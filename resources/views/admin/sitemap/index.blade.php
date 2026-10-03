@extends('layouts.admin')

@section('title', 'Sitemap Manager')
@section('page_title', 'साइटमैप प्रबंधक (Sitemap Manager)')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
    <div>
        <p style="color: #64748b; font-size: 14px; margin: 0;">Google, Bing और अन्य सर्च इंजनों के लिए XML साइटमैप स्वचालित रूप से जनरेट और प्रबंधित करें।</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="{{ url('sitemap.xml') }}" target="_blank" class="btn btn-outline">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> View sitemap.xml
        </a>
        <form action="{{ route('admin.sitemap.generate') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-arrows-rotate"></i> अभी जनरेट करें (Regenerate Now)
            </button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- ── Stats Overview ── -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 24px;">
    <div class="card" style="padding: 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Total URLs</span>
                <h3 style="font-size: 26px; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ number_format($stats['total_urls']) }}</h3>
            </div>
            <div style="width: 48px; height: 48px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-link"></i>
            </div>
        </div>
        <span style="font-size: 12px; color: #16a34a; font-weight: 600; display: block; margin-top: 8px;">
            <i class="fa-solid fa-check-double"></i> Live in XML
        </span>
    </div>

    <div class="card" style="padding: 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Published Posts</span>
                <h3 style="font-size: 26px; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ number_format($stats['total_posts']) }}</h3>
            </div>
            <div style="width: 48px; height: 48px; border-radius: 10px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-newspaper"></i>
            </div>
        </div>
        <span style="font-size: 12px; color: #64748b; display: block; margin-top: 8px;">
            Priority: {{ $settings['priority_posts'] }} ({{ $settings['freq_posts'] }})
        </span>
    </div>

    <div class="card" style="padding: 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Categories</span>
                <h3 style="font-size: 26px; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ number_format($stats['total_categories']) }}</h3>
            </div>
            <div style="width: 48px; height: 48px; border-radius: 10px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>
        <span style="font-size: 12px; color: #64748b; display: block; margin-top: 8px;">
            Priority: {{ $settings['priority_categories'] }} ({{ $settings['freq_categories'] }})
        </span>
    </div>

    <div class="card" style="padding: 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Static File Status</span>
                <h4 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-top: 6px;">{{ $stats['file_size_formatted'] }}</h4>
            </div>
            <div style="width: 48px; height: 48px; border-radius: 10px; background: #fee2e2; color: #e60000; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-file-code"></i>
            </div>
        </div>
        <span style="font-size: 11.5px; color: #64748b; display: block; margin-top: 8px;">
            Updated: {{ $stats['file_last_modified'] }}
        </span>
    </div>
</div>

