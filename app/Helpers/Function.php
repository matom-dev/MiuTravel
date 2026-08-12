<?php
use Carbon\Carbon;

function randString($length)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = '';
    $size = strlen($chars);
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[rand(0, $size - 1)];
    }
    return $str;
}

/**
 * function Cut string
 *
 * @param    string $text
 * @return     string lenght $num
 */
function customDate($startDate, $endDate){

    Carbon::setLocale('vn'); // hiển thị ngôn ngữ tiếng việt.
    $fromDate = Carbon::parse($startDate);
    $toDate = Carbon::parse($endDate);

    return  $fromDate->diffForHumans($toDate); //12 phút trước
}

if ( ! function_exists('safeTitle')) {
    function safeTitle($str = '')
    {
        $str = html_entity_decode($str, ENT_QUOTES, "UTF-8");
        $filter_in = array('#(a|à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#', '#(A|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#', '#(e|è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#', '#(E|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#', '#(i|ì|í|ị|ỉ)#', '#(I|ĩ|Ì|Í|Ị|Ỉ|Ĩ)#', '#(o|ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#', '#(O|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#', '#(u|ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#', '#(U|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#', '#(y|ỳ|ý|ỵ|ỷ|ỹ)#', '#(Y|Ỳ|Ý|Ỵ|Ỷ|Ỹ)#', '#(d|đ)#', '#(D|Đ)#');
        $filter_out = array('a', 'A', 'e', 'E', 'i', 'I', 'o', 'O', 'u', 'U', 'y', 'Y', 'd', 'D');
        $text = preg_replace($filter_in, $filter_out, $str);
        $text = preg_replace('/[^a-zA-Z0-9]/', ' ', $text);
        $text = trim(preg_replace('/ /', '-', trim(strtolower($text))));
        $text = preg_replace('/--/', '-', $text);
        $text = preg_replace('/--/', '-', $text);
        return preg_replace('/--/', '-', $text);
    }
}

/**
 * @param string $stringDate
 * @return false|string
 */
if (!function_exists('formatDate')) {
    function formatDate(string $stringDate) {
        return date('Y-m-d', strtotime($stringDate));
    }
}

if (!function_exists('get_data_user')) {
    function get_data_user($type, $field = 'id')
    {
        return \Auth::guard($type)->user() ? Auth::guard($type)->user()->$field : '';
    }
}


if (!function_exists('get_info_user'))
{
    function get_info_user($type, $field = 'id')
    {
        return Auth::guard($type)->user() ? Auth::guard($type)->user()->$field : '';
    }
}

if (!function_exists('upload_image')) {
    /**
     * @param $file [tên file trùng tên input]
     * @param array $extend [ định dạng file có thể upload được]
     * @return array|int [ tham số trả về là 1 mảng - nếu lỗi trả về int ]
     */
    function upload_image($file, $folder = '', array $extend = array())
    {
        return app(\App\Services\MediaUploadService::class)->uploadOne($file, $folder, $extend);
    }
}

if (!function_exists('upload_multiple_images')) {
    /**
     * Upload nhiều ảnh cùng lúc
     * @param string $field - tên input file (multiple)
     * @param string $folder - thư mục con (tuỳ chọn)
     * @return array - mảng tên file đã upload
     */
    function upload_multiple_images($field, $folder = '')
    {
        return app(\App\Services\MediaUploadService::class)->uploadMany($field, $folder);
    }
}

if (!function_exists('delete_uploaded_image')) {
    function delete_uploaded_image($image, $folder = '')
    {
        app(\App\Services\MediaUploadService::class)->deleteImage($image, $folder);
    }
}

if (!function_exists('thumbnail_url_file')) {
    function thumbnail_url_file($image, $folder = '')
    {
        if (!$image) {
            return pare_url_file($image, $folder);
        }

        $path = pare_url_file($image, $folder);
        $path = ltrim($path, '/');
        $directory = dirname($path);

        return '/' . trim($directory . '/thumbnails/' . basename($path), '/');
    }
}

if (!function_exists('pare_url_file')) {
    function pare_url_file($image, $folder = '')
    {
        if (!$image) {
            return 'page/img/teachers/teacher-01.png';
        }

        $explode = explode('__', $image);

        if (isset($explode[0])) {
            $datePart = str_replace('_', '/', $explode[0]);
            $timestamp = strtotime($datePart);

            // Kiểm tra date hợp lệ (phải sau năm 2000)
            if ($timestamp && $timestamp > mktime(0, 0, 0, 1, 1, 2000)) {
                return '/uploads' . $folder . '/' . date('Y/m/d', $timestamp) . '/' . $image;
            }
        }

        // Fallback: thử trực tiếp trong thư mục uploads
        return '/uploads' . $folder . '/' . $image;
    }
}

/**
 * function Cut string
 *
 * @param    string $text
 * @return     string lenght $num
 */
function the_excerpt($text ,$num){

    if(strlen($text)> $num){

        $cutstring = substr($text,0,$num);
        $word = substr($text,0,strrpos($cutstring,' '));
        return $word. ' ...';

    }
    else{
        return $text;
    }

}

if (!function_exists('article_url')) {
    function article_url($article)
    {
        $slug = safeTitle($article->a_title ?? '');
        $category = null;

        if (method_exists($article, 'relationLoaded') && $article->relationLoaded('category')) {
            $category = $article->category;
        } elseif (method_exists($article, 'category')) {
            $category = $article->category()->first();
        }

        if ($category && $category->c_slug && $category->c_slug !== 'tin-tuc') {
            return route('articles.category.detail', [
                'category' => $category->c_slug,
                'id' => $article->id,
                'slug' => $slug,
            ]);
        }

        return route('articles.detail', [
            'id' => $article->id,
            'slug' => $slug,
        ]);
    }
}

function getTimeRegister($timeStart) {
    $start = new Carbon($timeStart);
    $currentTime = Carbon::now();
    $timeThi = Carbon::parse($currentTime)->diffInMinutes($start, false);
    return $timeThi;
}
