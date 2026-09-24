@extends('layouts.frontend')

@section('title', $category->name . ' — ताज़ा खबरें | ' . ($globalSettings['site_name'] ?? 'भारत समाचार'))
@section('meta_description', $category->description ?? ($category->name . ' की ताज़ा खबरें, ब्रेकिंग न्यूज़, विश्लेषण और लाइव अपडेट्स।'))

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/category.css') }}" />
@endpush

@section('content')
<!-- ════════ BREADCRUMB ════════ -->
<div class="breadcrumb-bar">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> होम</a>
      <span class="bc-sep"><i class="fa-solid fa-chevron-right"></i></span>
      <span class="bc-current">{{ $category->name }}</span>
    </nav>
  </div>
</div>

<!-- ════════ CATEGORY HERO BANNER ════════ -->
<div class="cat-hero-banner">
  <div class="cat-hero-bg">
    <img src="https://picsum.photos/1400/280?random=200" alt="{{ $category->name }} बैनर" />
    <div class="cat-hero-overlay"></div>
  </div>
  <div class="container cat-hero-content">
    <div class="cat-hero-icon">🏛️</div>
    <div>
      <h1 class="cat-hero-title">{{ $category->name }}</h1>
      <p class="cat-hero-sub">{{ $category->description ?? 'देश और दुनिया से जुड़ी ताज़ा खबरें, विश्लेषण और लाइव अपडेट्स' }}</p>
      <div class="cat-hero-stats">
        <span><i class="fa-solid fa-newspaper"></i> {{ $posts->total() }}+ लेख</span>
        <span><i class="fa-regular fa-clock"></i> आज नई खबरें</span>
        <span><i class="fa-regular fa-eye"></i> 2.4M पाठक / माह</span>
      </div>
    </div>
  </div>
</div>

<!-- ════════ SUB-CATEGORY TABS ════════ -->
<div class="subcat-bar">
  <div class="container">
    <div class="subcat-tabs" id="subcat-tabs" role="tablist" aria-label="Sub-categories">
      <a href="{{ route('category.show', $category->slug) }}" class="subcat-tab active" style="text-decoration:none;">सभी</a>
      @if(isset($globalCategories))
        @foreach($globalCategories->where('id', '!=', $category->id)->take(6) as $otherCat)
          <a href="{{ route('category.show', $otherCat->slug) }}" class="subcat-tab" style="text-decoration:none;">{{ $otherCat->name }}</a>
        @endforeach
      @endif
    </div>
  </div>
</div>

<!-- ════════ MAIN CONTENT ════════ -->
<main class="cat-main">
  <div class="container">
    <div class="cat-layout">

      <!-- ════ LEFT: ARTICLES ════ -->
      <div class="cat-articles">

        @php $topStory = $posts->first(); @endphp
        @if($topStory)
          <!-- TOP STORY (Featured) -->
          <section class="top-story-wrap" aria-label="शीर्ष खबर">
            <div class="top-story-label">
              <span class="ts-badge"><i class="fa-solid fa-star"></i> शीर्ष खबर</span>
            </div>
            <a href="{{ route('post.show', $topStory->slug) }}" class="top-story-card">
              <div class="top-story-img">
                <img src="{{ $topStory->image_url }}" alt="{{ $topStory->title }}" />
                <div class="top-story-overlay"></div>
                @if($topStory->is_breaking)
                  <span class="badge badge-red ts-live-badge">🔴 ब्रेकिंग</span>
                @endif
              </div>
              <div class="top-story-body">
                <div class="ts-cats">
                  <span class="badge badge-orange">{{ $category->name }}</span>
                </div>
                <h2 class="top-story-title">{{ $topStory->title }}</h2>
                <p class="top-story-excerpt">{{ Str::limit(strip_tags($topStory->summary ?: $topStory->content), 200) }}</p>
                <div class="top-story-meta">
                  <span class="ts-author"><i class="fa-solid fa-pen"></i> {{ $topStory->author ? $topStory->author->name : 'विशेष संवाददाता' }}</span>
                  <span class="ts-dot">·</span>
                  <span><i class="fa-regular fa-clock"></i> {{ $topStory->published_at ? $topStory->published_at->diffForHumans() : $topStory->created_at->diffForHumans() }}</span>
                  <span class="ts-dot">·</span>
                  <span><i class="fa-regular fa-eye"></i> {{ number_format($topStory->views_count) }}</span>
                </div>
              </div>
            </a>
          </section>
        @endif

        <!-- AD BANNER -->
        <div class="ad-banner" style="margin:0 0 20px">
          @if(isset($sidebarAd))
            @if($sidebarAd->type === 'code')
              {!! $sidebarAd->custom_code !!}
            @elseif($sidebarAd->image_url)
              <a href="{{ $sidebarAd->target_url ?? '#' }}" target="_blank" rel="nofollow">
                <img src="{{ $sidebarAd->image_url }}" alt="{{ $sidebarAd->title }}" />
              </a>
            @endif
          @else
            <img src="https://picsum.photos/800/90?random=51" alt="Advertisement" />
          @endif
        </div>

        <!-- FILTER BAR -->
        <div class="filter-bar">
          <div class="filter-result-count">
            <span id="result-count">{{ $posts->total() }}</span> खबरें मिलीं
          </div>
        </div>

        <!-- NEWS ARTICLES GRID -->
        <div class="articles-container grid-view" id="articles-container">
          @forelse($posts->slice($topStory ? 1 : 0) as $post)
            <a href="{{ route('post.show', $post->slug) }}" class="art-card">
              <div class="art-card-img">
                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" />
                <span class="badge badge-orange art-badge">{{ $category->name }}</span>
              </div>
              <div class="art-card-body">
                <h3 class="art-card-title">{{ $post->title }}</h3>
                <p class="art-card-excerpt">{{ Str::limit(strip_tags($post->summary ?: $post->content), 120) }}</p>
                <div class="art-card-meta">
                  <span class="art-author">{{ $post->author ? $post->author->name : 'डेस्क रिपोर्ट' }}</span>
                  <span class="art-dot">·</span>
                  <span><i class="fa-regular fa-clock"></i> {{ $post->published_at ? $post->published_at->diffForHumans() : $post->created_at->diffForHumans() }}</span>
                  <span class="art-dot">·</span>
                  <span><i class="fa-regular fa-eye"></i> {{ number_format($post->views_count) }}</span>
                </div>
              </div>
            </a>
          @empty
            <p style="padding: 20px; color: #64748b;">इस श्रेणी में और कोई खबर उपलब्ध नहीं है।</p>
          @endforelse
        </div>

        <!-- PAGINATION -->
        <div style="margin-top: 30px; display: flex; justify-content: center;">
          {{ $posts->links() }}
        </div>

      </div><!-- /.cat-articles -->

      <!-- ════ RIGHT: SIDEBAR ════ -->
      @include('frontend.partials.sidebar')

    </div><!-- /.cat-layout -->
  </div><!-- /.container -->
</main>
@endsection
