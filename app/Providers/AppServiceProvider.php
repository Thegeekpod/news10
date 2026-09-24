<?php

namespace App\Providers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tag;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        if (Schema::hasTable('categories') && Schema::hasTable('settings')) {
            View::composer(['frontend.*', 'layouts.frontend'], function ($view) {
                $categories = Category::where('is_active', true)
                    ->orderBy('order', 'asc')
                    ->get();

                $settings = Setting::pluck('value', 'key')->toArray();

                $breakingPosts = Post::published()
                    ->where('is_breaking', true)
                    ->latest('published_at')
                    ->take(10)
                    ->get();

                if ($breakingPosts->isEmpty()) {
                    $breakingPosts = Post::published()
                        ->latest('published_at')
                        ->take(8)
                        ->get();
                }

                $headerAd = Ad::active()
                    ->where('placement', 'header_banner')
                    ->inRandomOrder()
                    ->first();

                $popularTags = Tag::withCount('posts')
                    ->orderByDesc('posts_count')
                    ->take(12)
                    ->get();

                $sidebarTrendingPosts = Post::published()
                    ->with('category')
                    ->orderByDesc('views_count')
                    ->take(5)
                    ->get();

                $sidebarEditorPicks = Post::published()
                    ->where('is_editor_pick', true)
                    ->latest('published_at')
                    ->take(5)
                    ->get();

                if ($sidebarEditorPicks->count() < 5) {
                    $fallback = Post::published()
                        ->whereNotIn('id', $sidebarEditorPicks->pluck('id'))
                        ->latest('published_at')
                        ->take(5 - $sidebarEditorPicks->count())
                        ->get();
                    $sidebarEditorPicks = $sidebarEditorPicks->concat($fallback);
                }

                $sidebarTopAd = Ad::active()
                    ->where('placement', 'sidebar_top')
                    ->inRandomOrder()
                    ->first();

                $sidebarBottomAd = Ad::active()
                    ->where('placement', 'sidebar_bottom')
                    ->inRandomOrder()
                    ->first();

                $breakingTickers = \App\Models\BreakingTicker::where('is_active', true)->latest()->get();

                $view->with([
                    'globalCategories' => $categories,
                    'globalSettings' => $settings,
                    'globalBreakingPosts' => $breakingPosts,
                    'globalBreakingTickers' => $breakingTickers,
                    'headerAd' => $headerAd,
                    'globalPopularTags' => $popularTags,
                    'sidebarTrendingPosts' => $sidebarTrendingPosts,
                    'sidebarEditorPicks' => $sidebarEditorPicks,
                    'sidebarTopAd' => $sidebarTopAd,
                    'sidebarBottomAd' => $sidebarBottomAd,
                ]);
            });
        }
    }
}
