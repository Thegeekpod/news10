<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category;
use App\Models\Post;
use App\Models\Subscriber;
use App\Models\Tag;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'breaking_posts' => Post::where('is_breaking', true)->where('status', 'published')->count(),
            'total_views' => Post::sum('views_count'),
            'total_categories' => Category::count(),
            'total_tags' => Tag::count(),
            'active_ads' => Ad::active()->count(),
            'subscribers_count' => Subscriber::where('is_active', true)->count(),
        ];

        $recentPosts = Post::with(['category', 'author'])
            ->latest()
            ->take(6)
            ->get();

        $topPosts = Post::with('category')
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        $categoryDistribution = Category::withCount('posts')
            ->orderByDesc('posts_count')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPosts', 'topPosts', 'categoryDistribution'));
    }
}
