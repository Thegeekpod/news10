<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('भारत समाचार');
    }

    public function test_category_page_loads_successfully(): void
    {
        $category = Category::first();
        $this->assertNotNull($category);
        $response = $this->get('/category/' . $category->slug);
        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    public function test_detail_page_loads_successfully(): void
    {
        $post = Post::first();
        $this->assertNotNull($post);
        $response = $this->get('/news/' . $post->slug);
        $response->assertStatus(200);
        $response->assertSee($post->title);
    }

    public function test_search_page_loads_successfully(): void
    {
        $response = $this->get('/search?q=मोदी');
        $response->assertStatus(200);
    }

    public function test_admin_login_page_loads(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('एडमिन');
    }

    public function test_admin_dashboard_accessible_when_authenticated(): void
    {
        $admin = User::where('is_admin', true)->first();
        $this->assertNotNull($admin);
        $this->actingAs($admin);

        $response = $this->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('डैशबोर्ड');
    }

    public function test_admin_can_create_post(): void
    {
        Storage::fake('public');
        $admin = User::where('is_admin', true)->first();
        $category = Category::first();

        $this->actingAs($admin);

        $file = UploadedFile::fake()->image('news_banner.jpg');

        $response = $this->post('/admin/posts', [
            'title' => 'नया परीक्षण समाचार शीर्षक (Test News Title)',
            'category_id' => $category->id,
            'summary' => 'यह परीक्षण खबर का सारांश है।',
            'content' => '<p>परीक्षण खबर की विस्तृत सामग्री।</p>',
            'status' => 'published',
            'is_breaking' => 1,
            'is_featured' => 1,
            'featured_image' => $file,
            'tags' => ['परीक्षण टैग', 'नई खबर'],
        ]);

        $response->assertRedirect('/admin/posts');
        $this->assertDatabaseHas('posts', [
            'title' => 'नया परीक्षण समाचार शीर्षक (Test News Title)',
            'is_breaking' => 1,
        ]);
    }

    public function test_admin_can_create_category(): void
    {
        $admin = User::where('is_admin', true)->first();
        $this->actingAs($admin);

        $response = $this->post('/admin/categories', [
            'name' => 'ऑटोमोबाइल',
            'slug' => 'automobile',
            'color' => '#10b981',
            'order' => 10,
            'description' => 'गाड़ियों और ऑटोमोबाइल जगत के समाचार',
        ]);

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', [
            'slug' => 'automobile',
            'name' => 'ऑटोमोबाइल',
        ]);
    }

    public function test_admin_can_create_ad(): void
    {
        $admin = User::where('is_admin', true)->first();
        $this->actingAs($admin);

        $response = $this->post('/admin/ads', [
            'title' => 'नया साइडबार प्रायोजित विज्ञापन',
            'placement' => 'sidebar_top',
            'type' => 'image',
            'image_url' => 'https://picsum.photos/300/250',
            'target_url' => 'https://advertiser.com',
            'is_active' => 1,
        ]);

        $response->assertRedirect('/admin/ads');
        $this->assertDatabaseHas('ads', [
            'title' => 'नया साइडबार प्रायोजित विज्ञापन',
            'placement' => 'sidebar_top',
        ]);
    }

    public function test_newsletter_subscription(): void
    {
        $response = $this->post('/newsletter/subscribe', [
            'email' => 'testreader@example.com',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('subscribers', [
            'email' => 'testreader@example.com',
        ]);
    }
}
