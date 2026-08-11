<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\RichTextSanitizer;


class Tour extends Model
{
    use HasFactory;
    protected $table = 'tours';
    public $timestamps = true;

    protected $fillable = [
        't_title',
        't_journeys',
        't_schedule',
        't_duration_days',
        't_duration_nights',
        't_move_method',
        't_starting_gate',
        't_start_date',
        't_end_date',
        't_number_guests',
        't_price_adults',
        't_price_children',
        't_sale',
        't_view',
        't_description',
        't_content',
        't_activities',
        't_guides',
        't_anbum_image',
        't_image',
        't_location_id',
        't_user_id',
        't_number_registered',
        't_follow',
        't_status',
    ];

    protected $casts = [
        't_anbum_image' => 'array',
        't_activities' => 'array',
        't_guides' => 'array',
        't_duration_days' => 'integer',
        't_duration_nights' => 'integer',
    ];

    public function setTDescriptionAttribute($value)
    {
        $this->attributes['t_description'] = app(RichTextSanitizer::class)->clean($value);
    }

    public function setTContentAttribute($value)
    {
        $this->attributes['t_content'] = app(RichTextSanitizer::class)->clean($value);
    }

    public function getTAnbumImageAttribute($value)
    {
        return $this->normalizeAlbumImages($value);
    }

    public function getTActivitiesAttribute($value)
    {
        return $this->normalizeJsonList($value);
    }

    public function getTGuidesAttribute($value)
    {
        return $this->normalizeJsonList($value);
    }

    public function getEffectiveDurationDaysAttribute(): int
    {
        $durationDays = (int) ($this->t_duration_days ?: 0);

        if ($durationDays < 1) {
            $durationDays = $this->parseDurationNumber(['ngày', 'ngay', 'days?', 'n']) ?: 1;
        }

        return max(1, $durationDays);
    }

    public function getEffectiveDurationNightsAttribute(): int
    {
        if ($this->t_duration_nights !== null) {
            return max(0, (int) $this->t_duration_nights);
        }

        $durationNights = $this->parseDurationNumber(['đêm', 'dem', 'nights?', 'd']);

        return $durationNights !== null ? max(0, $durationNights) : max(0, $this->effective_duration_days - 1);
    }

    public function getDurationTextAttribute(): string
    {
        $schedule = trim((string) $this->t_schedule);

        return $schedule !== ''
            ? $schedule
            : $this->effective_duration_days . ' ngày ' . $this->effective_duration_nights . ' đêm';
    }

    protected function normalizeAlbumImages($value)
    {
        if (is_array($value)) {
            return array_values(array_filter($value));
        }

        if (!is_string($value)) {
            return [];
        }

        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return array_values(array_filter($decoded));
        }

        $decodedAgain = json_decode($decoded ?? '', true);
        if (is_array($decodedAgain)) {
            return array_values(array_filter($decodedAgain));
        }

