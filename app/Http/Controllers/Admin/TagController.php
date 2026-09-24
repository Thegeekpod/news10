<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Tag::withCount('posts')->orderByDesc('posts_count');

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $tags = $query->paginate(20)->withQueryString();

        return view('admin.tags.index', compact('tags', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:tags,slug',
        ]);

        $slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'], '-', 'hi')
            : (Str::slug($validated['name'], '-', 'hi') ?: 'tag-' . time());

        Tag::create([
            'name' => $validated['name'],
            'slug' => $slug,
        ]);

        return redirect()->route('admin.tags.index')->with('success', 'टैग सफलतापूर्वक जोड़ा गया!');
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:tags,slug,' . $tag->id,
        ]);

        $tag->update([
            'name' => $validated['name'],
            'slug' => !empty($validated['slug']) ? Str::slug($validated['slug'], '-', 'hi') : $tag->slug,
        ]);

        return redirect()->route('admin.tags.index')->with('success', 'टैग सफलतापूर्वक अपडेट किया गया!');
    }

    public function destroy(Tag $tag)
    {
        $tag->posts()->detach();
        $tag->delete();

        return back()->with('success', 'टैग सफलतापूर्वक हटा दिया गया।');
    }
}
