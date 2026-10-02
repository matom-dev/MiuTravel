<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Comment;
use App\Models\Tour;

class AgencyController extends Controller
{
    public function show(int $id)
    {
        $agency = Agency::findOrFail($id);
        $publicTours = Tour::where('agency_id', $agency->id)->visibleToCustomers();
        $tourCount = (clone $publicTours)->count();

        if ($tourCount === 0) {
            abort(404);
        }

        $approvedReviews = fn ($query) => $query
            ->where('cm_status', Comment::STATUS_APPROVED)
            ->whereNull('cm_reply_id');

        $tours = $publicTours->with(['location', 'latestApprovedReview.user'])
            ->withCount(['comments as review_count' => $approvedReviews])
            ->withAvg(['comments as average_rating' => $approvedReviews], 'cm_rating')
            ->orderBy('t_status')
            ->orderByDesc('id')
            ->paginate(12);

        return view('page.agency.show', compact('agency', 'tourCount', 'tours'));
    }
}
