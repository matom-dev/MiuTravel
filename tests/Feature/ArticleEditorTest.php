<?php

namespace Tests\Feature;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class ArticleEditorTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_form_uses_one_editor_and_generates_summary_from_content(): void
    {
        $category = $this->createCategory();
        $html = View::make('admin.article.form', [
            'categories' => collect([$category]),
            'actives' => Article::ACTIVES,
            'errors' => (new ViewErrorBag())->put('default', new MessageBag()),
        ])->render();

        $this->assertSame(1, substr_count($html, 'name="a_content"'));
        $this->assertStringNotContainsString('name="a_description"', $html);
        $this->assertStringNotContainsString('Tóm tắt hiển thị', $html);
        $this->assertStringNotContainsString('Hệ thống sẽ tự tạo đoạn tóm tắt', $html);

        $request = Request::create('/admin/article/create', 'POST', [
            'a_title' => 'Bai viet mot trinh soan thao',
            'a_category_id' => $category->id,
            'a_active' => 1,
            'a_content' => '<h2>Mo dau hanh trinh</h2><p>Day la noi dung dau tien dung de tao tom tat cho danh sach bai viet.</p>',
        ]);

        $article = (new Article())->createOrUpdate($request);

        $this->assertStringContainsString('Mo dau hanh trinh', $article->a_description);
        $this->assertStringContainsString('Day la noi dung dau tien', $article->a_description);
        $this->assertSame($article->a_description, strip_tags($article->a_description));
        $this->assertStringContainsString('<h2>Mo dau hanh trinh</h2>', $article->a_content);
    }

    public function test_published_article_requires_content_but_draft_can_be_empty(): void
    {
        $publishedValidator = Validator::make([
            'a_title' => 'Bai viet xuat ban',
            'a_category_id' => 1,
            'a_active' => 1,
        ], (new ArticleRequest())->rules());

        $this->assertTrue($publishedValidator->fails());
        $this->assertArrayHasKey('a_content', $publishedValidator->errors()->toArray());

        $draftValidator = Validator::make([
            'a_title' => 'Bai viet ban nhap',
            'a_category_id' => 1,
            'a_active' => 2,
        ], (new ArticleRequest())->rules());

        $this->assertFalse($draftValidator->fails());
    }

    private function createCategory(): Category
    {
        return Category::create([
            'c_name' => 'Tin tuc',
            'c_slug' => 'tin-tuc',
            'c_status' => 1,
            'c_type' => 2,
        ]);
    }
}
