<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $status = $request->input('status');

        $query = Post::with(['category', 'author'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('summary', 'LIKE', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $posts = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.posts.index', compact('posts', 'categories', 'search', 'categoryId', 'status'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'slug' => 'nullable|string|max:500|unique:posts,slug',
            'category_id' => 'required|exists:categories,id',
            'summary' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'image_url' => 'nullable|url',
            'image_caption' => 'nullable|string|max:255',
            'status' => 'required|in:published,draft',
            'is_breaking' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'is_editor_pick' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|string',
        ]);

        $slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'], '-', 'hi')
            : (Str::slug($validated['title'], '-', 'hi') ?: 'post-' . time());

        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('posts', 'public');
        } elseif (!empty($request->input('image_url'))) {
            $imagePath = $request->input('image_url');
        }

        $post = Post::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category_id' => $validated['category_id'],
            'user_id' => Auth::id(),
            'summary' => $validated['summary'] ?? Str::limit(strip_tags($validated['content']), 160),
            'content' => $validated['content'],
            'featured_image' => $imagePath,
            'image_caption' => $validated['image_caption'] ?? null,
            'status' => $validated['status'],
            'is_breaking' => $request->boolean('is_breaking'),
            'is_featured' => $request->boolean('is_featured'),
            'is_trending' => $request->boolean('is_trending'),
            'is_editor_pick' => $request->boolean('is_editor_pick'),
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? $validated['summary'],
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        // Sync or Create Tags
        if (!empty($request->tags)) {
            $tagIds = [];
            foreach ($request->tags as $tagInput) {
                if (is_numeric($tagInput)) {
                    $tagIds[] = (int) $tagInput;
                } else {
                    $tag = Tag::firstOrCreate(
                        ['slug' => Str::slug($tagInput, '-', 'hi') ?: Str::random(8)],
                        ['name' => trim($tagInput)]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('admin.posts.index')->with('success', 'खबर सफलतापूर्वक प्रकाशित / सहेजी गई!');
    }

    public function edit(Post $post)
    {
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $tags = Tag::orderBy('name')->get();
        $selectedTags = $post->tags->pluck('id')->toArray();

        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'selectedTags'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'slug' => 'nullable|string|max:500|unique:posts,slug,' . $post->id,
            'category_id' => 'required|exists:categories,id',
            'summary' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'image_url' => 'nullable|url',
            'image_caption' => 'nullable|string|max:255',
            'status' => 'required|in:published,draft',
            'is_breaking' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'is_editor_pick' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
        ]);

        $imagePath = $post->featured_image;
        if ($request->hasFile('featured_image')) {
            if ($post->featured_image && !str_starts_with($post->featured_image, 'http')) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $imagePath = $request->file('featured_image')->store('posts', 'public');
        } elseif (!empty($request->input('image_url'))) {
            $imagePath = $request->input('image_url');
        }

        $post->update([
            'title' => $validated['title'],
            'slug' => !empty($validated['slug']) ? Str::slug($validated['slug'], '-', 'hi') : $post->slug,
            'category_id' => $validated['category_id'],
            'summary' => $validated['summary'] ?? Str::limit(strip_tags($validated['content']), 160),
            'content' => $validated['content'],
            'featured_image' => $imagePath,
            'image_caption' => $validated['image_caption'] ?? null,
            'status' => $validated['status'],
            'is_breaking' => $request->boolean('is_breaking'),
            'is_featured' => $request->boolean('is_featured'),
            'is_trending' => $request->boolean('is_trending'),
            'is_editor_pick' => $request->boolean('is_editor_pick'),
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? $validated['summary'],
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'published_at' => ($validated['status'] === 'published' && !$post->published_at) ? now() : $post->published_at,
        ]);

        // Sync Tags
        if (isset($request->tags)) {
            $tagIds = [];
            foreach ($request->tags as $tagInput) {
                if (is_numeric($tagInput)) {
                    $tagIds[] = (int) $tagInput;
                } else {
                    $tag = Tag::firstOrCreate(
                        ['slug' => Str::slug($tagInput, '-', 'hi') ?: Str::random(8)],
                        ['name' => trim($tagInput)]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            $post->tags()->sync($tagIds);
        } else {
            $post->tags()->sync([]);
        }

        return redirect()->route('admin.posts.index')->with('success', 'खबर सफलतापूर्वक अपडेट कर दी गई!');
    }

    public function destroy(Post $post)
    {
        if ($post->featured_image && !str_starts_with($post->featured_image, 'http')) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->tags()->detach();
        $post->delete();

        return back()->with('success', 'खबर सफलतापूर्वक हटा दी गई।');
    }

    public function toggleStatus(Post $post)
    {
        $post->status = $post->status === 'published' ? 'draft' : 'published';
        if ($post->status === 'published' && !$post->published_at) {
            $post->published_at = now();
        }
        $post->save();

        return back()->with('success', 'खबर की स्थिति अपडेट कर दी गई।');
    }

    public function toggleBreaking(Post $post)
    {
        $post->is_breaking = !$post->is_breaking;
        $post->save();

        return back()->with('success', 'ब्रेकिंग न्यूज़ स्टेटस अपडेट किया गया।');
    }
}
