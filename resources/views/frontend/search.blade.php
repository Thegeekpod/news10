@extends('layouts.frontend')

@section('title', ($tagModel ? ('#' . $tagModel->name) : ('" ' . $query . ' " के खोज परिणाम')) . ' — ' . ($globalSettings['site_name'] ?? 'News 10'))

@section('content')
<div class="breadcrumb-bar" style="background: #f1f5f9; padding: 10px 0; font-size: 13px;">
    <div class="container">
        <nav class="breadcrumb" style="display: flex; align-items: center; gap: 8px;">
            <a href="{{ route('home') }}" style="color: #64748b; text-decoration: none;"><i class="fa-solid fa-house"></i> होम</a>
            <span style="color: #94a3b8;"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i></span>
            <span style="color: #c1121f; font-weight: 700;">खोज परिणाम</span>
        </nav>
    </div>
</div>

<div class="container" style="margin-top: 25px; margin-bottom: 60px;">
    <div style="background: #ffffff; padding: 25px 30px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 5px solid #c1121f;">
        <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;">
            @if($tagModel)
                टैग: <span style="color: #c1121f;">#{{ $tagModel->name }}</span>
            @elseif($query)
                खोज परिणाम: <span style="color: #c1121f;">"{{ $query }}"</span>
            @else
                सभी ताज़ा खबरें
            @endif
        </h1>
        <p style="color: #64748b; margin: 0; font-size: 14px;">कुल {{ $posts->total() }} समाचार मिले</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 320px; gap: 30px;" class="search-layout">
        <div>
            @if($posts->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                    @foreach($posts as $post)
                        <a href="{{ route('post.show', $post->slug) }}" style="text-decoration: none; color: inherit; display: block;">
                            <article style="background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); height: 100%; display: flex; flex-direction: column;">
                                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" style="width: 100%; height: 170px; object-fit: cover;" />
                                <div style="padding: 15px; display: flex; flex-direction: column; justify-content: space-between; flex: 1;">
                                    <div>
                                        @if($post->category)
                                            <span style="background: {{ $post->category->color }}; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; display: inline-block; margin-bottom: 8px;">
                                                {{ $post->category->name }}
                                            </span>
                                        @endif
                                        <h3 style="font-size: 15.5px; font-weight: 700; line-height: 1.4; color: #0f172a; margin: 0 0 6px 0;">{{ $post->title }}</h3>
                                    </div>
                                    <div style="font-size: 11.5px; color: #94a3b8; display: flex; justify-content: space-between; border-top: 1px solid #f1f5f9; padding-top: 8px; margin-top: 10px;">
                                        <span><i class="fa-regular fa-clock"></i> {{ $post->published_at ? $post->published_at->diffForHumans() : $post->created_at->diffForHumans() }}</span>
                                        <span><i class="fa-regular fa-eye"></i> {{ number_format($post->views_count) }}</span>
                                    </div>
                                </div>
                            </article>
                        </a>
                    @endforeach
                </div>

                <div style="margin-top: 30px; display: flex; justify-content: center;">
                    {{ $posts->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 50px 20px; background: #fff; border-radius: 8px;">
                    <div style="font-size: 40px; margin-bottom: 10px;">🔍</div>
                    <h3 style="font-size: 18px; color: #0f172a;">कोई समाचार नहीं मिला</h3>
                    <p style="color: #64748b; font-size: 14px;">कृपया किसी अन्य कीवर्ड से खोजें।</p>
                </div>
            @endif
        </div>

        <!-- ── SIDEBAR ── -->
        @include('frontend.partials.sidebar')
    </div>
</div>

<style>
@media (max-width: 900px) {
    .search-layout {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