        return [];
    }

    protected function normalizeJsonList($value)
    {
        if (is_array($value)) {
            return array_values(array_filter($value));
        }

        if (!is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? array_values(array_filter($decoded)) : [];
    }

    const STATUS = [
        1 => 'Còn nhận đặt',
        2 => 'Tạm ngưng nhận đặt',
        3 => 'Ngừng hiển thị',
    ];

    const STATUS_BADGE_CLASS = [
        1 => 'badge-success',
        2 => 'badge-warning',
        3 => 'badge-secondary',
    ];

    const STATUS_ICON = [
        1 => 'fas fa-check-circle',
        2 => 'fas fa-pause-circle',
        3 => 'fas fa-eye-slash',
    ];

    const PUBLIC_STATUS_ICON = [
        1 => 'fa fa-check-circle',
        2 => 'fa fa-pause-circle',
        3 => 'fa fa-eye-slash',
    ];

    public function getIsBookableAttribute(): bool
    {
        return (int) $this->t_status === 1;
    }

    public function getIsPubliclyVisibleAttribute(): bool
    {
        return in_array((int) $this->t_status, [1, 2], true);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[(int) $this->t_status] ?? 'Không rõ';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return self::STATUS_BADGE_CLASS[(int) $this->t_status] ?? 'badge-secondary';
    }

    public function getStatusIconAttribute(): string
    {
        return self::STATUS_ICON[(int) $this->t_status] ?? 'fas fa-question-circle';
    }

    public function getPublicStatusIconAttribute(): string
    {
        return self::PUBLIC_STATUS_ICON[(int) $this->t_status] ?? 'fa fa-question-circle';
    }

    /**
     * Scope lấy các tour còn nhận đặt (status = 1).
     * Dùng thống nhất thay cho->where('t_status', 1) rải rác khắp nơi.
     */
    public function scopeActive($query)
    {
        return $query->where('t_status', 1);
    }

    public function scopeVisibleToCustomers($query)
    {
        return $query->whereIn('t_status', [1, 2]);
    }

    public function createOrUpdate($request , $id ='')
    {
        $params = $request->except([
            'images',
            'album_images',
            '_token',
            'submit',
            'activity_title',
            'activity_icon',
            'activity_description',
            'guide_name',
            'guide_role',
            'guide_phone',
            'guide_email',
            'guide_experience',
            'guide_languages',
            'guide_photo',
            'guide_photo_old',
            'tour_leader_id',
            'tour_guide_ids',
            'schedule_id',
            'schedule_start_date',
            'schedule_end_date',
            'schedule_number_guests',
            'schedule_status',
            't_number_guests',
            't_start_date',
            't_end_date',
        ]);

        $params['t_duration_days'] = $request->t_duration_days;
        $params['t_duration_nights'] = $request->t_duration_nights;
        $params['t_schedule'] = $this->formatDuration($request->t_duration_days, $request->t_duration_nights);
        $params['t_start_date'] = null;
        $params['t_end_date'] = null;
        $params['t_activities'] = $this->buildActivities($request);
        $params['t_guides'] = $this->buildGuides($request);

        // Upload ảnh đại diện
        if ($request->hasFile('images')) {
            $image = upload_image('images');
            if ($image['code'] == 1)
                $params['t_image'] = $image['name'];
        }

        // Upload nhiều ảnh album
        if ($request->hasFile('album_images')) {
            $existingAlbum = [];
            if ($id) {
                $existing = $this->find($id);
                $existingAlbum = $existing && $existing->t_anbum_image ? $existing->t_anbum_image : [];
            }
            $uploadedAlbum = upload_multiple_images('album_images');
            // Không dùng json_encode thủ công vì model đã có $casts => 'array'
            // Eloquent tự động encode khi lưu
            $params['t_anbum_image'] = array_merge($existingAlbum, $uploadedAlbum);
        }

        $params['t_sale'] = $request->t_sale ? $request->t_sale : 0;
        if ($id) {
            $tour = $this->find($id);
            $updated = $tour->update($params);
            $this->syncGuideAssignments($tour, $request);

            return $updated;
        }
        $params['t_user_id'] = Auth::guard('admins')->id();
        $params['t_number_guests'] = 0;
        $tour = $this->create($params);
        $this->syncGuideAssignments($tour, $request);

        return $tour;
    }

    protected function formatDuration($days, $nights)
    {
        $days = max(1, (int) $days);
        $nights = max(0, (int) $nights);

        return $days . ' ngày ' . $nights . ' đêm';
    }

    protected function parseDurationNumber(array $units): ?int
    {
        $schedule = trim((string) $this->t_schedule);

        if ($schedule === '') {
            return null;
        }

        $unitPattern = implode('|', $units);

        if (preg_match('/(\d+)\s*(?:' . $unitPattern . ')\b/iu', $schedule, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    protected function buildActivities($request)
    {
        $titles = $request->activity_title ?: [];
        $icons = $request->activity_icon ?: [];
        $descriptions = $request->activity_description ?: [];
        $activities = [];

        foreach ($titles as $index => $title) {
            $title = trim((string) $title);
            $description = trim((string) ($descriptions[$index] ?? ''));

            if ($title === '' && $description === '') {
                continue;
            }

            $activities[] = [
                'title' => $title,
                'icon' => trim((string) ($icons[$index] ?? 'fa fa-check-circle')),
                'description' => $description,
            ];
        }

        return $activities;
    }

    protected function buildGuides($request)
    {
        if ($request->has('tour_leader_id') || $request->has('tour_guide_ids')) {
            return $this->buildGuideSnapshotsFromStaff($request);
        }

        $names = $request->guide_name ?: [];
        $roles = $request->guide_role ?: [];
        $phones = $request->guide_phone ?: [];
        $emails = $request->guide_email ?: [];
        $experiences = $request->guide_experience ?: [];
        $languages = $request->guide_languages ?: [];
        $photos = $request->file('guide_photo') ?: [];
        $oldPhotos = $request->guide_photo_old ?: [];
        $guides = [];

        foreach ($names as $index => $name) {
            $name = trim((string) $name);
            $phone = trim((string) ($phones[$index] ?? ''));
            $email = trim((string) ($emails[$index] ?? ''));
            $photo = trim((string) ($oldPhotos[$index] ?? ''));

            if (isset($photos[$index]) && $photos[$index] && $photos[$index]->isValid()) {
                $uploadedPhoto = $this->uploadGuidePhoto($photos[$index]);
                if ($uploadedPhoto) {
                    $photo = $uploadedPhoto;
                }
            }

            if ($name === '' && $phone === '' && $email === '' && $photo === '') {
                continue;
            }

            $guides[] = [
                'name' => $name,
                'role' => trim((string) ($roles[$index] ?? 'Hướng dẫn viên')),
                'phone' => $phone,
                'email' => $email,
                'experience' => trim((string) ($experiences[$index] ?? '')),
                'languages' => trim((string) ($languages[$index] ?? '')),
                'photo' => $photo,
            ];
        }

        return $guides;
    }

    protected function buildGuideSnapshotsFromStaff($request)
    {
        $leaderId = $request->tour_leader_id;
        $guideIds = array_values(array_filter((array) $request->tour_guide_ids));
        $selectedIds = array_values(array_unique(array_filter(array_merge([$leaderId], $guideIds))));

        if (empty($selectedIds)) {
            return [];
        }

        $staff = TourGuide::whereIn('id', $selectedIds)->get()->keyBy('id');
        $guides = [];

        if ($leaderId && isset($staff[$leaderId])) {
            $guides[] = $this->makeGuideSnapshot($staff[$leaderId], 'leader');
        }

        foreach ($guideIds as $guideId) {
            if (!isset($staff[$guideId]) || (string) $guideId === (string) $leaderId) {
                continue;
            }

            $guides[] = $this->makeGuideSnapshot($staff[$guideId], 'guide');
        }

        return $guides;
    }

    protected function makeGuideSnapshot($guide, $assignmentRole)
    {
        return [
            'id' => $guide->id,
            'name' => $guide->tg_name,
            'role' => TourGuide::ASSIGNMENT_ROLES[$assignmentRole] ?? TourGuide::ROLES[$guide->tg_role] ?? 'Hướng dẫn viên',
            'gender' => $guide->tg_gender,
            'birth_date' => $guide->tg_birth_date ? $guide->tg_birth_date->format('Y-m-d') : null,
            'hometown' => $guide->tg_hometown,
            'phone' => $guide->tg_phone,
            'email' => $guide->tg_email,
            'experience' => $guide->tg_experience,
            'languages' => $guide->tg_languages,
            'photo' => $guide->tg_photo,
        ];
    }

    protected function syncGuideAssignments($tour, $request)
    {
        if (!$request->has('tour_leader_id') && !$request->has('tour_guide_ids')) {
            return;
        }

        $tour->guideAssignments()->delete();

        if ($request->tour_leader_id) {
            $tour->guideAssignments()->create([
                'tga_guide_id' => $request->tour_leader_id,
                'tga_role' => 'leader',
            ]);
        }

        foreach (array_values(array_unique(array_filter((array) $request->tour_guide_ids))) as $guideId) {
            if ((string) $guideId === (string) $request->tour_leader_id) {
                continue;
            }

            $tour->guideAssignments()->create([
                'tga_guide_id' => $guideId,
                'tga_role' => 'guide',
            ]);
        }
    }

    protected function uploadGuidePhoto($file)
    {
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) {
            return '';
        }

        $nameFile = trim(str_replace('.' . $ext, '', md5($file->getClientOriginalName() . microtime(true))));
        $filename = date('Y-m-d__') . Str::slug($nameFile) . '.' . $ext;
        $path = public_path() . '/uploads/' . date('Y/m/d/');

        if (!\File::exists($path)) {
            mkdir($path, 0777, true);
        }

        $file->move($path, $filename);

        return $filename;
    }

    public function location ()
    {
        return $this->belongsTo(Location::class, 't_location_id', 'id')->where('l_status', 1);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 't_user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'cm_tour_id', 'id');
    }
    public function booktour()
    {
        return $this->hasMany(BookTour::class, 'b_tour_id', 'id');
    }

    public function schedules()
    {
        return $this->hasMany(TourSchedule::class, 'ts_tour_id', 'id');
    }

    public function activeSchedules()
    {
        return $this->hasMany(TourSchedule::class, 'ts_tour_id', 'id')
            ->active()
            ->orderBy('ts_start_date');
    }

    public function guideAssignments()
    {
        return $this->hasMany(TourGuideAssignment::class, 'tga_tour_id', 'id');
    }

    public function staffGuides()
    {
        return $this->belongsToMany(TourGuide::class, 'tour_guide_assignments', 'tga_tour_id', 'tga_guide_id')
            ->withPivot('tga_role')
            ->withTimestamps();
    }
}
