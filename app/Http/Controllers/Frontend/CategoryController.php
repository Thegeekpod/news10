<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $posts = Post::published()
            ->with(['category', 'author'])
            ->where('category_id', $category->id)
            ->latest('published_at')
            ->paginate(12);

        $trendingPosts = Post::published()
            ->with('category')
            ->orderByDesc('views_count')
            ->take(6)
            ->get();

        $sidebarAd = Ad::active()
            ->where(function ($q) {
                $q->where('placement', 'category_sidebar')
                  ->orWhere('placement', 'sidebar_top');
            })
            ->inRandomOrder()
            ->first();

        return view('frontend.category', compact(
            'category',
            'posts',
            'trendingPosts',
            'sidebarAd'
        ));
    }
}
