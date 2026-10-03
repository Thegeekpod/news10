<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Video;
use App\Services\SitemapService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SitemapController extends Controller
{
    public function __construct(protected SitemapService $sitemapService) {}

    public function index(): View
    {
        $settings = $this->sitemapService->getSettings();
        $urls = $this->sitemapService->getSitemapData();

        $filePath = public_path('sitemap.xml');
        $fileExists = file_exists($filePath);
        $fileSize = $fileExists ? filesize($filePath) : 0;
        $fileLastModified = $fileExists ? filemtime($filePath) : null;

        $stats = [
            'total_urls' => count($urls),
            'total_posts' => Post::where('status', 'published')->count(),
            'total_categories' => Category::count(),
            'total_videos' => class_exists(Video::class) ? Video::where('is_active', true)->count() : 0,
            'file_exists' => $fileExists,
            'file_size_formatted' => $fileExists ? number_format($fileSize / 1024, 2).' KB' : 'Not generated yet',
            'file_last_modified' => $fileLastModified ? date('d M Y, h:i A', $fileLastModified) : 'N/A',
        ];

        return view('admin.sitemap.index', compact('settings', 'urls', 'stats'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sitemap_include_posts' => 'nullable|boolean',
            'sitemap_include_categories' => 'nullable|boolean',
            'sitemap_include_videos' => 'nullable|boolean',
            'sitemap_include_images' => 'nullable|boolean',
            'sitemap_post_limit' => 'required|integer|min:10|max:10000',
            'sitemap_freq_home' => 'required|string|in:always,hourly,daily,weekly',
            'sitemap_freq_posts' => 'required|string|in:always,hourly,daily,weekly,monthly',
            'sitemap_freq_categories' => 'required|string|in:hourly,daily,weekly,monthly',
            'sitemap_freq_videos' => 'required|string|in:hourly,daily,weekly,monthly',
            'sitemap_priority_home' => 'required|string',
            'sitemap_priority_posts' => 'required|string',
            'sitemap_priority_categories' => 'required|string',
            'sitemap_priority_videos' => 'required|string',
        ]);

        Setting::set('sitemap_include_posts', $request->has('sitemap_include_posts') ? '1' : '0');
        Setting::set('sitemap_include_categories', $request->has('sitemap_include_categories') ? '1' : '0');
        Setting::set('sitemap_include_videos', $request->has('sitemap_include_videos') ? '1' : '0');
        Setting::set('sitemap_include_images', $request->has('sitemap_include_images') ? '1' : '0');
        Setting::set('sitemap_post_limit', (string) $validated['sitemap_post_limit']);
        Setting::set('sitemap_freq_home', $validated['sitemap_freq_home']);
        Setting::set('sitemap_freq_posts', $validated['sitemap_freq_posts']);
        Setting::set('sitemap_freq_categories', $validated['sitemap_freq_categories']);
        Setting::set('sitemap_freq_videos', $validated['sitemap_freq_videos']);
        Setting::set('sitemap_priority_home', $validated['sitemap_priority_home']);
        Setting::set('sitemap_priority_posts', $validated['sitemap_priority_posts']);
        Setting::set('sitemap_priority_categories', $validated['sitemap_priority_categories']);
        Setting::set('sitemap_priority_videos', $validated['sitemap_priority_videos']);

        // Automatically regenerate sitemap file with new settings
        $result = $this->sitemapService->generateFile();

        return redirect()->route('admin.sitemap.index')
            ->with('success', "Sitemap settings saved and sitemap.xml automatically regenerated ({$result['url_count']} URLs).");
    }

    public function generate(): RedirectResponse
    {
        $result = $this->sitemapService->generateFile();

        return redirect()->route('admin.sitemap.index')
            ->with('success', "sitemap.xml generated successfully! Total {$result['url_count']} URLs included (".number_format($result['file_size'] / 1024, 2).' KB).');
    }
}
