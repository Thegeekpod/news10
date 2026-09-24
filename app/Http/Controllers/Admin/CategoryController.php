<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')
            ->orderBy('order')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:categories,slug',
            'color' => 'nullable|string|max:20',
            'order' => 'nullable|integer',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'], '-', 'hi')
            : (Str::slug($validated['name'], '-', 'hi') ?: 'category-' . time());

        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'color' => $validated['color'] ?? '#e63946',
            'order' => $validated['order'] ?? 0,
            'description' => $validated['description'] ?? null,
            'is_featured' => $request->boolean('is_featured', true),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'कैटेगरी सफलतापूर्वक जोड़ी गई!');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:categories,slug,' . $category->id,
            'color' => 'nullable|string|max:20',
            'order' => 'nullable|integer',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => !empty($validated['slug']) ? Str::slug($validated['slug'], '-', 'hi') : $category->slug,
            'color' => $validated['color'] ?? $category->color,
            'order' => $validated['order'] ?? 0,
            'description' => $validated['description'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'कैटेगरी सफलतापूर्वक अपडेट कर दी गई!');
    }

    public function destroy(Category $category)
    {
        if ($category->posts()->count() > 0) {
            return back()->with('error', 'इस कैटेगरी में पोस्ट मौजूद हैं। पहले उन पोस्ट्स को दूसरी कैटेगरी में ट्रांसफर करें।');
        }

        $category->delete();
        return back()->with('success', 'कैटेगरी सफलतापूर्वक हटा दी गई।');
    }
}
