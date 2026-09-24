<!-- ════════════════════════════════════════
     COMMON SIDEBAR (HOME / CATEGORY / DETAIL)
════════════════════════════════════════ -->
<aside class="sidebar">

  <!-- Sidebar Top Ad -->
  <div class="sidebar-ad">
    @if(isset($sidebarTopAd))
      @if($sidebarTopAd->type === 'code')
        {!! $sidebarTopAd->custom_code !!}
      @elseif($sidebarTopAd->image_url)
        <a href="{{ $sidebarTopAd->target_url ?? '#' }}" target="_blank" rel="nofollow">
          <img src="{{ $sidebarTopAd->image_url }}" alt="{{ $sidebarTopAd->title }}" />
        </a>
      @endif
    @else
      <img src="https://picsum.photos/340/260?random=90" alt="Advertisement" />
    @endif
  </div>

  <!-- Trending Section -->
  <div class="section-wrap">
    <div class="section-header">
      <h2 class="section-title">🔥 ट्रेंडिंग <span class="en">Trending</span></h2>
    </div>
    @if(isset($sidebarTrendingPosts))
      @foreach($sidebarTrendingPosts as $tIndex => $tPost)
        <a href="{{ route('post.show', $tPost->slug) }}" style="text-decoration:none; color:inherit; display:block;">
          <div class="trending-item" tabindex="0">
            <span class="trending-rank">{{ $tIndex + 1 }}</span>
            <div>
              <p class="trending-title">{{ $tPost->title }}</p>
              <p class="trending-meta">{{ number_format($tPost->views_count) }} views</p>
            </div>
          </div>
        </a>
      @endforeach
    @endif
  </div>

  <!-- Weather Widget -->
  <div class="section-wrap">
    <div class="section-header">
      <h2 class="section-title">🌦 मौसम <span class="en">Weather</span></h2>
    </div>
    <div class="weather-widget">
      <p class="weather-city">🗺 नई दिल्ली</p>
      <div class="weather-icon">⛅</div>
      <div class="weather-temp">29°C</div>
      <p class="weather-desc">आंशिक बादल छाए रहेंगे</p>
      <div class="weather-details">
        <div class="weather-detail">
          <p class="weather-detail-label">Humidity</p>
          <p class="weather-detail-val">72%</p>
        </div>
        <div class="weather-detail">
          <p class="weather-detail-label">Wind</p>
          <p class="weather-detail-val">18 km/h</p>
        </div>
        <div class="weather-detail">
          <p class="weather-detail-label">UV Index</p>
          <p class="weather-detail-val">High</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Poll Widget -->
  <div class="section-wrap">
    <div class="section-header">
      <h2 class="section-title">📊 पाठक सर्वेक्षण <span class="en">Poll</span></h2>
    </div>
    <div class="poll-widget">
      <p class="poll-question">क्या आप सरकार की नई शिक्षा नीति से सहमत हैं?</p>
      <div class="poll-option">
        <div class="poll-option-label">
          <span>हाँ, बिल्कुल सहमत हूँ</span>
          <span class="poll-pct">54%</span>
        </div>
        <div class="poll-bar-bg"><div class="poll-bar-fill green" style="width:54%"></div></div>
      </div>
      <div class="poll-option">
        <div class="poll-option-label">
          <span>नहीं, असहमत हूँ</span>
          <span class="poll-pct">30%</span>
        </div>
        <div class="poll-bar-bg"><div class="poll-bar-fill" style="width:30%"></div></div>
      </div>
      <div class="poll-option">
        <div class="poll-option-label">
          <span>तटस्थ हूँ</span>
          <span class="poll-pct">16%</span>
        </div>
        <div class="poll-bar-bg"><div class="poll-bar-fill blue" style="width:16%"></div></div>
      </div>
      <button class="poll-vote-btn" id="poll-vote-btn">✅ वोट करें</button>
    </div>
  </div>

  <!-- Top Stories (Numbered) -->
  @if(isset($sidebarEditorPicks) && $sidebarEditorPicks->count() > 0)
    <div class="section-wrap">
      <div class="section-header">
        <h2 class="section-title">📌 टॉप स्टोरीज़ <span class="en">Top Stories</span></h2>
        <a href="#" class="view-all">और →</a>
      </div>
      <div class="numbered-news">
        @foreach($sidebarEditorPicks as $epIndex => $epPost)
          <a href="{{ route('post.show', $epPost->slug) }}" style="text-decoration:none; color:inherit; display:block;">
            <div class="numbered-item" tabindex="0">
              <span class="numbered-num">0{{ $epIndex + 1 }}</span>
              <div>
                <p class="numbered-title">{{ $epPost->title }}</p>
                <p class="numbered-meta">{{ $epPost->published_at ? $epPost->published_at->diffForHumans() : '' }}</p>
              </div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  @endif

  <!-- Sidebar Bottom Ad -->
  <div class="sidebar-ad" style="height:200px">
    @if(isset($sidebarBottomAd))
      @if($sidebarBottomAd->type === 'code')
        {!! $sidebarBottomAd->custom_code !!}
      @elseif($sidebarBottomAd->image_url)
        <a href="{{ $sidebarBottomAd->target_url ?? '#' }}" target="_blank" rel="nofollow">
          <img src="{{ $sidebarBottomAd->image_url }}" alt="{{ $sidebarBottomAd->title }}" />
        </a>
      @endif
    @else
      <img src="https://picsum.photos/340/200?random=91" alt="Advertisement" />
    @endif
  </div>

</aside>
