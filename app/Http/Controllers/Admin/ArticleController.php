<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{
    protected $article;
    /**
     * constructor.
     */
    public function __construct(Article  $article, Category $category)
    {
        view()->share([
            'article_active' => 'active',
            'actives' => $article::ACTIVES,
        ]);

        view()->composer(['admin.article.*'], function ($view) use ($category) {
            $view->with('categories', $category::get());
        });

        $this->article = $article;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $articles = Article::with('category');

        if ($request->a_title) {
            $articles->where('a_title', 'like', '%'.$request->a_title.'%');
        }

        if ($request->a_category_id) {
            $articles->where('a_category_id', $request->a_category_id);
            
        }

        if ($request->filled('a_active') && array_key_exists((int) $request->a_active, Article::ACTIVES)) {
            $articles->where('a_active', (int) $request->a_active);
        }

        $sort = $request->input('sort', 'latest');
        if ($sort === 'oldest') {
            $articles->orderBy('id');
        } elseif ($sort === 'title_asc') {
            $articles->orderBy('a_title');
        } elseif ($sort === 'title_desc') {
            $articles->orderByDesc('a_title');
        } else {
            $articles->orderByDesc('id');
        }

        $articles = $articles->paginate(NUMBER_PAGINATION)->withQueryString();

        return view('admin.article.index', compact('articles'));
    }

    public function preview($id)
    {
        $article = Article::with('category')->findOrFail($id);

        return view('admin.article.preview', compact('article'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.article.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        //
        \DB::beginTransaction();
        try {
            $this->article->createOrUpdate($request);
            \DB::commit();
            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $article = Article::findOrFail($id);

        if (!$article) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        return view('admin.article.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, $id)
    {
        //
        \DB::beginTransaction();
        try {
            $this->article->createOrUpdate($request, $id);
            \DB::commit();
            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    public function removeAlbumImage($id, $index)
    {
        $article = Article::find($id);
        if (!$article) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        $album = $article->a_album_images ? $article->a_album_images : [];
        if (isset($album[$index])) {
            $removedImage = $album[$index];
            array_splice($album, $index, 1);
            $article->a_album_images = array_values($album);
            $article->save();
            delete_uploaded_image($removedImage);
        }

        return redirect()->back()->with('success', 'Đã xóa ảnh khỏi album');
    }

    public function uploadInlineImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120|dimensions:min_width=1,min_height=1,max_width=8000,max_height=8000',
        ]);

        $image = upload_image('image', 'article-inline');

        if (($image['code'] ?? 0) !== 1) {
            return response()->json(['message' => 'Tệp ảnh không hợp lệ'], 422);
        }

        return response()->json([
            'url' => asset($image['path_img']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
        $article = Article::findOrFail($id);
        if (!$article) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        try {
            $article->delete();
            return redirect()->back()->with('success', 'Xóa thành công');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể xóa dữ liệu');
        }
    }
}
