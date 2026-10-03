@extends('layouts.frontend')

@section('title', 'Video News - ' . ($globalSettings['site_name'] ?? 'News 10'))
@section('meta_description', 'Latest video news and updates')

@section('content')
<main class="page-content" style="padding-top: 30px;">
  <div class="main-content">
    <div class="container">
      <div class="content-grid" style="grid-template-columns: 1fr;">
        <div class="left-content" style="width: 100%;">
          
          <div class="section-header" style="margin-bottom: 20px;">
            <h1 class="section-title">📹 वीडियो न्यूज़ <span class="en">Video News</span></h1>
          </div>

          <div class="video-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            @forelse($videos as $video)
              @php $thumbUrl = str_starts_with($video->thumbnail, 'http') ? $video->thumbnail : asset('storage/'.$video->thumbnail); @endphp
              <article class="video-card" tabindex="0" onclick="openVideoModal('{{ $video->youtube_url }}', '{{ $thumbUrl }}')" style="cursor: pointer;">
                <div class="video-thumb">
                  <img src="{{ $thumbUrl }}" alt="{{ $video->title }}" />
                  <div class="video-play"><div class="video-play-btn"><i class="fa-solid fa-play"></i></div></div>
                  @if($video->duration) <span class="video-duration">{{ $video->duration }}</span> @endif
                </div>
                <div class="video-body">
                  <p class="video-title">{{ $video->title }}</p>
                  <p class="video-meta">
                    @if($video->views_text) <i class="fa-regular fa-eye"></i> {{ $video->views_text }} &nbsp;·&nbsp; @endif
                    {{ $video->time_ago_text ?? $video->created_at->diffForHumans() }}
                  </p>
                </div>
              </article>
            @empty
              <p>No videos available.</p>
            @endforelse
          </div>
          
          <div style="margin-top: 30px;">
            {{ $videos->links('pagination::bootstrap-5') }}
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Video Modal -->
  <div id="videoModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; justify-content:center; align-items:center;">
    <div id="videoContainer" style="position:relative; width:90%; max-width:800px; background:#000; border-radius:8px; overflow:hidden; aspect-ratio:16/9; background-position: center; background-size: cover;">
      <button onclick="closeVideoModal()" style="position:absolute; top:10px; right:10px; background:rgba(255,255,255,0.2); border:none; color:#fff; font-size:24px; cursor:pointer; width:40px; height:40px; border-radius:50%; z-index:10;">&times;</button>
      <iframe id="videoIframe" width="100%" height="100%" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position:relative; z-index:5;"></iframe>
    </div>
  </div>
</main>

@push('styles')
<style>
.video-card:hover { transform: translateY(-4px); transition: 0.3s; }
.video-card:hover .video-play-btn { background: var(--red); color: #fff; transform: scale(1.1); }
</style>
@endpush

<script>
function extractVideoID(url){
    var regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match && match[2].length == 11) {
        return match[2];
    }
    return null;
}
function openVideoModal(url, thumbUrl) {
    var videoId = extractVideoID(url);
    if(videoId) {
        document.getElementById('videoContainer').style.backgroundImage = "url('" + thumbUrl + "')";
        document.getElementById('videoIframe').src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1';
        document.getElementById('videoModal').style.display = 'flex';
    } else {
        window.open(url, '_blank');
    }
}
function closeVideoModal() {
    document.getElementById('videoIframe').src = '';
    document.getElementById('videoModal').style.display = 'none';
}
</script>
@endsection
