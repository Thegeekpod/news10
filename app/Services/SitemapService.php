<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Video;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class SitemapService
{
    /**
     * Retrieve all sitemap configuration settings with sensible defaults.
     */
    public function getSettings(): array
    {
        return [
            'include_posts' => (bool) Setting::get('sitemap_include_posts', '1'),
            'include_categories' => (bool) Setting::get('sitemap_include_categories', '1'),
            'include_videos' => (bool) Setting::get('sitemap_include_videos', '1'),
            'include_images' => (bool) Setting::get('sitemap_include_images', '1'),
            'post_limit' => (int) Setting::get('sitemap_post_limit', '1000'),
            'freq_home' => Setting::get('sitemap_freq_home', 'hourly'),
            'freq_posts' => Setting::get('sitemap_freq_posts', 'daily'),
            'freq_categories' => Setting::get('sitemap_freq_categories', 'daily'),
            'freq_videos' => Setting::get('sitemap_freq_videos', 'daily'),
            'priority_home' => Setting::get('sitemap_priority_home', '1.0'),
            'priority_posts' => Setting::get('sitemap_priority_posts', '0.8'),
            'priority_categories' => Setting::get('sitemap_priority_categories', '0.7'),
            'priority_videos' => Setting::get('sitemap_priority_videos', '0.6'),
            'last_generated_at' => Setting::get('sitemap_last_generated_at', null),
        ];
    }

    /**
     * Compile structured list of sitemap items.
     */
    public function getSitemapData(): array
    {
        $settings = $this->getSettings();
        $urls = [];

        // 1. Homepage
        $latestPost = Post::where('status', 'published')->latest('updated_at')->first();
        $homeLastmod = $latestPost ? $latestPost->updated_at : Carbon::now();

        $urls[] = [
            'loc' => route('home'),
            'lastmod' => $homeLastmod->toAtomString(),
            'changefreq' => $settings['freq_home'],
            'priority' => $settings['priority_home'],
            'type' => 'Home',
            'title' => 'Home Page',
            'image' => asset('logo.webp'),
        ];

        // 2. Categories
        if ($settings['include_categories']) {
            $categories = Category::all();
            foreach ($categories as $category) {
                $categoryLatestPost = Post::where('category_id', $category->id)
                    ->where('status', 'published')
                    ->latest('updated_at')
                    ->first();

                $categoryLastmod = $categoryLatestPost ? $categoryLatestPost->updated_at : ($category->updated_at ?? Carbon::now());

                $urls[] = [
                    'loc' => route('category.show', $category->slug),
                    'lastmod' => $categoryLastmod->toAtomString(),
                    'changefreq' => $settings['freq_categories'],
                    'priority' => $settings['priority_categories'],
                    'type' => 'Category',
                    'title' => $category->name,
                    'image' => null,
                ];
            }
        }

        // 3. Videos
        if ($settings['include_videos'] && class_exists(Video::class)) {
            $latestVideo = Video::where('is_active', true)->latest('updated_at')->first();
            $videoLastmod = $latestVideo ? $latestVideo->updated_at : Carbon::now();

            $urls[] = [
                'loc' => route('videos.index'),
                'lastmod' => $videoLastmod->toAtomString(),
                'changefreq' => $settings['freq_videos'],
                'priority' => $settings['priority_videos'],
                'type' => 'Page',
                'title' => 'Video News',
                'image' => $latestVideo ? (str_starts_with($latestVideo->thumbnail, 'http') ? $latestVideo->thumbnail : asset('storage/'.$latestVideo->thumbnail)) : null,
            ];
        }

        // 4. Published Posts
        if ($settings['include_posts']) {
            $posts = Post::where('status', 'published')
                ->latest('updated_at')
                ->limit($settings['post_limit'])
                ->get();

            foreach ($posts as $post) {
                $postLastmod = $post->updated_at ?? $post->published_at ?? Carbon::now();
                $imageUrl = null;

                if ($settings['include_images']) {
                    if ($post->featured_image) {
                        $imageUrl = str_starts_with($post->featured_image, 'http')
                            ? $post->featured_image
                            : asset('storage/'.$post->featured_image);
                    }
                }

                $urls[] = [
                    'loc' => route('post.show', $post->slug),
                    'lastmod' => $postLastmod->toAtomString(),
                    'changefreq' => $settings['freq_posts'],
                    'priority' => $settings['priority_posts'],
                    'type' => 'Article',
                    'title' => $post->title,
                    'image' => $imageUrl,
                ];
            }
        }

        return $urls;
    }

    /**
     * Build the valid XML sitemap string.
     */
    public function buildXml(): string
    {
        $urls = $this->getSitemapData();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'."\n";
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n";

        foreach ($urls as $item) {
            $xml .= "    <url>\n";
            $xml .= '        <loc>'.htmlspecialchars($item['loc'], ENT_XML1, 'UTF-8')."</loc>\n";
            $xml .= '        <lastmod>'.htmlspecialchars($item['lastmod'], ENT_XML1, 'UTF-8')."</lastmod>\n";
            $xml .= '        <changefreq>'.htmlspecialchars($item['changefreq'], ENT_XML1, 'UTF-8')."</changefreq>\n";
            $xml .= '        <priority>'.htmlspecialchars($item['priority'], ENT_XML1, 'UTF-8')."</priority>\n";

            if (! empty($item['image'])) {
                $xml .= "        <image:image>\n";
                $xml .= '            <image:loc>'.htmlspecialchars($item['image'], ENT_XML1, 'UTF-8')."</image:loc>\n";
                $xml .= '            <image:title>'.htmlspecialchars($item['title'] ?? '', ENT_XML1, 'UTF-8')."</image:title>\n";
                $xml .= "        </image:image>\n";
            }

            $xml .= "    </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Generate and save static sitemap.xml into the public folder.
     */
    public function generateFile(): array
    {
        $xml = $this->buildXml();
        $filePath = public_path('sitemap.xml');

        File::put($filePath, $xml);
        Setting::set('sitemap_last_generated_at', Carbon::now()->toDateTimeString());

        $this->ensureRobotsEntry();

        return [
            'success' => true,
            'file_path' => $filePath,
            'file_size' => filesize($filePath),
            'url_count' => substr_count($xml, '<url>'),
            'generated_at' => Carbon::now()->toDateTimeString(),
        ];
    }

    /**
     * Ensure robots.txt references the sitemap.
     */
    public function ensureRobotsEntry(): void
    {
        $robotsPath = public_path('robots.txt');
        $sitemapUrl = route('sitemap');

        if (File::exists($robotsPath)) {
            $content = File::get($robotsPath);
            if (! str_contains($content, 'sitemap.xml')) {
                $content = trim($content)."\n\nSitemap: ".$sitemapUrl."\n";
                File::put($robotsPath, $content);
            }
        } else {
            $content = "User-agent: *\nDisallow:\n\nSitemap: ".$sitemapUrl."\n";
            File::put($robotsPath, $content);
        }
    }
}
