<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', ($globalSettings['site_name'] ?? 'भारत समाचार') . ' | Bharat Samachar — देश की नंबर 1 हिंदी न्यूज़')</title>
  <meta name="description" content="@yield('meta_description', $globalSettings['site_description'] ?? 'भारत समाचार - देश की सबसे तेज़ हिंदी न्यूज़ वेबसाइट। ताज़ा खबरें, ब्रेकिंग न्यूज़, राजनीति, खेल, मनोरंजन और अंतरराष्ट्रीय समाचार।')" />
  <meta name="keywords" content="@yield('meta_keywords', 'hindi news, hindi samachar, breaking news, bharat samachar, latest news india')" />

  <!-- Open Graph -->
  <meta property="og:title" content="@yield('og_title', $globalSettings['site_name'] ?? 'भारत समाचार')" />
  <meta property="og:description" content="@yield('meta_description', $globalSettings['site_description'] ?? '')" />
  <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="{{ url()->current() }}" />

  <!-- Font Awesome (CDN) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  @stack('styles')

  {!! $globalSettings['header_script'] ?? '' !!}
</head>
<body>

<!-- ════════════════════════════════════════
     TOP BAR
════════════════════════════════════════ -->
<div class="top-bar">
  <div class="container">
    <div class="top-bar-date">
      <i class="fa-regular fa-calendar"></i>
      <span id="top-date">{{ now()->locale('hi')->translatedFormat('l, d F Y') }}</span>
    </div>

    <div class="top-bar-right">
      <a href="{{ !empty($globalSettings['epaper_url']) ? $globalSettings['epaper_url'] : '#' }}">ई-पेपर</a>
      <a href="{{ route('admin.login') }}"><i class="fa-solid fa-lock"></i> एडमिन</a>
      <a href="#">विज्ञापन</a>
      <a href="#">संपर्क</a>
      <div class="social-icons">
        @if(!empty($globalSettings['social_facebook']))
          <a href="{{ $globalSettings['social_facebook'] }}" target="_blank" class="fb" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
        @else
          <a href="#" class="fb" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
        @endif
        @if(!empty($globalSettings['social_twitter']))
          <a href="{{ $globalSettings['social_twitter'] }}" target="_blank" class="tw" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
        @else
          <a href="#" class="tw" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
        @endif
        @if(!empty($globalSettings['social_youtube']))
          <a href="{{ $globalSettings['social_youtube'] }}" target="_blank" class="yt" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
        @else
          <a href="#" class="yt" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
        @endif
        @if(!empty($globalSettings['social_instagram']))
          <a href="{{ $globalSettings['social_instagram'] }}" target="_blank" class="ig" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
        @else
          <a href="#" class="ig" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════
     TICKER
════════════════════════════════════════ -->
<div class="ticker-wrap">
  <div class="container">
    <span class="ticker-label">⚡ ताज़ा</span>
    <div class="ticker-content">
      <div class="ticker-inner" id="ticker">
        @php
            $hasTickers = isset($globalBreakingTickers) && $globalBreakingTickers->count() > 0;
            $hasPosts = isset($globalBreakingPosts) && $globalBreakingPosts->count() > 0;
        @endphp

        @if($hasTickers || $hasPosts)
          <!-- Original List -->
          @if($hasTickers)
            @foreach($globalBreakingTickers as $ticker)
              @if($ticker->link_url)
                  <a href="{{ $ticker->link_url }}" class="ticker-item" style="color:inherit; text-decoration:none;">{{ $ticker->ticker_text }}</a>
              @else
                  <span class="ticker-item">{{ $ticker->ticker_text }}</span>
              @endif
            @endforeach
          @endif
          @if($hasPosts)
            @foreach($globalBreakingPosts as $bPost)
              <a href="{{ route('post.show', $bPost->slug) }}" class="ticker-item" style="color:inherit; text-decoration:none;">{{ $bPost->title }}</a>
            @endforeach
          @endif

          <!-- Duplicate for seamless loop -->
          @if($hasTickers)
            @foreach($globalBreakingTickers as $ticker)
              @if($ticker->link_url)
                  <a href="{{ $ticker->link_url }}" class="ticker-item" style="color:inherit; text-decoration:none;">{{ $ticker->ticker_text }}</a>
              @else
                  <span class="ticker-item">{{ $ticker->ticker_text }}</span>
              @endif
            @endforeach
          @endif
          @if($hasPosts)
            @foreach($globalBreakingPosts as $bPost)
              <a href="{{ route('post.show', $bPost->slug) }}" class="ticker-item" style="color:inherit; text-decoration:none;">{{ $bPost->title }}</a>
            @endforeach
          @endif
        @else
          <span class="ticker-item">प्रधानमंत्री मोदी ने नई दिल्ली में द्विपक्षीय बैठक की</span>
          <span class="ticker-item">भारत ने चंद्रयान-4 मिशन के लिए ISRO को मिली मंज़ूरी</span>
          <span class="ticker-item">शेयर बाजार में भारी उछाल, सेंसेक्स 82,000 के पार</span>
          <span class="ticker-item">टीम इंडिया ने दर्ज की शानदार जीत</span>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════
     HEADER
