<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::latest()->paginate(15);
        return view('admin.ads.index', compact('ads'));
    }

    public function create()
    {
        $placements = [
            'header_banner' => 'हेडर बैनर (Header Top 728x90)',
            'home_middle' => 'होमपेज मिडिल बैनर (Home Middle 970x120)',
            'sidebar_top' => 'साइडबार टॉप (Sidebar Top 300x250)',
            'sidebar_bottom' => 'साइडबार बॉटम (Sidebar Bottom 300x400)',
            'detail_top' => 'आर्टिकल डिटेल टॉप (Article Inside 728x120)',
            'detail_sidebar' => 'डिटेल साइडबार (Article Sidebar 300x250)',
            'detail_bottom' => 'डिटेल बॉटम (Article Bottom 728x90)',
            'category_sidebar' => 'कैटेगरी साइडबार (Category Sidebar 300x250)',
        ];

        return view('admin.ads.create', compact('placements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'placement' => 'required|string',
            'type' => 'required|in:image,code',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'image_url' => 'nullable|url',
            'target_url' => 'nullable|url',
            'custom_code' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'expiry_date' => 'nullable|date',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ads', 'public');
        } elseif (!empty($request->input('image_url'))) {
            $imagePath = $request->input('image_url');
        }

        Ad::create([
            'title' => $validated['title'],
            'placement' => $validated['placement'],
            'type' => $validated['type'],
            'image_path' => $imagePath,
            'target_url' => $validated['target_url'] ?? null,
            'custom_code' => $validated['custom_code'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'expiry_date' => $validated['expiry_date'] ?? null,
        ]);

        return redirect()->route('admin.ads.index')->with('success', 'विज्ञापन सफलतापूर्वक जोड़ा गया!');
    }

    public function edit(Ad $ad)
    {
        $placements = [
            'header_banner' => 'हेडर बैनर (Header Top 728x90)',
            'home_middle' => 'होमपेज मिडिल बैनर (Home Middle 970x120)',
            'sidebar_top' => 'साइडबार टॉप (Sidebar Top 300x250)',
            'sidebar_bottom' => 'साइडबार बॉटम (Sidebar Bottom 300x400)',
            'detail_top' => 'आर्टिकल डिटेल टॉप (Article Inside 728x120)',
            'detail_sidebar' => 'डिटेल साइडबार (Article Sidebar 300x250)',
            'detail_bottom' => 'डिटेल बॉटम (Article Bottom 728x90)',
            'category_sidebar' => 'कैटेगरी साइडबार (Category Sidebar 300x250)',
        ];

        return view('admin.ads.edit', compact('ad', 'placements'));
    }

    public function update(Request $request, Ad $ad)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'placement' => 'required|string',
            'type' => 'required|in:image,code',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'image_url' => 'nullable|url',
            'target_url' => 'nullable|url',
            'custom_code' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'expiry_date' => 'nullable|date',
        ]);

        $imagePath = $ad->image_path;
        if ($request->hasFile('image')) {
            if ($ad->image_path && !str_starts_with($ad->image_path, 'http')) {
                Storage::disk('public')->delete($ad->image_path);
            }
            $imagePath = $request->file('image')->store('ads', 'public');
        } elseif (!empty($request->input('image_url'))) {
            $imagePath = $request->input('image_url');
        }

        $ad->update([
            'title' => $validated['title'],
            'placement' => $validated['placement'],
            'type' => $validated['type'],
            'image_path' => $imagePath,
            'target_url' => $validated['target_url'] ?? null,
            'custom_code' => $validated['custom_code'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'expiry_date' => $validated['expiry_date'] ?? null,
        ]);

        return redirect()->route('admin.ads.index')->with('success', 'विज्ञापन सफलतापूर्वक अपडेट किया गया!');
    }

    public function toggleStatus(Ad $ad)
    {
        $ad->is_active = !$ad->is_active;
        $ad->save();

        return back()->with('success', 'विज्ञापन की स्थिति अपडेट कर दी गई।');
    }

    public function destroy(Ad $ad)
    {
        if ($ad->image_path && !str_starts_with($ad->image_path, 'http')) {
            Storage::disk('public')->delete($ad->image_path);
        }

        $ad->delete();
        return back()->with('success', 'विज्ञापन सफलतापूर्वक हटा दिया गया।');
    }
}
