@extends('layouts.admin')

@section('title', 'डैशबोर्ड')
@section('page_title', 'एडमिन डैशबोर्ड (Dashboard Overview)')

@push('styles')
<style>
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 22px;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }

    .stat-number {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        margin-top: 4px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .icon-red { background: #fee2e2; color: #dc2626; }
    .icon-blue { background: #dbeafe; color: #2563eb; }
    .icon-green { background: #dcfce7; color: #16a34a; }
    .icon-purple { background: #f3e8ff; color: #9333ea; }
    .icon-orange { background: #ffedd5; color: #ea580c; }
    .icon-cyan { background: #cffafe; color: #0891b2; }
</style>
@endpush

@section('content')

<!-- ── Quick Action Bar ── -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h3 style="font-size: 20px; font-weight: 800; color: #0f172a;">नमस्ते, {{ Auth::user()->name }}! 👋</h3>
        <p style="color: #64748b; font-size: 13.5px;">पोर्टल का ताज़ा आँकड़ा और प्रमुख अपडेट्स नीचे देखें।</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-feather-pointed"></i> नई खबर लिखें (Post News)
        </a>
        <a href="{{ route('admin.ads.create') }}" class="btn btn-outline">
            <i class="fa-solid fa-rectangle-ad"></i> नया विज्ञापन (New Ad)
        </a>
    </div>
</div>

<!-- ── Stats KPI Cards ── -->
<div class="stat-grid">
    <div class="stat-card">
        <div>
            <div class="stat-number">{{ number_format($stats['total_posts']) }}</div>
            <div class="stat-label">कुल समाचार (Total Posts)</div>
        </div>
        <div class="stat-icon icon-red"><i class="fa-solid fa-newspaper"></i></div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-number">{{ number_format($stats['total_views']) }}</div>
            <div class="stat-label">कुल पाठक (Total Views)</div>
        </div>
        <div class="stat-icon icon-blue"><i class="fa-solid fa-eye"></i></div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-number">{{ $stats['total_categories'] }}</div>
            <div class="stat-label">श्रेणियां (Categories)</div>
        </div>
        <div class="stat-icon icon-purple"><i class="fa-solid fa-layer-group"></i></div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-number">{{ $stats['active_ads'] }}</div>
            <div class="stat-label">सक्रिय विज्ञापन (Active Ads)</div>
        </div>
        <div class="stat-icon icon-orange"><i class="fa-solid fa-rectangle-ad"></i></div>
    </div>
</div>

<!-- ── Main Dashboard Split ── -->
<div style="display: grid; grid-template-columns: 2fr 1.2fr; gap: 24px;" class="dash-grid">
    
    <!-- Left: Recent Posts Table -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><i class="fa-solid fa-clock-rotate-left"></i> हाल ही में प्रकाशित खबरें</h4>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline btn-sm">सभी देखें</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>थंबनेल</th>
                            <th>शीर्षक</th>
                            <th>कैटेगरी</th>
                            <th>व्यूज</th>
                            <th>एक्शन</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPosts as $rPost)
                            <tr>
                                <td style="width: 60px;">
                                    <img src="{{ $rPost->image_url }}" alt="" style="width: 50px; height: 38px; object-fit: cover; border-radius: 4px;" />
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #0f172a; max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $rPost->title }}
                                    </div>
                                    <div style="font-size: 11.5px; color: #94a3b8;">
                                        {{ $rPost->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td>
                                    @if($rPost->category)
                                        <span class="badge" style="background: {{ $rPost->category->color }}20; color: {{ $rPost->category->color }};">
                                            {{ $rPost->category->name }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-weight: 600; font-size: 13px;">{{ number_format($rPost->views_count) }}</span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <a href="{{ route('admin.posts.edit', $rPost->id) }}" class="btn btn-outline btn-sm" title="एडिट"><i class="fa-solid fa-pen"></i></a>
                                        <a href="{{ route('post.show', $rPost->slug) }}" target="_blank" class="btn btn-outline btn-sm" title="देखें"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: #64748b; padding: 25px;">कोई खबर उपलब्ध नहीं है।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right: Top Viewed & Categories breakdown -->
    <div>
        <!-- Top Viewed Widget -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">🔥 सर्वाधिक पढ़ी गई खबरें</h4>
            </div>
            <div class="card-body" style="padding: 14px 18px;">
                <div style="display: flex; flex-direction: column; gap: 14px;">
                    @foreach($topPosts as $tpIndex => $topP)
                        <div style="display: flex; gap: 12px; align-items: center;">
                            <span style="font-size: 18px; font-weight: 800; color: {{ $tpIndex < 3 ? '#c1121f' : '#94a3b8' }}; width: 18px;">
                                {{ $tpIndex + 1 }}
                            </span>
                            <div style="flex: 1;">
                                <a href="{{ route('post.show', $topP->slug) }}" target="_blank" style="text-decoration: none; color: #1e293b; font-size: 13.5px; font-weight: 600; display: block; line-height: 1.35;">
                                    {{ Str::limit($topP->title, 50) }}
                                </a>
                                <span style="font-size: 11.5px; color: #64748b;"><i class="fa-regular fa-eye"></i> {{ number_format($topP->views_count) }} views</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Categories Distribution -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><i class="fa-solid fa-chart-pie"></i> श्रेणी अनुसार खबरें</h4>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline btn-sm">प्रबंधित करें</a>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach($categoryDistribution as $catDist)
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13.5px;">
                            <span style="display: flex; align-items: center; gap: 8px;">
                                <span style="width: 10px; height: 10px; border-radius: 50%; background: {{ $catDist->color }};"></span>
                                {{ $catDist->name }}
                            </span>
                            <span style="font-weight: 700; color: #475569;">{{ $catDist->posts_count }} खबरें</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

</div>

<style>
@media (max-width: 900px) {
    .dash-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