════════════════════════════════════════ -->
<header class="header" id="main-header">
  <div class="header-top">
    <div class="container">
      <!-- Logo -->
      <a href="{{ route('home') }}" class="logo">
        <div class="logo-icon">🇮🇳</div>
        <div class="logo-text">
          <span class="logo-hindi">{{ $globalSettings['site_name'] ?? 'भारत समाचार' }}</span>
          <span class="logo-tagline">{{ $globalSettings['site_tagline'] ?? 'Bharat Samachar · Sach Ki Awaaz' }}</span>
        </div>
      </a>

      <!-- Header Ad -->
      <div class="header-ad">
        @if(isset($headerAd))
          @if($headerAd->type === 'code')
            {!! $headerAd->custom_code !!}
          @elseif($headerAd->image_url)
            <a href="{{ $headerAd->target_url ?? '#' }}" target="_blank" rel="nofollow">
              <img src="{{ $headerAd->image_url }}" alt="{{ $headerAd->title }}" />
            </a>
          @endif
        @else
          <img src="https://picsum.photos/600/70?random=99" alt="Advertisement" />
        @endif
      </div>

      <!-- Header Actions -->
      <div class="header-actions">
        <button class="search-btn" id="search-toggle" aria-label="Search">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        <button class="btn-subscribe" id="subscribe-btn" onclick="document.querySelector('.newsletter-section')?.scrollIntoView({behavior: 'smooth'})">🔔 सदस्यता लें</button>
        <button class="hamburger" id="hamburger" aria-label="Menu">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>

    <!-- Search Bar -->
    <div class="search-bar" id="search-bar">
      <form action="{{ route('search') }}" method="GET" class="search-bar-inner">
        <input type="text" name="q" placeholder="खबर खोजें... (Search news in Hindi)" id="search-input" value="{{ request('q') }}" required />
        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
      </form>
    </div>
  </div>

  <!-- Navbar -->
  <nav class="navbar" aria-label="Main Navigation">
    <div class="container">
      <ul class="nav-list" id="nav-list">
        <li class="nav-item"><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">🏠 होम</a></li>
        @if(isset($globalCategories))
          @foreach($globalCategories as $gCat)
            <li class="nav-item">
              <a href="{{ route('category.show', $gCat->slug) }}" class="nav-link {{ (request()->is('category/' . $gCat->slug)) ? 'active' : '' }}">
                {{ $gCat->name }}
              </a>
            </li>
          @endforeach
        @endif
        <li class="nav-item">
          <a href="#" class="nav-live">🔴 LIVE TV</a>
        </li>
      </ul>
    </div>
  </nav>
</header>

@yield('content')

<!-- ════════════════════════════════════════
     FOOTER
