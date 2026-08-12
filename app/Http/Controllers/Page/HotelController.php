<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HotelController extends Controller
{
    private const HOTEL_PER_PAGE = 9;

    //
    public function index(Request $request)
    {
        $validated = $request->validate([
            'destination' => 'nullable|string|max:191',
            'key_hotel' => 'nullable|string|max:191',
            'check_in' => 'nullable|required_with:check_out|date|after_or_equal:today',
            'check_out' => 'nullable|required_with:check_in|date|after:check_in',
            'adults' => 'nullable|integer|min:1|max:30',
            'children' => 'nullable|integer|min:0|max:20',
            'rooms' => 'nullable|integer|min:1|max:10',
            'types' => 'nullable|array',
            'types.*' => ['string', Rule::in(array_keys(Hotel::ACCOMMODATION_TYPES))],
            'stars' => 'nullable|array',
            'stars.*' => 'integer|between:1,5',
            'amenities' => 'nullable|array',
            'amenities.*' => ['string', Rule::in(array_keys(Hotel::AMENITIES))],
            'room_facilities' => 'nullable|array',
            'room_facilities.*' => ['string', Rule::in(array_keys(Hotel::ROOM_FACILITIES))],
            'property_policies' => 'nullable|array',
            'property_policies.*' => ['string', Rule::in(array_keys(Hotel::PROPERTY_POLICIES))],
            'meal_plans' => 'nullable|array',
            'meal_plans.*' => ['string', Rule::in(array_keys(Hotel::MEAL_PLANS))],
            'suitable_for' => 'nullable|array',
            'suitable_for.*' => ['string', Rule::in(array_keys(Hotel::SUITABLE_FOR))],
        ], [
            'check_in.required_with' => 'Vui lòng chọn ngày nhận phòng.',
            'check_in.after_or_equal' => 'Ngày nhận phòng không được ở trong quá khứ.',
            'check_out.required_with' => 'Vui lòng chọn ngày trả phòng.',
            'check_out.after' => 'Ngày trả phòng phải sau ngày nhận phòng.',
            'adults.min' => 'Cần có ít nhất 1 người lớn.',
            'rooms.min' => 'Cần chọn ít nhất 1 phòng.',
        ]);

        $hotels = Hotel::with('user');
        $destination = trim((string) ($validated['destination'] ?? $validated['key_hotel'] ?? ''));

        if ($destination !== '') {
            $hotels->where(function ($query) use ($destination) {
                $query->where('h_name', 'like', '%'.$destination.'%')
                    ->orWhere('h_address', 'like', '%'.$destination.'%');
            });
        }

        $selectedFilters = [
            'types' => array_values(array_unique($validated['types'] ?? [])),
            'stars' => array_values(array_unique(array_map('intval', $validated['stars'] ?? []))),
            'amenities' => array_values(array_unique($validated['amenities'] ?? [])),
            'room_facilities' => array_values(array_unique($validated['room_facilities'] ?? [])),
            'property_policies' => array_values(array_unique($validated['property_policies'] ?? [])),
            'meal_plans' => array_values(array_unique($validated['meal_plans'] ?? [])),
            'suitable_for' => array_values(array_unique($validated['suitable_for'] ?? [])),
        ];

        if ($selectedFilters['types']) {
            $hotels->whereIn('h_accommodation_type', $selectedFilters['types']);
        }

        if ($selectedFilters['stars']) {
            $hotels->whereIn('h_star_rating', $selectedFilters['stars']);
        }

        foreach ($selectedFilters['amenities'] as $amenity) {
            $hotels->where('h_amenities', 'like', '%"'.$amenity.'"%');
        }

        foreach ($selectedFilters['room_facilities'] as $facility) {
            $hotels->where('h_room_facilities', 'like', '%"'.$facility.'"%');
        }

        foreach ($selectedFilters['property_policies'] as $policy) {
            $hotels->where('h_property_policies', 'like', '%"'.$policy.'"%');
        }

        foreach ($selectedFilters['meal_plans'] as $mealPlan) {
            $hotels->where('h_meal_plans', 'like', '%"'.$mealPlan.'"%');
        }

        if ($selectedFilters['suitable_for']) {
            $hotels->where(function ($query) use ($selectedFilters) {
                foreach ($selectedFilters['suitable_for'] as $group) {
                    $query->orWhere('h_suitable_for', 'like', '%"'.$group.'"%');
                }
            });
        }

        $hotels = $hotels->active()->orderByDesc('id')->paginate(self::HOTEL_PER_PAGE)->withQueryString();
        $searchContext = [
            'destination' => $destination,
            'check_in' => $validated['check_in'] ?? null,
            'check_out' => $validated['check_out'] ?? null,
            'adults' => $validated['adults'] ?? 2,
            'children' => $validated['children'] ?? 0,
            'rooms' => $validated['rooms'] ?? 1,
        ];

        $filterCounts = $this->buildFilterCounts();

        return view('page.hotel.index', compact(
            'hotels',
            'searchContext',
            'selectedFilters',
            'filterCounts'
        ));
    }

    public function detail(Request $request, $id)
    {
        $hotel = Hotel::with(['comments' => function($query) use ($id){
            $query->with(['user', 'replies' => function($q) {
                $q->with('user')->limit(10);
            }])->where('cm_hotel_id', $id)
              ->where('cm_status', '!=', 3) // Ẩn những BL admin đã ẩn (status=3)
              ->limit(20)->orderByDesc('id');
        }])->find($id);
        if (!$hotel) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        $hotels = Hotel::with('user')->where('id', '<>', $id)->active()->orderByDesc('id')->limit(NUMBER_PAGINATION_PAGE)->get();

        $stayValidator = Validator::make($request->only([
            'destination', 'check_in', 'check_out', 'adults', 'children', 'rooms'
        ]), [
            'destination' => 'nullable|string|max:191',
            'check_in' => 'nullable|required_with:check_out|date',
            'check_out' => 'nullable|required_with:check_in|date|after:check_in',
            'adults' => 'nullable|integer|min:1|max:30',
            'children' => 'nullable|integer|min:0|max:20',
            'rooms' => 'nullable|integer|min:1|max:10',
        ]);

        $stayContext = array_merge([
            'destination' => null,
            'check_in' => null,
            'check_out' => null,
            'adults' => 2,
            'children' => 0,
            'rooms' => 1,
        ], $stayValidator->fails() ? [] : $stayValidator->validated());

        return view('page.hotel.detail', compact('hotel', 'hotels', 'stayContext'));
    }

    public function bookTour()
    {
        return view('page.tour.book');
    }

    private function buildFilterCounts(): array
    {
        $hotels = Hotel::active()->get([
            'h_accommodation_type',
            'h_star_rating',
            'h_amenities',
            'h_room_facilities',
            'h_property_policies',
            'h_meal_plans',
            'h_suitable_for',
        ]);

        return [
            'types' => collect(Hotel::ACCOMMODATION_TYPES)->mapWithKeys(function ($label, $key) use ($hotels) {
                return [$key => $hotels->where('h_accommodation_type', $key)->count()];
            })->all(),
            'stars' => collect(range(5, 1))->mapWithKeys(function ($star) use ($hotels) {
                return [$star => $hotels->where('h_star_rating', $star)->count()];
            })->all(),
            'amenities' => collect(Hotel::AMENITIES)->mapWithKeys(function ($label, $key) use ($hotels) {
                return [$key => $hotels->filter(function ($hotel) use ($key) {
                    return in_array($key, $hotel->h_amenities ?? [], true);
                })->count()];
            })->all(),
            'room_facilities' => collect(Hotel::ROOM_FACILITIES)->mapWithKeys(function ($label, $key) use ($hotels) {
                return [$key => $hotels->filter(function ($hotel) use ($key) {
                    return in_array($key, $hotel->h_room_facilities ?? [], true);
                })->count()];
            })->all(),
            'property_policies' => collect(Hotel::PROPERTY_POLICIES)->mapWithKeys(function ($label, $key) use ($hotels) {
                return [$key => $hotels->filter(function ($hotel) use ($key) {
                    return in_array($key, $hotel->h_property_policies ?? [], true);
                })->count()];
            })->all(),
            'meal_plans' => collect(Hotel::MEAL_PLANS)->mapWithKeys(function ($label, $key) use ($hotels) {
                return [$key => $hotels->filter(function ($hotel) use ($key) {
                    return in_array($key, $hotel->h_meal_plans ?? [], true);
                })->count()];
            })->all(),
            'suitable_for' => collect(Hotel::SUITABLE_FOR)->mapWithKeys(function ($label, $key) use ($hotels) {
                return [$key => $hotels->filter(function ($hotel) use ($key) {
                    return in_array($key, $hotel->h_suitable_for ?? [], true);
                })->count()];
            })->all(),
        ];
    }
}
