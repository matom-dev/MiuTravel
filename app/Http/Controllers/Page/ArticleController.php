<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;

class ArticleController extends Controller
{
    private const ARTICLE_PER_PAGE = 8;

    //
    public function index(Request $request)
    {
        $category = Category::where('c_slug', 'tin-tuc')
            ->where('c_status', 1)
            ->first();

        return $this->listing($request, $category);
    }

    public function category(Request $request, $slug)
    {
        $category = Category::where('c_slug', $slug)
            ->where('c_status', 1)
            ->firstOrFail();

        return $this->listing($request, $category);
    }

    private function listing(Request $request, ?Category $category = null)
    {
        $featuredLimit = $category && in_array($category->c_slug, ['dac-san', 'kinh-nghiem-du-lich', 'tin-tuc'], true)
            ? 2
            : 3;

        $featuredArticles = Article::with(['user', 'category'])
            ->active()
            ->when($category, function ($query) use ($category) {
                $query->where('a_category_id', $category->id);
            })
            ->orderByDesc('id')
            ->limit($featuredLimit)
            ->get();

        $articles = Article::with(['user', 'category'])
            ->active()
            ->when($category, function ($query) use ($category) {
                $query->where('a_category_id', $category->id);
            });

        if ($request->key_search) {
            $articles->where('a_title', 'like', '%'.$request->key_search.'%');
        }

        $articles = $articles->orderByDesc('id')->paginate(self::ARTICLE_PER_PAGE)->withQueryString();

        return view('page.articles.index', compact('articles', 'featuredArticles', 'category'));
    }

    public function categoryDetail(Request $request, $categorySlug, $id)
    {
        return $this->detail($request, $id);
    }

    public function detail(Request $request, $id)
    {
        $article = Article::with(['user', 'category', 'comments' => function ($query) use ($id) {
            $query->with(['user', 'replies' => function ($q) {
                $q->with('user')->where('cm_status', Comment::STATUS_APPROVED)->limit(10);
            }])->where('cm_article_id', $id)
              ->where('cm_status', Comment::STATUS_APPROVED)
              ->limit(20)
              ->orderByDesc('id');
        }])->find($id);

        if (!$article) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        // Tăng view count
        $article->increment('a_view');

        $articleCategory = $article->category;
        $categories = Category::with('news')
            ->where('c_status', 1)
            ->where('c_type', 2)
            ->get();
        $articles = Article::with(['user', 'category'])
            ->active()
            ->where('a_category_id', $article->a_category_id)
            ->where('id', '!=', $article->id)
            ->orderByDesc('id')
            ->limit(NUMBER_PAGINATION_PAGE)
            ->get();

        return view('page.articles.detail', compact('article', 'categories', 'articles', 'articleCategory'));
    }
}