════════════════════════════════════════ -->
<footer class="footer" aria-label="Footer">
  <div class="container">
    <div class="footer-grid">

      <!-- Brand -->
      <div class="footer-brand">
        <a href="{{ route('home') }}" class="logo">
          <div class="logo-icon" style="width:40px;height:40px;font-size:1.2rem">🇮🇳</div>
          <div class="logo-text">
            <span class="logo-hindi">{{ $globalSettings['site_name'] ?? 'भारत समाचार' }}</span>
            <span class="logo-tagline">{{ $globalSettings['site_tagline'] ?? 'Bharat Samachar' }}</span>
          </div>
        </a>
        <p class="footer-about">{{ $globalSettings['footer_about'] ?? 'भारत समाचार देश की सबसे विश्वसनीय हिंदी न्यूज़ वेबसाइट है। हम आपको 24×7 ताज़ा, निष्पक्ष और सटीक खबरें प्रदान करते हैं।' }}</p>
        <div class="footer-social">
          @if(!empty($globalSettings['social_facebook']))
            <a href="{{ $globalSettings['social_facebook'] }}" target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          @endif
          @if(!empty($globalSettings['social_twitter']))
            <a href="{{ $globalSettings['social_twitter'] }}" target="_blank" aria-label="Twitter/X"><i class="fa-brands fa-x-twitter"></i></a>
          @endif
          @if(!empty($globalSettings['social_youtube']))
            <a href="{{ $globalSettings['social_youtube'] }}" target="_blank" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
          @endif
          @if(!empty($globalSettings['social_instagram']))
            <a href="{{ $globalSettings['social_instagram'] }}" target="_blank" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          @endif
          @if(!empty($globalSettings['social_whatsapp']))
            <a href="{{ $globalSettings['social_whatsapp'] }}" target="_blank" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
          @endif
        </div>
      </div>

      <!-- Category Links -->
      <div>
        <span class="footer-heading">समाचार श्रेणी</span>
        <div class="footer-links">
          @if(isset($globalCategories))
            @foreach($globalCategories->take(9) as $fCat)
              <a href="{{ route('category.show', $fCat->slug) }}">{{ $fCat->name }}</a>
            @endforeach
          @endif
        </div>
      </div>

      <!-- Quick Links -->
      <div>
        <span class="footer-heading">त्वरित लिंक</span>
        <div class="footer-links">
          <a href="#">हमारे बारे में</a>
          <a href="#">संपादकीय नीति</a>
          <a href="{{ !empty($globalSettings['epaper_url']) ? $globalSettings['epaper_url'] : '#' }}">ई-पेपर</a>
          <a href="#">RSS फ़ीड</a>
          <a href="#">विज्ञापन दें</a>
          <a href="{{ route('admin.login') }}">एडमिन लॉगिन</a>
          <a href="#">Live TV</a>
          <a href="#">पॉडकास्ट</a>
        </div>
      </div>

      <!-- Contact -->
      <div>
        <span class="footer-heading">संपर्क करें</span>
        <div class="footer-contact-item">
          <i class="fa-solid fa-location-dot"></i>
          <span>{{ $globalSettings['contact_address'] ?? 'भारत समाचार मीडिया हाउस, कनॉट प्लेस, नई दिल्ली — 110001' }}</span>
        </div>
        <div class="footer-contact-item">
          <i class="fa-solid fa-phone"></i>
          <span>{{ $globalSettings['contact_phone'] ?? '+91-11-4567-8900' }}</span>
        </div>
        <div class="footer-contact-item">
          <i class="fa-solid fa-envelope"></i>
          <span>{{ $globalSettings['contact_email'] ?? 'editor@bharatsamachar.in' }}</span>
        </div>
        <div class="footer-contact-item">
          <i class="fa-solid fa-clock"></i>
          <span>24×7 न्यूज़रूम हॉटलाइन</span>
        </div>
      </div>

    </div>
  </div>

  <div class="footer-bottom">
    <div class="container" style="display:flex;justify-content:space-between;align-items:center;width:100%">
      <p>{{ $globalSettings['copyright_text'] ?? '© 2026 भारत समाचार (Bharat Samachar). सभी अधिकार सुरक्षित।' }}</p>
      <div class="footer-bottom-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Use</a>
        <a href="#">Cookie Policy</a>
        <a href="#">Sitemap</a>
      </div>
    </div>
  </div>
