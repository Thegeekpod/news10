<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Featured / Hero Top Stories
        $featuredPosts = Post::published()
            ->with(['category', 'author'])
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        // If less than 5 featured posts, grab latest
        if ($featuredPosts->count() < 5) {
            $fallback = Post::published()
                ->with(['category', 'author'])
                ->whereNotIn('id', $featuredPosts->pluck('id'))
                ->latest('published_at')
                ->take(5 - $featuredPosts->count())
                ->get();
            $featuredPosts = $featuredPosts->concat($fallback);
        }

        $mainHero = $featuredPosts->first();
        $sideHeroes = $featuredPosts->slice(1, 4);

        // 2. Trending / Most Read News (Ordered by views count)
        $trendingPosts = Post::published()
            ->with('category')
            ->orderByDesc('views_count')
            ->take(6)
            ->get();

        // 3. Category News Blocks
        $categorySections = Category::where('is_active', true)
            ->where('is_featured', true)
            ->with(['posts' => function ($query) {
                $query->published()
                    ->latest('published_at')
                    ->take(5);
            }])
            ->orderBy('order', 'asc')
            ->get();

        // 4. Editor's Picks
        $editorPicks = Post::published()
            ->with('category')
            ->where('is_editor_pick', true)
            ->latest('published_at')
            ->take(4)
            ->get();

        // 5. Advertisements for Home Page
        $homeMiddleAd = Ad::active()->where('placement', 'home_middle')->inRandomOrder()->first();
        $sidebarTopAd = Ad::active()->where('placement', 'sidebar_top')->inRandomOrder()->first();
        $sidebarBottomAd = Ad::active()->where('placement', 'sidebar_bottom')->inRandomOrder()->first();

        return view('frontend.index', compact(
            'mainHero',
            'sideHeroes',
            'trendingPosts',
            'categorySections',
            'editorPicks',
            'homeMiddleAd',
            'sidebarTopAd',
            'sidebarBottomAd'
        ));
    }
}
