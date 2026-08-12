<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
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
            'checkin_images' => 'nullable|array|max:5',
            'checkin_images.*' => 'image|mimes:jpg,jpeg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:5120|dimensions:min_width=1,min_height=1,max_width=8000,max_height=8000',
        ], [
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

        try {
            $comment = new Comment();

            if ($request->tour_id)    $comment->cm_tour_id    = $request->tour_id;
            if ($request->article_id) $comment->cm_article_id = $request->article_id;
            if ($request->hotel_id)   $comment->cm_hotel_id   = $request->hotel_id;

            $comment->cm_user_id = Auth::guard('users')->user()->id;
            $comment->cm_content = strip_tags(trim($request->message));
            $comment->cm_images  = upload_multiple_images('checkin_images');
            $comment->cm_status  = 1; // Hiển thị ngay, admin có thể ẩn/xoá sau
            $comment->save();

            $comment = Comment::with('user')->find($comment->id);
            $html    = view('page.common.itemComment', compact('comment'))->render();

            return response(['code' => 200, 'html' => $html]);
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
            $reply->cm_status     = 1;

            // Kế thừa loại bình luận từ comment cha
            $reply->cm_tour_id    = $parentComment->cm_tour_id;
            $reply->cm_article_id = $parentComment->cm_article_id;
            $reply->cm_hotel_id   = $parentComment->cm_hotel_id;

            $reply->save();

            $reply = Comment::with('user')->find($reply->id);
            $html  = view('page.common.itemReply', compact('reply'))->render();

            return response(['code' => 200, 'html' => $html]);
        } catch (\Exception $exception) {
            return response(['code' => 500, 'message' => 'Đã xảy ra lỗi, vui lòng thử lại'], 500);
        }
    }
}
