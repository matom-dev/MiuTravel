<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use App\Models\BookTour;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Gửi bình luận mới (AJAX).
     */
    public function comment(Request $request)
    {
        if (!$request->ajax()) {
            return response(['code' => 400, 'message' => 'Bad request'], 400);
        }

        // Kiểm tra đăng nhập
        if (!Auth::guard('users')->check()) {
            return response(['code' => 401, 'message' => 'Vui lòng đăng nhập để bình luận'], 401);
        }

        // Validate nội dung
        if (empty(trim($request->message ?? ''))) {
            return response(['code' => 422, 'message' => 'Nội dung không được để trống'], 422);
        }

        $validator = Validator::make($request->all(), [
            'tour_id' => 'nullable|integer',
            'article_id' => 'nullable|integer',
            'hotel_id' => 'nullable|integer',
            'reply_id' => 'nullable|integer',
            'rating' => 'nullable|integer|between:1,5',
            'checkin_images' => 'nullable|array|max:5',
            'checkin_images.*' => 'image|mimes:jpg,jpeg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:5120|dimensions:min_width=1,min_height=1,max_width=8000,max_height=8000',
        ], [
            'rating.between' => 'Vui lòng chọn số sao từ 1 đến 5',
            'checkin_images.max' => 'Chỉ được tải tối đa 5 ảnh check-in',
            'checkin_images.*.image' => 'Tệp tải lên phải là hình ảnh',
            'checkin_images.*.mimes' => 'Ảnh check-in chỉ hỗ trợ JPG, PNG hoặc WEBP',
            'checkin_images.*.max' => 'Mỗi ảnh check-in không được vượt quá 5MB',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $isTourReview = $request->filled('tour_id') && !$request->filled('reply_id');
        if ($isTourReview && !$this->userHasEligibleTourBooking((int) $request->tour_id)) {
            return response([
                'code' => 403,
                'message' => 'Chỉ khách đã có booking tour được xác nhận mới được gửi đánh giá.',
            ], 403);
        }

        if ($isTourReview && !$request->filled('rating')) {
            return response([
                'code' => 422,
                'message' => 'Vui lòng chọn số sao đánh giá tour.',
            ], 422);
        }

        try {
            $comment = new Comment();

            if ($request->tour_id)    $comment->cm_tour_id    = $request->tour_id;
            if ($request->article_id) $comment->cm_article_id = $request->article_id;
            if ($request->hotel_id)   $comment->cm_hotel_id   = $request->hotel_id;

            $comment->cm_user_id = Auth::guard('users')->user()->id;
            $comment->cm_content = strip_tags(trim($request->message));
            $comment->cm_rating  = $request->filled('rating') ? (int) $request->rating : null;
            $comment->cm_images  = upload_multiple_images('checkin_images');
            $comment->cm_status  = Comment::STATUS_PENDING;
            $comment->save();

            $this->notifyAdminsAboutPendingComment($comment);

            return response([
                'code' => 200,
                'html' => '',
                'message' => 'Cảm ơn bạn. Nội dung đã được gửi và đang chờ duyệt.',
            ]);
        } catch (\Exception $exception) {
            return response(['code' => 500, 'message' => 'Đã xảy ra lỗi, vui lòng thử lại'], 500);
        }
    }

    /**
     * Gửi trả lời bình luận (AJAX).
     */
    public function replyComment(Request $request)
    {
        if (!$request->ajax()) {
            return response(['code' => 400, 'message' => 'Bad request'], 400);
        }

        // Kiểm tra đăng nhập
        if (!Auth::guard('users')->check()) {
            return response(['code' => 401, 'message' => 'Vui lòng đăng nhập để trả lời'], 401);
        }

        if (empty(trim($request->message ?? '')) || empty($request->reply_id)) {
            return response(['code' => 422, 'message' => 'Dữ liệu không hợp lệ'], 422);
        }

        // Kiểm tra bình luận cha tồn tại
        $parentComment = Comment::find($request->reply_id);
        if (!$parentComment) {
            return response(['code' => 404, 'message' => 'Bình luận không tồn tại'], 404);
        }

        try {
            $reply = new Comment();
            $reply->cm_reply_id   = $request->reply_id;
            $reply->cm_user_id    = Auth::guard('users')->user()->id;
            $reply->cm_content    = strip_tags(trim($request->message));
            $reply->cm_status     = Comment::STATUS_PENDING;

            // Kế thừa loại bình luận từ comment cha
            $reply->cm_tour_id    = $parentComment->cm_tour_id;
            $reply->cm_article_id = $parentComment->cm_article_id;
            $reply->cm_hotel_id   = $parentComment->cm_hotel_id;

            $reply->save();

            return response([
                'code' => 200,
                'html' => '',
                'message' => 'Phản hồi đã được gửi và đang chờ duyệt.',
            ]);
        } catch (\Exception $exception) {
            return response(['code' => 500, 'message' => 'Đã xảy ra lỗi, vui lòng thử lại'], 500);
        }
    }

    private function userHasEligibleTourBooking(int $tourId): bool
    {
        return BookTour::where('b_user_id', Auth::guard('users')->id())
            ->where('b_tour_id', $tourId)
            ->whereIn('b_status', [
                BookTour::STATUS_CONFIRMED,
                BookTour::STATUS_PAID,
                BookTour::STATUS_COMPLETED,
            ])
            ->exists();
    }

    private function notifyAdminsAboutPendingComment(Comment $comment): void
    {
        if (!Schema::hasTable('app_notifications')) {
            return;
        }

        try {
            $target = 'nội dung';
            if ($comment->cm_tour_id) {
                $target = 'tour';
            } elseif ($comment->cm_hotel_id) {
                $target = 'khách sạn';
            } elseif ($comment->cm_article_id) {
                $target = 'bài viết';
            }

            AppNotification::create([
                'receiver_guard' => 'admins',
                'type' => 'comment_pending',
                'title' => 'Có bình luận chờ duyệt',
                'message' => 'Khách ' . Auth::guard('users')->user()->name . ' vừa gửi bình luận mới cho ' . $target . '.',
                'url' => route('comment.index', ['status' => Comment::STATUS_PENDING], false),
                'data' => [
                    'comment_id' => $comment->id,
                    'user_id' => $comment->cm_user_id,
                    'tour_id' => $comment->cm_tour_id,
                    'hotel_id' => $comment->cm_hotel_id,
                    'article_id' => $comment->cm_article_id,
                ],
            ]);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
