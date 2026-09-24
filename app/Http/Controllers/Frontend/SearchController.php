<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));
        $tagSlug = $request->input('tag');

        $postsQuery = Post::published()->with(['category', 'author']);

        $tagModel = null;
        if ($tagSlug) {
            $tagModel = Tag::where('slug', $tagSlug)->first();
            if ($tagModel) {
                $postsQuery->whereHas('tags', function ($q) use ($tagModel) {
                    $q->where('tags.id', $tagModel->id);
                });
            }
        } elseif ($query) {
            $postsQuery->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('summary', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            });
        }

        $posts = $postsQuery->latest('published_at')->paginate(12)->withQueryString();

        $trendingPosts = Post::published()
            ->with('category')
            ->orderByDesc('views_count')
            ->take(6)
            ->get();

        $sidebarAd = Ad::active()->where('placement', 'sidebar_top')->inRandomOrder()->first();

        return view('frontend.search', compact('posts', 'query', 'tagModel', 'trendingPosts', 'sidebarAd'));
    }
}
