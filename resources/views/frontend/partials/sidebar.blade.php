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

  <!-- Share Market Widget -->
  <style>
    .market-widget { background: #fff; border-radius: 12px; border: 1px solid var(--border-color); padding: 16px; margin-bottom: 24px; font-family: 'Noto Sans Devanagari', sans-serif; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .market-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #dc2626; }
    .market-title { font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px; color: #0f172a; margin: 0; }
    .market-header-right { display: flex; gap: 8px; align-items: center; }
    .live-badge { display: flex; align-items: center; gap: 4px; background: #ecfdf5; color: #059669; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; border: 1px solid #d1fae5; }
    .live-dot { width: 6px; height: 6px; background: #10b981; border-radius: 50%; animation: pulse 2s infinite; }
    @keyframes pulse { 0% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.5); opacity: 0.5; } 100% { transform: scale(1); opacity: 1; } }
    .refresh-btn { background: #f1f5f9; border: none; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #475569; transition: 0.2s; }
    .refresh-btn:hover { background: #e2e8f0; color: #0f172a; }
    @keyframes rotate { 100% { transform: rotate(360deg); } }
    .rotating i { animation: rotate 1s linear infinite; }
    
    .indices-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; }
    .index-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px; position: relative; overflow: hidden; }
    .index-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: #cbd5e1; transition: 0.3s; }
    .index-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
    .index-name { font-size: 11px; font-weight: 700; color: #64748b; }
    .val-up { color: #059669; font-weight: 700; font-size: 11px; display: flex; align-items: center; gap: 2px; }
    .val-down { color: #dc2626; font-weight: 700; font-size: 11px; display: flex; align-items: center; gap: 2px; }
    .index-price { font-size: 15px; font-weight: 800; color: #0f172a; }

    .featured-stock { background: #1e293b; border-radius: 10px; padding: 14px; position: relative; overflow: hidden; margin-bottom: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .fs-loader { position: absolute; top: 0; left: 0; height: 3px; background: #06b6d4; width: 0%; }
    .fs-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
    .fs-left { display: flex; gap: 10px; align-items: center; }
    .fs-rank { background: #334155; color: #fff; font-size: 12px; font-weight: 700; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 6px; }
    .fs-title { color: #fff; font-weight: 700; font-size: 15px; line-height: 1.2; }
    .fs-sub { color: #94a3b8; font-size: 11px; }
    .fs-pct-down { background: rgba(220, 38, 38, 0.2); color: #fca5a5; padding: 4px 8px; border-radius: 20px; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 4px; border: 1px solid rgba(220, 38, 38, 0.4); }
    .fs-pct-up { background: rgba(16, 185, 129, 0.2); color: #6ee7b7; padding: 4px 8px; border-radius: 20px; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 4px; border: 1px solid rgba(16, 185, 129, 0.4); }
    .fs-bottom { display: flex; justify-content: space-between; align-items: flex-end; }
    .fs-label { color: #64748b; font-size: 12px; }
    .fs-price { color: #fff; font-size: 22px; font-weight: 800; }

    .market-tabs { display: flex; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; margin-bottom: 12px; }
    .m-tab { flex: 1; text-align: center; padding: 10px 4px; font-size: 12px; font-weight: 600; cursor: pointer; background: #f8fafc; color: #64748b; border-right: 1px solid #e2e8f0; transition: 0.2s; line-height: 1.2; }
    .m-tab:last-child { border-right: none; }
    .m-tab.active { background: #dc2626; color: #fff; }
    .m-tab:not(.active):hover { background: #f1f5f9; color: #0f172a; }

    .stock-list { max-height: 320px; overflow-y: auto; padding-right: 4px; display: flex; flex-direction: column; gap: 8px; transition: opacity 0.3s ease; }
    .stock-list::-webkit-scrollbar { width: 6px; }
    .stock-list::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .stock-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .stock-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; background: #f8fafc; border-radius: 8px; border: 1px solid #f1f5f9; transition: transform 0.2s, background 0.2s; }
    .stock-item:hover { background: #f1f5f9; transform: translateX(2px); }
    .stock-item.active-item { background: #ecfdf5; border-color: #a7f3d0; }
    .si-left { display: flex; gap: 12px; align-items: center; }
    .si-rank { width: 24px; height: 24px; border-radius: 50%; background: #e2e8f0; color: #475569; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; }
    .stock-item.active-item .si-rank { background: #10b981; color: #fff; }
    .si-title { font-size: 13px; font-weight: 700; color: #0f172a; line-height: 1.2; }
    .si-sub { font-size: 11px; color: #64748b; margin-top: 2px; }
    .si-right { text-align: right; }
    .si-price { font-size: 13px; font-weight: 800; color: #0f172a; line-height: 1.2; }
    .si-badge-up { background: #d1fae5; color: #059669; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 2px; margin-top: 4px; }
    .si-badge-down { background: #fee2e2; color: #dc2626; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 2px; margin-top: 4px; }

    /* Loader */
    .stock-skeleton { animation: skeleton-loading 1s linear infinite alternate; }
    @keyframes skeleton-loading { 0% { background-color: #e2e8f0; } 100% { background-color: #cbd5e1; } }
  </style>

  <div class="market-widget">
    <div class="market-header">
      <h3 class="market-title">
        <i class="fa-solid fa-chart-line" style="color: #10b981;"></i> शेयर बाज़ार (Top 10)
      </h3>
      <div class="market-header-right">
        <div class="live-badge">
          <div class="live-dot"></div> लाइव
        </div>
        <button class="refresh-btn" id="refresh-stocks" title="Refresh"><i class="fa-solid fa-rotate-right"></i></button>
      </div>
    </div>

    <div class="indices-row">
      <div class="index-card">
        <div class="index-top">
          <span class="index-name">NIFTY 50</span>
          <span class="val-up" id="nifty-pct">...</span>
        </div>
        <div class="index-price" id="nifty-price">...</div>
      </div>
      <div class="index-card">
        <div class="index-top">
          <span class="index-name">SENSEX</span>
          <span class="val-up" id="sensex-pct">...</span>
        </div>
        <div class="index-price" id="sensex-price">...</div>
      </div>
    </div>

    <div class="featured-stock">
      <div class="fs-loader" id="fs-loader"></div>
      <div class="fs-top">
        <div class="fs-left">
          <div class="fs-rank"><i class="fa-solid fa-star" style="font-size: 10px;"></i></div>
          <div>
            <div class="fs-title stock-skeleton" id="fs-title" style="min-width: 60px; min-height: 18px; border-radius: 4px;"></div>
            <div class="fs-sub" id="fs-sub"></div>
          </div>
        </div>
        <div class="fs-pct-up" id="fs-pct">
          ...
        </div>
      </div>
      <div class="fs-bottom">
        <div class="fs-label">बाज़ार भाव:</div>
        <div class="fs-price" id="fs-price">...</div>
      </div>
    </div>

    <div class="market-tabs">
      <div class="m-tab active" data-type="top">टॉप 10</div>
      <div class="m-tab" data-type="gainers">लाभदायक<br>(Gainers)</div>
      <div class="m-tab" data-type="losers">नुकसान<br>(Losers)</div>
    </div>

    <div class="stock-list" id="stock-list-container">
      <div style="text-align:center; padding: 20px; color:#94a3b8;"><i class="fa-solid fa-circle-notch fa-spin"></i> लोड हो रहा है...</div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
        let allStocks = [];
        let currentFeaturedIndex = 0;
        let featuredInterval;
        
        const hindiNames = {
            'RELIANCE.NS': 'रिलायंस इंडस्ट्रीज़',
            'TCS.NS': 'टीसीएस',
            'HDFCBANK.NS': 'एचडीएफसी बैंक',
            'BHARTIARTL.NS': 'भारती एयरटेल',
            'ICICIBANK.NS': 'आईसीआईसीआई बैंक',
            'INFY.NS': 'इन्फोसिस',
            'ITC.NS': 'आईटीसी',
            'SBI.NS': 'एसबीआई',
            'L&TFH.NS': 'एलएंडटी फाइनेंस'
        };

        const fetchStocks = async () => {
            const btn = document.getElementById('refresh-stocks');
            btn.classList.add('rotating');
            
            try {
                const res = await fetch('/api/stocks');
                const data = await res.json();
                const results = data.quoteResponse?.result || [];
                if (results.length > 0) {
                    renderStocks(results);
                }
            } catch(e) {
                console.error("Failed to fetch stocks");
            } finally {
                setTimeout(() => {
                    btn.classList.remove('rotating');
                }, 500);
            }
        };

        const formatNumber = (num) => new Intl.NumberFormat('hi-IN').format(num || 0);
        const formatPct = (num) => (num > 0 ? '+' : '') + (num || 0).toFixed(2) + '%';

        const renderStocks = (results) => {
            let nifty = results.find(r => r.symbol === '^NSEI');
            let sensex = results.find(r => r.symbol === '^BSESN');
            
            if(nifty) {
                document.getElementById('nifty-price').innerText = formatNumber(nifty.regularMarketPrice);
                document.getElementById('nifty-pct').innerText = formatPct(nifty.regularMarketChangePercent);
                document.getElementById('nifty-pct').className = nifty.regularMarketChangePercent >= 0 ? 'val-up' : 'val-down';
                document.getElementById('nifty-pct').innerHTML = `<i class="fa-solid fa-caret-${nifty.regularMarketChangePercent >= 0 ? 'up' : 'down'}"></i> ` + document.getElementById('nifty-pct').innerHTML;
                document.getElementById('nifty-price').parentElement.style.borderBottomColor = nifty.regularMarketChangePercent >= 0 ? '#10b981' : '#ef4444';
            }
            if(sensex) {
                document.getElementById('sensex-price').innerText = formatNumber(sensex.regularMarketPrice);
                document.getElementById('sensex-pct').innerText = formatPct(sensex.regularMarketChangePercent);
                document.getElementById('sensex-pct').className = sensex.regularMarketChangePercent >= 0 ? 'val-up' : 'val-down';
                document.getElementById('sensex-pct').innerHTML = `<i class="fa-solid fa-caret-${sensex.regularMarketChangePercent >= 0 ? 'up' : 'down'}"></i> ` + document.getElementById('sensex-pct').innerHTML;
                document.getElementById('sensex-price').parentElement.style.borderBottomColor = sensex.regularMarketChangePercent >= 0 ? '#10b981' : '#ef4444';
            }

            allStocks = results.filter(r => !r.symbol.startsWith('^'));
            
            // Auto rotate featured stock
            const updateFeaturedStock = () => {
                if(allStocks.length === 0) return;
                
                let featured = allStocks[currentFeaturedIndex];
                
                // Update content directly
                let fsTitle = document.getElementById('fs-title');
                fsTitle.classList.remove('stock-skeleton');
                let fullTitle = featured.longName || featured.shortName || featured.symbol.split('.')[0];
                fsTitle.innerText = fullTitle.toUpperCase();
                document.getElementById('fs-sub').innerText = hindiNames[featured.symbol] || fullTitle;
                document.getElementById('fs-price').innerText = '₹' + formatNumber(featured.regularMarketPrice);
                
                let fsPct = document.getElementById('fs-pct');
                let isUp = featured.regularMarketChangePercent >= 0;
                fsPct.innerHTML = `<i class="fa-solid fa-arrow-trend-${isUp ? 'up' : 'down'}"></i> ${formatPct(featured.regularMarketChangePercent)}`;
                fsPct.className = isUp ? 'fs-pct-up' : 'fs-pct-down';
                
                // Animate loader line
                let loader = document.getElementById('fs-loader');
                loader.style.transition = 'none';
                loader.style.width = '0%';
                
                // Force reflow to restart animation
                void loader.offsetWidth;
                
                loader.style.transition = 'width 5s linear';
                loader.style.width = '100%';
            };

            updateFeaturedStock();
            
            if (featuredInterval) clearInterval(featuredInterval);
            featuredInterval = setInterval(() => {
                currentFeaturedIndex = (currentFeaturedIndex + 1) % allStocks.length;
                updateFeaturedStock();
            }, 5000);

            let activeTab = document.querySelector('.m-tab.active').dataset.type;
            renderList(activeTab);
        };

        const renderList = (filterType) => {
            let listToRender = [...allStocks];
            
            if (filterType === 'gainers') {
                listToRender = listToRender.filter(s => s.regularMarketChangePercent > 0).sort((a,b) => b.regularMarketChangePercent - a.regularMarketChangePercent);
            } else if (filterType === 'losers') {
                listToRender = listToRender.filter(s => s.regularMarketChangePercent < 0).sort((a,b) => a.regularMarketChangePercent - b.regularMarketChangePercent);
            } else {
                // Top - sort by market cap if possible, or just price
                listToRender.sort((a,b) => b.regularMarketPrice - a.regularMarketPrice);
            }

            const container = document.getElementById('stock-list-container');
            container.style.opacity = 0;

            setTimeout(() => {
                container.innerHTML = '';
                if(listToRender.length === 0) {
                    container.innerHTML = '<div style="text-align:center; padding: 20px; color:#94a3b8;">कोई डेटा नहीं मिला।</div>';
                }
                listToRender.forEach((stock, index) => {
                    const isUp = stock.regularMarketChangePercent >= 0;
                    let title = stock.longName || stock.shortName || stock.symbol.split('.')[0];
                    let sub = hindiNames[stock.symbol] || stock.shortName;
                    
                    let html = `
                        <div class="stock-item">
                            <div class="si-left">
                                <div class="si-rank">${index + 1}</div>
                                <div>
                                    <div class="si-title">${title.toUpperCase()}</div>
                                    <div class="si-sub">${sub}</div>
                                </div>
                            </div>
                            <div class="si-right">
                                <div class="si-price">₹${formatNumber(stock.regularMarketPrice)}</div>
                                <div class="${isUp ? 'si-badge-up' : 'si-badge-down'}"><i class="fa-solid fa-arrow-trend-${isUp ? 'up' : 'down'}"></i> ${formatPct(stock.regularMarketChangePercent)}</div>
                            </div>
                        </div>
                    `;
                    container.innerHTML += html;
                });
                container.style.opacity = 1;
            }, 200);
        };

        document.querySelectorAll('.m-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.m-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                renderList(this.dataset.type);
            });
        });

        document.getElementById('refresh-stocks').addEventListener('click', fetchStocks);
        
        fetchStocks();
        setInterval(fetchStocks, 15000); // 15 seconds
    });
  </script>

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