</footer>

<!-- Scroll To Top -->
<button class="scroll-top" id="scroll-top" aria-label="ऊपर जाएँ">
  <i class="fa-solid fa-chevron-up"></i>
</button>

<!-- ════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════ -->
<script>
  /* ── Live Date ── */
  (function () {
    const days   = ['रविवार','सोमवार','मंगलवार','बुधवार','गुरुवार','शुक्रवार','शनिवार'];
    const months = ['जनवरी','फरवरी','मार्च','अप्रैल','मई','जून','जुलाई','अगस्त','सितम्बर','अक्टूबर','नवम्बर','दिसम्बर'];
    const now    = new Date();
    const el     = document.getElementById('top-date');
    if (el) el.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
  })();

  /* ── Search Toggle ── */
  const searchToggle = document.getElementById('search-toggle');
  const searchBar    = document.getElementById('search-bar');
  if (searchToggle && searchBar) {
    searchToggle.addEventListener('click', () => {
      searchBar.classList.toggle('open');
      if (searchBar.classList.contains('open')) {
        document.getElementById('search-input')?.focus();
      }
    });
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') searchBar.classList.remove('open');
    });
  }

  /* ── Hamburger ── */
  const hamburger = document.getElementById('hamburger');
  const navList   = document.getElementById('nav-list');
  if (hamburger && navList) {
    hamburger.addEventListener('click', () => {
      navList.classList.toggle('open');
      hamburger.classList.toggle('active');
    });
  }

  /* ── Scroll To Top ── */
  const scrollTopBtn = document.getElementById('scroll-top');
  window.addEventListener('scroll', () => {
    if (scrollTopBtn) {
      scrollTopBtn.classList.toggle('visible', window.scrollY > 400);
    }
  });
  if (scrollTopBtn) {
    scrollTopBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }

  /* ── Poll ── */
  const pollBtn = document.getElementById('poll-vote-btn');
  if (pollBtn) {
    pollBtn.addEventListener('click', () => {
      pollBtn.textContent = '✅ आपका वोट दर्ज हो गया!';
      pollBtn.disabled = true;
      pollBtn.style.opacity = '.7';
    });
  }

  /* ── Newsletter Form AJAX ── */
  const nlBtn   = document.getElementById('nl-btn');
  const nlEmail = document.getElementById('nl-email');
  if (nlBtn && nlEmail) {
    nlBtn.addEventListener('click', async () => {
      if (!nlEmail.value.includes('@')) {
        nlEmail.style.borderColor = '#e74c3c';
        return;
      }
      nlEmail.style.borderColor = '';
      nlBtn.textContent = 'प्रतीक्षा करें...';
      try {
        const res = await fetch('{{ route("newsletter.subscribe") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ email: nlEmail.value })
        });
        if (res.ok) {
          nlBtn.textContent = '🎉 धन्यवाद! सदस्यता पूरी हुई';
          nlBtn.disabled = true;
          nlEmail.disabled = true;
        } else {
          nlBtn.textContent = 'सदस्यता लें →';
          alert('कृपया सही ईमेल दर्ज करें।');
        }
      } catch (err) {
        nlBtn.textContent = 'सदस्यता लें →';
      }
    });
  }

  /* ── Animate poll bars on load ── */
  window.addEventListener('load', () => {
    document.querySelectorAll('.poll-bar-fill').forEach(bar => {
      const w = bar.style.width;
      bar.style.width = '0';
      setTimeout(() => { bar.style.width = w; }, 400);
    });
  });

  /* ── Card hover ripple effect ── */
  document.querySelectorAll('.news-card-v, .news-card-h, .hero-side-card').forEach(card => {
    card.addEventListener('click', function () {
      this.style.transform = 'scale(0.98)';
      setTimeout(() => { this.style.transform = ''; }, 150);
    });
  });
</script>

{!! $globalSettings['footer_script'] ?? '' !!}
@stack('scripts')
</body>
</html>
