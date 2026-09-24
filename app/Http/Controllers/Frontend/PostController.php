<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show(string $slug)
    {
        $post = Post::published()
            ->with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views count
        $post->increment('views_count');

        // Related posts in same category
        $relatedPosts = Post::published()
            ->with('category')
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(4)
            ->get();

        // Trending / Most read posts
        $trendingPosts = Post::published()
            ->with('category')
            ->where('id', '!=', $post->id)
            ->orderByDesc('views_count')
            ->take(6)
            ->get();

        // Advertisements for article view
        $inArticleAd = Ad::active()->where('placement', 'detail_top')->inRandomOrder()->first();
        $sidebarAd = Ad::active()->where('placement', 'detail_sidebar')->orWhere('placement', 'sidebar_top')->inRandomOrder()->first();
        $bottomAd = Ad::active()->where('placement', 'detail_bottom')->orWhere('placement', 'sidebar_bottom')->inRandomOrder()->first();

        return view('frontend.detail', compact(
            'post',
            'relatedPosts',
            'trendingPosts',
            'inArticleAd',
            'sidebarAd',
            'bottomAd'
        ));
    }
}