<!-- ── Layout Grid ── -->
<div style="display: grid; grid-template-columns: 380px 1fr; gap: 24px; align-items: start;">

    <!-- Left: Settings Form -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><i class="fa-solid fa-sliders"></i> साइटमैप सेटिंग्स (Settings)</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sitemap.updateSettings') }}" method="POST">
                @csrf

                <div style="margin-bottom: 20px;">
                    <span class="form-label" style="margin-bottom: 12px;">शामिल करें (Include In Sitemap)</span>

                    <label style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px; cursor: pointer; font-size: 13.5px; color: #334155;">
                        <input type="checkbox" name="sitemap_include_posts" value="1" {{ $settings['include_posts'] ? 'checked' : '' }} style="width: 17px; height: 17px; accent-color: var(--primary);">
                        <span><strong>समाचार पोस्ट (Published Posts)</strong></span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px; cursor: pointer; font-size: 13.5px; color: #334155;">
                        <input type="checkbox" name="sitemap_include_categories" value="1" {{ $settings['include_categories'] ? 'checked' : '' }} style="width: 17px; height: 17px; accent-color: var(--primary);">
                        <span><strong>कैटगरी पेजेस (Categories)</strong></span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px; cursor: pointer; font-size: 13.5px; color: #334155;">
                        <input type="checkbox" name="sitemap_include_videos" value="1" {{ $settings['include_videos'] ? 'checked' : '' }} style="width: 17px; height: 17px; accent-color: var(--primary);">
                        <span><strong>वीडियो न्यूज़ पेज (Video News)</strong></span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px; cursor: pointer; font-size: 13.5px; color: #334155;">
                        <input type="checkbox" name="sitemap_include_images" value="1" {{ $settings['include_images'] ? 'checked' : '' }} style="width: 17px; height: 17px; accent-color: var(--primary);">
                        <span><strong>इमेज टैग्स (Google Image Sitemap)</strong></span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label" for="post_limit">अधिकतम पोस्ट सीमा (Max Posts Limit)</label>
                    <input type="number" id="post_limit" name="sitemap_post_limit" class="form-control" value="{{ $settings['post_limit'] }}" min="10" max="10000" required>
                    <small style="color: #64748b; font-size: 11.5px; margin-top: 3px; display: block;">सर्च इंजन क्रॉलर के लिए अधिकतम हालिया पोस्ट संख्या।</small>
                </div>

                <hr style="border: none; border-top: 1px solid var(--border-color); margin: 20px 0;">

                <div style="font-weight: 700; font-size: 13px; color: #0f172a; margin-bottom: 14px; text-transform: uppercase; letter-spacing: 0.5px;">
                    क्रॉल फ़्रीक्वेंसी व प्राथमिकता
                </div>

                <!-- Homepage -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                    <div>
                        <label class="form-label" style="font-size: 12px;">होमपेज फ़्रीक्वेंसी</label>
                        <select name="sitemap_freq_home" class="form-select" style="padding: 7px 10px; font-size: 13px;">
                            <option value="always" {{ $settings['freq_home'] === 'always' ? 'selected' : '' }}>Always</option>
                            <option value="hourly" {{ $settings['freq_home'] === 'hourly' ? 'selected' : '' }}>Hourly</option>
                            <option value="daily" {{ $settings['freq_home'] === 'daily' ? 'selected' : '' }}>Daily</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 12px;">होमपेज प्राथमिकता</label>
                        <select name="sitemap_priority_home" class="form-select" style="padding: 7px 10px; font-size: 13px;">
                            <option value="1.0" {{ $settings['priority_home'] === '1.0' ? 'selected' : '' }}>1.0 (Highest)</option>
                            <option value="0.9" {{ $settings['priority_home'] === '0.9' ? 'selected' : '' }}>0.9</option>
                            <option value="0.8" {{ $settings['priority_home'] === '0.8' ? 'selected' : '' }}>0.8</option>
                        </select>
                    </div>
                </div>

                <!-- Posts -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                    <div>
                        <label class="form-label" style="font-size: 12px;">पोस्ट्स फ़्रीक्वेंसी</label>
                        <select name="sitemap_freq_posts" class="form-select" style="padding: 7px 10px; font-size: 13px;">
                            <option value="hourly" {{ $settings['freq_posts'] === 'hourly' ? 'selected' : '' }}>Hourly</option>
                            <option value="daily" {{ $settings['freq_posts'] === 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ $settings['freq_posts'] === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 12px;">पोस्ट्स प्राथमिकता</label>
                        <select name="sitemap_priority_posts" class="form-select" style="padding: 7px 10px; font-size: 13px;">
                            <option value="0.9" {{ $settings['priority_posts'] === '0.9' ? 'selected' : '' }}>0.9</option>
                            <option value="0.8" {{ $settings['priority_posts'] === '0.8' ? 'selected' : '' }}>0.8</option>
                            <option value="0.7" {{ $settings['priority_posts'] === '0.7' ? 'selected' : '' }}>0.7</option>
                            <option value="0.6" {{ $settings['priority_posts'] === '0.6' ? 'selected' : '' }}>0.6</option>
                        </select>
                    </div>
                </div>

                <!-- Categories -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                    <div>
                        <label class="form-label" style="font-size: 12px;">कैटगरी फ़्रीक्वेंसी</label>
                        <select name="sitemap_freq_categories" class="form-select" style="padding: 7px 10px; font-size: 13px;">
                            <option value="daily" {{ $settings['freq_categories'] === 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ $settings['freq_categories'] === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 12px;">कैटगरी प्राथमिकता</label>
                        <select name="sitemap_priority_categories" class="form-select" style="padding: 7px 10px; font-size: 13px;">
                            <option value="0.8" {{ $settings['priority_categories'] === '0.8' ? 'selected' : '' }}>0.8</option>
                            <option value="0.7" {{ $settings['priority_categories'] === '0.7' ? 'selected' : '' }}>0.7</option>
                            <option value="0.6" {{ $settings['priority_categories'] === '0.6' ? 'selected' : '' }}>0.6</option>
                        </select>
                    </div>
                </div>

                <!-- Videos -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;">
                    <div>
                        <label class="form-label" style="font-size: 12px;">वीडियोज़ फ़्रीक्वेंसी</label>
                        <select name="sitemap_freq_videos" class="form-select" style="padding: 7px 10px; font-size: 13px;">
                            <option value="daily" {{ $settings['freq_videos'] === 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ $settings['freq_videos'] === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 12px;">वीडियोज़ प्राथमिकता</label>
                        <select name="sitemap_priority_videos" class="form-select" style="padding: 7px 10px; font-size: 13px;">
                            <option value="0.7" {{ $settings['priority_videos'] === '0.7' ? 'selected' : '' }}>0.7</option>
                            <option value="0.6" {{ $settings['priority_videos'] === '0.6' ? 'selected' : '' }}>0.6</option>
                            <option value="0.5" {{ $settings['priority_videos'] === '0.5' ? 'selected' : '' }}>0.5</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    <i class="fa-solid fa-floppy-disk"></i> सेव करें और साइटमैप अपडेट करें
                </button>
            </form>
        </div>
    </div>

    <!-- Right: URLs Preview Table -->
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h4 class="card-title"><i class="fa-solid fa-list-check"></i> साइटमैप लाइव यूआरएल प्रीव्यू (URLs Preview)</h4>
            <span style="font-size: 12px; color: #64748b;">Showing {{ count($urls) }} URLs</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 100px;">Type</th>
                            <th>Title & URL</th>
                            <th style="width: 80px;">Priority</th>
                            <th style="width: 90px;">Freq</th>
                            <th style="width: 130px; text-align: right;">Last Modified</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($urls as $item)
                            <tr>
                                <td style="color: #94a3b8; font-size: 12px;">{{ $loop->iteration }}</td>
                                <td>
                                    @if($item['type'] === 'Home')
                                        <span class="badge badge-info">Home</span>
                                    @elseif($item['type'] === 'Category')
                                        <span class="badge badge-warning">Category</span>
                                    @elseif($item['type'] === 'Article')
                                        <span class="badge badge-success">Article</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $item['type'] }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #0f172a; font-size: 13.5px; line-height: 1.35; margin-bottom: 2px;">
                                        {{ Str::limit($item['title'], 55) }}
                                    </div>
                                    <a href="{{ $item['loc'] }}" target="_blank" style="font-size: 11.5px; color: #2563eb; text-decoration: none; word-break: break-all;">
                                        {{ $item['loc'] }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 9px;"></i>
                                    </a>
                                </td>
                                <td>
                                    <span style="font-family: monospace; font-weight: 700; font-size: 12px; color: #0f172a;">
                                        {{ $item['priority'] }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-size: 12px; color: #64748b; text-transform: capitalize;">
                                        {{ $item['changefreq'] }}
                                    </span>
                                </td>
                                <td style="text-align: right; font-size: 11.5px; color: #64748b; white-space: nowrap;">
                                    {{ \Illuminate\Support\Carbon::parse($item['lastmod'])->format('d M Y, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: #64748b; padding: 40px;">
                                    No URLs found in sitemap.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
