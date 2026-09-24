@extends('layouts.frontend')

@section('title', $post->title . ' | ' . ($globalSettings['site_name'] ?? 'भारत समाचार'))
@section('meta_description', $post->meta_description ?? Str::limit(strip_tags($post->summary ?: $post->content), 160))
@section('meta_keywords', $post->meta_keywords ?? 'hindi news, breaking news, ' . ($post->category ? $post->category->name : ''))

@section('og_title', $post->title)
@section('og_image', $post->image_url)

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/detail.css') }}" />
@endpush

@section('content')
<!-- ════════════════════════════════════════
     BREADCRUMB
════════════════════════════════════════ -->
<div class="breadcrumb-bar">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> होम</a>
      <span class="bc-sep"><i class="fa-solid fa-chevron-right"></i></span>
      @if($post->category)
        <a href="{{ route('category.show', $post->category->slug) }}">{{ $post->category->name }}</a>
        <span class="bc-sep"><i class="fa-solid fa-chevron-right"></i></span>
      @endif
      <span class="bc-current">{{ Str::limit($post->title, 40) }}</span>
    </nav>
  </div>
</div>

<!-- ════════════════════════════════════════
     DETAIL LAYOUT
════════════════════════════════════════ -->
<main class="detail-main">
  <div class="container">
    <div class="detail-grid">

      <!-- ════ LEFT: ARTICLE ════ -->
      <article class="article-body" id="article-top">

        <!-- Article Header -->
        <div class="article-header">
          <div class="article-cats">
            @if($post->is_breaking)
              <span class="badge badge-red">🔴 ब्रेकिंग न्यूज़</span>
            @endif
            @if($post->category)
              <span class="badge badge-orange">{{ $post->category->name }}</span>
            @endif
          </div>

          <h1 class="article-title">
            {{ $post->title }}
          </h1>

          @if($post->summary)
            <p class="article-summary">
              {{ $post->summary }}
            </p>
          @endif

          <!-- Meta Info Bar -->
          <div class="article-meta-bar">
            <div class="article-meta-left">
              <div class="author-info">
                <span class="author-name"><i class="fa-solid fa-user-pen"></i> {{ $post->author ? $post->author->name : 'वरिष्ठ संवाददाता' }}</span>
                <span class="author-role">डेस्क रिपोर्ट</span>
              </div>
            </div>
            <div class="article-meta-right">
              <span class="meta-item"><i class="fa-regular fa-calendar"></i> {{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
              <span class="meta-item"><i class="fa-regular fa-clock"></i> {{ $post->published_at ? $post->published_at->format('h:i A') : $post->created_at->format('h:i A') }}</span>
              <span class="meta-item"><i class="fa-regular fa-eye"></i> {{ number_format($post->views_count) }} पाठक</span>
            </div>
          </div>

          <!-- Share Bar Top -->
          <div class="share-bar" id="share-bar-top">
            <span class="share-label"><i class="fa-solid fa-share-nodes"></i> शेयर करें:</span>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="share-btn fb" aria-label="Share on Facebook"><i class="fa-brands fa-facebook-f"></i> Facebook</a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="share-btn tw" aria-label="Share on Twitter"><i class="fa-brands fa-x-twitter"></i> Twitter</a>
            <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="share-btn wa" aria-label="Share on WhatsApp"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
            <button onclick="navigator.clipboard.writeText(window.location.href); alert('लिंक कॉपी हो गया!');" class="share-btn copy" aria-label="Copy link"><i class="fa-solid fa-link"></i> <span id="copy-text">कॉपी करें</span></button>
          </div>
        </div>

        <!-- Hero Image -->
        <figure class="article-hero-img">
          <img src="{{ $post->image_url }}" alt="{{ $post->title }}" />
          @if($post->image_caption)
            <figcaption>
              <i class="fa-solid fa-camera"></i>
              {{ $post->image_caption }}
            </figcaption>
          @endif
        </figure>

        <!-- Article In-Content Ad -->
        @if(isset($inArticleAd))
          <div class="ad-banner" style="margin: 20px 0;">
            @if($inArticleAd->type === 'code')
              {!! $inArticleAd->custom_code !!}
            @elseif($inArticleAd->image_url)
              <a href="{{ $inArticleAd->target_url ?? '#' }}" target="_blank" rel="nofollow">
                <img src="{{ $inArticleAd->image_url }}" alt="{{ $inArticleAd->title }}" />
              </a>
            @endif
          </div>
        @endif

        <!-- Article Content -->
        <div class="article-content" id="article-content">
          {!! $post->content !!}
        </div>

        <!-- Share Bar Bottom -->
        <div class="share-bar share-bar-bottom" id="share-bar-bottom">
          <span class="share-label"><i class="fa-solid fa-share-nodes"></i> यह खबर शेयर करें:</span>
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="share-btn fb"><i class="fa-brands fa-facebook-f"></i> Facebook</a>
          <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="share-btn tw"><i class="fa-brands fa-x-twitter"></i> Twitter</a>
          <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="share-btn wa"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
        </div>

        <!-- Tags -->
        @if($post->tags->count() > 0)
          <div class="article-tags">
            <span class="tags-label"><i class="fa-solid fa-tags"></i> संबंधित विषय:</span>
            <div class="tags-list">
              @foreach($post->tags as $tag)
                <a href="{{ route('search', ['tag' => $tag->slug]) }}" class="tag-chip">#{{ $tag->name }}</a>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Related News -->
        @if(isset($relatedPosts) && $relatedPosts->count() > 0)
          <section class="related-section">
            <div class="section-header">
              <h2 class="section-title">📌 संबंधित खबरें <span class="en">Related News</span></h2>
            </div>
            <div class="related-grid">
              @foreach($relatedPosts as $rPost)
                <a href="{{ route('post.show', $rPost->slug) }}" class="related-card">
                  <div class="related-img">
                    <img src="{{ $rPost->image_url }}" alt="{{ $rPost->title }}" />
                  </div>
                  <div class="related-body">
                    @if($rPost->category)
                      <span class="badge badge-orange">{{ $rPost->category->name }}</span>
                    @endif
                    <p class="related-title">{{ $rPost->title }}</p>
                    <span class="related-meta"><i class="fa-regular fa-clock"></i> {{ $rPost->published_at ? $rPost->published_at->diffForHumans() : '' }}</span>
                  </div>
                </a>
              @endforeach
            </div>
          </section>
        @endif

      </article><!-- /.article-body -->

      <!-- ════ RIGHT: SIDEBAR ════ -->
      @include('frontend.partials.sidebar')

    </div><!-- /.detail-grid -->
  </div><!-- /.container -->
</main>
@endsection
