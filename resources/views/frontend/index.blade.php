@extends('layouts.frontend')

@section('title', ($globalSettings['site_name'] ?? 'भारत समाचार') . ' | Bharat Samachar — देश की नंबर 1 हिंदी न्यूज़')

@section('content')
<main>

  <!-- ── HERO SECTION ── -->
  <section class="hero-section">
    <div class="container">
      <div class="hero-grid">

        @if($mainHero)
          <!-- Main Hero Card -->
          <a href="{{ route('post.show', $mainHero->slug) }}" style="display:block">
            <article class="hero-main" tabindex="0" role="article">
              <img src="{{ $mainHero->image_url }}" alt="{{ $mainHero->title }}" />
              <div class="hero-main-overlay"></div>
              <div class="hero-main-content">
                @if($mainHero->is_breaking)
                  <span class="badge badge-red">🔴 ब्रेकिंग न्यूज़</span>
                @elseif($mainHero->category)
                  <span class="badge" style="background: {{ $mainHero->category->color }}; color: #fff;">{{ $mainHero->category->name }}</span>
                @endif
                <h1 class="hero-main-title">
                  {{ $mainHero->title }}
                </h1>
                <div class="hero-main-meta">
                  <span><i class="fa-regular fa-clock"></i> {{ $mainHero->published_at ? $mainHero->published_at->diffForHumans() : $mainHero->created_at->diffForHumans() }}</span>
                  <span class="author"><i class="fa-solid fa-pen"></i> {{ $mainHero->author ? $mainHero->author->name : 'रमेश शर्मा' }}</span>
                  <span><i class="fa-regular fa-eye"></i> {{ number_format($mainHero->views_count) }} views</span>
                </div>
              </div>
            </article>
          </a>
        @endif

        <!-- Hero Sidebar -->
        <aside class="hero-sidebar">
          @if(isset($sideHeroes))
            @foreach($sideHeroes as $sIndex => $sPost)
              <a href="{{ route('post.show', $sPost->slug) }}" style="display:block; text-decoration:none; color:inherit;">
                <article class="hero-side-card {{ $sIndex === 0 ? 'featured' : '' }}" tabindex="0">
                  <div class="hero-side-img">
                    <img src="{{ $sPost->image_url }}" alt="{{ $sPost->title }}" />
                  </div>
                  <div class="hero-side-content">
                    @if($sPost->category)
                      <span class="badge" style="background: {{ $sPost->category->color }}; color: #fff;">{{ $sPost->category->name }}</span>
                    @endif
                    <p class="hero-side-title">{{ $sPost->title }}</p>
                    <span class="hero-side-meta"><i class="fa-regular fa-clock"></i> {{ $sPost->published_at ? $sPost->published_at->diffForHumans() : $sPost->created_at->diffForHumans() }}</span>
                  </div>
                </article>
              </a>
            @endforeach
          @endif
        </aside>

      </div><!-- /.hero-grid -->

      <!-- Breaking Strip -->
      <!-- <div class="breaking-strip" aria-label="ब्रेकिंग न्यूज़">
        <span class="breaking-label">⚡ BREAKING</span>
        <div class="breaking-items">
          <div class="breaking-scroll" id="breaking-scroll">
            @if(isset($globalBreakingTickers) && $globalBreakingTickers->count() > 0)
              @foreach($globalBreakingTickers as $ticker)
                <span>
                    @if($ticker->link_url)
                        <a href="{{ $ticker->link_url }}" style="color:inherit; text-decoration:none;">🔴 {{ $ticker->ticker_text }}</a>
                    @else
                        🔴 {{ $ticker->ticker_text }}
                    @endif
                    &nbsp;|&nbsp;
                </span>
              @endforeach
              @foreach($globalBreakingTickers as $ticker)
                <span>
                    @if($ticker->link_url)
                        <a href="{{ $ticker->link_url }}" style="color:inherit; text-decoration:none;">🔴 {{ $ticker->ticker_text }}</a>
                    @else
                        🔴 {{ $ticker->ticker_text }}
                    @endif
                    &nbsp;|&nbsp;
                </span>
              @endforeach
            @elseif(isset($globalBreakingPosts) && $globalBreakingPosts->count() > 0)
              @foreach($globalBreakingPosts as $bPost)
                <span><a href="{{ route('post.show', $bPost->slug) }}" style="color:inherit; text-decoration:none;">🔴 {{ $bPost->title }}</a> &nbsp;|&nbsp;</span>
              @endforeach
              @foreach($globalBreakingPosts as $bPost)
                <span><a href="{{ route('post.show', $bPost->slug) }}" style="color:inherit; text-decoration:none;">🔴 {{ $bPost->title }}</a> &nbsp;|&nbsp;</span>
              @endforeach
            @else
              <span>🔴 संसद का मानसून सत्र आज से शुरू &nbsp;|&nbsp;</span>
              <span>🔴 सुप्रीम कोर्ट ने EVM पर बड़ा फैसला दिया &nbsp;|&nbsp;</span>
              <span>🔴 ISRO का नया मिशन 2027 तक लॉन्च होगा &nbsp;|&nbsp;</span>
              <span>🔴 दिल्ली में भारी बारिश का अलर्ट, IMD ने जारी किया रेड अलर्ट &nbsp;|&nbsp;</span>
            @endif
          </div>
        </div>
      </div> -->

    </div>
  </section>

  <!-- ── AD BANNER ── -->
  <div class="container">
    <div class="ad-banner ad-banner-lg">
      @if(isset($homeMiddleAd))
        @if($homeMiddleAd->type === 'code')
          {!! $homeMiddleAd->custom_code !!}
        @elseif($homeMiddleAd->image_url)
          <a href="{{ $homeMiddleAd->target_url ?? '#' }}" target="_blank" rel="nofollow">
            <img src="{{ $homeMiddleAd->image_url }}" alt="{{ $homeMiddleAd->title }}" />
          </a>
        @endif
      @else
        <img src="https://picsum.photos/1280/120?random=50" alt="Advertisement" />
      @endif
    </div>
  </div>

  <!-- ════════════════════════════════════════
       MAIN CONTENT + SIDEBAR
  ════════════════════════════════════════ -->
  <div class="main-content">
    <div class="container">
      <div class="content-grid">

        <!-- ── LEFT CONTENT ── -->
        <div class="left-content">

          <!-- Dynamic Category Sections from Database -->
          @foreach($categorySections as $cIndex => $cSection)
            @if($cSection->posts->count() > 0)
              <section class="section-wrap">
                <div class="section-header">
                  <h2 class="section-title">
                    {{ $cSection->name }} <span class="en">{{ ucfirst($cSection->slug) }}</span>
                  </h2>
                  <a href="{{ route('category.show', $cSection->slug) }}" class="view-all">सभी देखें →</a>
                </div>

                @if($cIndex % 3 === 0)
                  <!-- Grid-3 Style -->
                  <div class="news-grid-3">
                    @foreach($cSection->posts->take(3) as $cPost)
                      <a href="{{ route('post.show', $cPost->slug) }}" style="text-decoration:none; color:inherit; display:block;">
                        <article class="news-card-v" tabindex="0">
                          <div class="news-card-v-img">
                            <img src="{{ $cPost->image_url }}" alt="{{ $cPost->title }}" />
                            <span class="badge" style="background: {{ $cSection->color }}; color: #fff;">{{ $cSection->name }}</span>
                          </div>
                          <div class="news-card-v-body">
                            <p class="news-card-v-title">{{ $cPost->title }}</p>
                            <div class="news-card-v-meta">
                              <span><i class="fa-regular fa-clock"></i> {{ $cPost->published_at ? $cPost->published_at->diffForHumans() : $cPost->created_at->diffForHumans() }}</span>
                              <span><i class="fa-regular fa-eye"></i> {{ number_format($cPost->views_count) }}</span>
                            </div>
                          </div>
                        </article>
                      </a>
                    @endforeach
                  </div>
                @elseif($cIndex % 3 === 1)
                  <!-- Category Hero Grid Style -->
                  <div class="category-hero-grid">
                    @php $firstPost = $cSection->posts->first(); @endphp
                    @if($firstPost)
                      <a href="{{ route('post.show', $firstPost->slug) }}" style="text-decoration:none; color:inherit; display:block;">
                        <article class="cat-main" tabindex="0">
                          <div class="cat-main-img">
                            <img src="{{ $firstPost->image_url }}" alt="{{ $firstPost->title }}" />
                          </div>
                          <div class="cat-main-overlay"></div>
                          <div class="cat-main-content">
                            <span class="badge" style="background: {{ $cSection->color }}; color: #fff;">{{ $cSection->name }}</span>
                            <h3 class="cat-main-title">{{ $firstPost->title }}</h3>
                            <span class="cat-main-meta"><i class="fa-regular fa-clock"></i> {{ $firstPost->published_at ? $firstPost->published_at->diffForHumans() : $firstPost->created_at->diffForHumans() }} &nbsp;·&nbsp; {{ $firstPost->author ? $firstPost->author->name : 'संवाददाता' }}</span>
                          </div>
                        </article>
                      </a>
                    @endif

                    <div class="cat-side">
                      @foreach($cSection->posts->slice(1, 3) as $subPost)
                        <a href="{{ route('post.show', $subPost->slug) }}" style="text-decoration:none; color:inherit; display:block;">
                          <article class="news-card-h" tabindex="0">
                            <div class="news-card-h-img"><img src="{{ $subPost->image_url }}" alt="{{ $subPost->title }}" /></div>
                            <div class="news-card-h-body">
                              <span class="badge" style="background: {{ $cSection->color }}; color: #fff;">{{ $cSection->name }}</span>
                              <p class="news-card-h-title">{{ $subPost->title }}</p>
                              <span class="news-card-h-meta"><i class="fa-regular fa-clock"></i> {{ $subPost->published_at ? $subPost->published_at->diffForHumans() : $subPost->created_at->diffForHumans() }}</span>
                            </div>
                          </article>
                        </a>
                      @endforeach
                    </div>
                  </div>
                @else
                  <!-- News Grid 4 Style -->
                  <div class="news-grid-4">
                    @foreach($cSection->posts->take(4) as $cPost)
                      <a href="{{ route('post.show', $cPost->slug) }}" style="text-decoration:none; color:inherit; display:block;">
                        <article class="news-card-v" tabindex="0">
                          <div class="news-card-v-img" style="height:150px">
                            <img src="{{ $cPost->image_url }}" alt="{{ $cPost->title }}" />
                            <span class="badge" style="background: {{ $cSection->color }}; color: #fff;">{{ $cSection->name }}</span>
                          </div>
                          <div class="news-card-v-body">
                            <p class="news-card-v-title">{{ $cPost->title }}</p>
                            <div class="news-card-v-meta"><span><i class="fa-regular fa-clock"></i> {{ $cPost->published_at ? $cPost->published_at->diffForHumans() : $cPost->created_at->diffForHumans() }}</span></div>
                          </div>
                        </article>
                      </a>
                    @endforeach
                  </div>
                @endif
              </section>

              @if($cIndex === 1)
                <!-- Mid Ad Banner -->
                <div class="ad-banner">
                  <img src="https://picsum.photos/1200/90?random=51" alt="Advertisement" />
                </div>
              @endif
            @endif
          @endforeach

          <!-- Video Section -->
          <section class="section-wrap">
            <div class="section-header">
              <h2 class="section-title">📹 वीडियो न्यूज़ <span class="en">Video News</span></h2>
              <a href="#" class="view-all">सभी देखें →</a>
            </div>
            <div class="video-grid">
              <article class="video-card" tabindex="0">
                <div class="video-thumb">
                  <img src="https://picsum.photos/300/150?random=60" alt="वीडियो" />
                  <div class="video-play"><div class="video-play-btn"><i class="fa-solid fa-play"></i></div></div>
                  <span class="video-duration">4:32</span>
                </div>
                <div class="video-body">
                  <p class="video-title">मोदी सरकार का 100 दिन का एजेंडा: क्या बदलेगा?</p>
                  <p class="video-meta"><i class="fa-regular fa-eye"></i> 1.2M views &nbsp;·&nbsp; 2 दिन पहले</p>
                </div>
              </article>
              <article class="video-card" tabindex="0">
                <div class="video-thumb">
                  <img src="https://picsum.photos/300/150?random=61" alt="वीडियो" />
                  <div class="video-play"><div class="video-play-btn"><i class="fa-solid fa-play"></i></div></div>
                  <span class="video-duration">7:18</span>
                </div>
                <div class="video-body">
                  <p class="video-title">ISRO वैज्ञानिक से खास बातचीत: चंद्रयान-4 की पूरी कहानी</p>
                  <p class="video-meta"><i class="fa-regular fa-eye"></i> 892K views &nbsp;·&nbsp; 3 दिन पहले</p>
                </div>
              </article>
              <article class="video-card" tabindex="0">
                <div class="video-thumb">
                  <img src="https://picsum.photos/300/150?random=62" alt="वीडियो" />
                  <div class="video-play"><div class="video-play-btn"><i class="fa-solid fa-play"></i></div></div>
                  <span class="video-duration">3:45</span>
                </div>
                <div class="video-body">
                  <p class="video-title">क्रिकेट विश्व कप 2027: भारत का शेड्यूल देखें यहाँ</p>
                  <p class="video-meta"><i class="fa-regular fa-eye"></i> 2.5M views &nbsp;·&nbsp; 1 दिन पहले</p>
                </div>
              </article>
              <article class="video-card" tabindex="0">
                <div class="video-thumb">
                  <img src="https://picsum.photos/300/150?random=63" alt="वीडियो" />
                  <div class="video-play"><div class="video-play-btn"><i class="fa-solid fa-play"></i></div></div>
                  <span class="video-duration">12:05</span>
                </div>
                <div class="video-body">
                  <p class="video-title">महंगाई पर आम जनता से बातचीत — सच्चाई क्या है?</p>
                  <p class="video-meta"><i class="fa-regular fa-eye"></i> 445K views &nbsp;·&nbsp; 4 दिन पहले</p>
                </div>
              </article>
            </div>
          </section>

          <!-- Photo Gallery -->
          <section class="section-wrap">
            <div class="section-header">
              <h2 class="section-title">📷 फोटो गैलरी <span class="en">Photo Gallery</span></h2>
              <a href="#" class="view-all">सभी देखें →</a>
            </div>
            <div class="gallery-grid">
              <div class="gallery-item" tabindex="0"><img src="https://picsum.photos/300/150?random=80" alt="गैलरी" /><div class="gallery-overlay"><i class="fa-solid fa-expand"></i></div></div>
              <div class="gallery-item" tabindex="0"><img src="https://picsum.photos/300/150?random=81" alt="गैलरी" /><div class="gallery-overlay"><i class="fa-solid fa-expand"></i></div></div>
              <div class="gallery-item" tabindex="0"><img src="https://picsum.photos/300/150?random=82" alt="गैलरी" /><div class="gallery-overlay"><i class="fa-solid fa-expand"></i></div></div>
              <div class="gallery-item" tabindex="0"><img src="https://picsum.photos/300/150?random=83" alt="गैलरी" /><div class="gallery-overlay"><i class="fa-solid fa-expand"></i></div></div>
              <div class="gallery-item" tabindex="0"><img src="https://picsum.photos/300/150?random=84" alt="गैलरी" /><div class="gallery-overlay"><i class="fa-solid fa-expand"></i></div></div>
              <div class="gallery-item" tabindex="0"><img src="https://picsum.photos/300/150?random=85" alt="गैलरी" /><div class="gallery-overlay"><i class="fa-solid fa-expand"></i></div></div>
              <div class="gallery-item" tabindex="0"><img src="https://picsum.photos/300/150?random=86" alt="गैलरी" /><div class="gallery-overlay"><i class="fa-solid fa-expand"></i></div></div>
              <div class="gallery-item" tabindex="0"><img src="https://picsum.photos/300/150?random=87" alt="गैलरी" /><div class="gallery-overlay"><i class="fa-solid fa-expand"></i></div></div>
            </div>
          </section>

          <!-- Tags Cloud -->
          <section class="section-wrap">
            <div class="section-header">
              <h2 class="section-title">🏷 टॉपिक <span class="en">Topics</span></h2>
            </div>
            <div class="tags-cloud">
              @if(isset($globalPopularTags))
                @foreach($globalPopularTags as $tag)
                  <a href="{{ route('search', ['tag' => $tag->slug]) }}" class="tag-chip">#{{ $tag->name }}</a>
                @endforeach
              @endif
            </div>
          </section>

        </div><!-- /.left-content -->

        <!-- ── SIDEBAR ── -->
        @include('frontend.partials.sidebar')

      </div><!-- /.content-grid -->
    </div><!-- /.container -->
  </div><!-- /.main-content -->

  <!-- ── NEWSLETTER ── -->
  <section class="newsletter-section" aria-label="न्यूज़लेटर">
    <div class="container">
      <div class="newsletter-wrap">
        <div class="newsletter-text">
          <h2>📧 ताज़ा खबरें सीधे <br/>आपके इनबॉक्स में पाएँ</h2>
          <p>हमारे न्यूज़लेटर की सदस्यता लें और हर सुबह देश-दुनिया की महत्वपूर्ण खबरें सबसे पहले पाएँ। बिल्कुल निःशुल्क!</p>
        </div>
        <div class="newsletter-form">
          <div class="newsletter-row">
            <input type="email" placeholder="आपका ईमेल पता लिखें..." id="nl-email" />
            <button id="nl-btn">सदस्यता लें →</button>
          </div>
          <p class="newsletter-note">* हम आपकी जानकारी किसी के साथ साझा नहीं करते। कभी भी सदस्यता रद्द करें।</p>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection
