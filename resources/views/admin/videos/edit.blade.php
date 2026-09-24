@extends('layouts.admin')

@section('title', 'Edit Video')
@section('page_title', 'Edit Video')

@section('content')

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-body">
        <form action="{{ route('admin.videos.update', $video->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Video Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $video->title) }}" required>
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">YouTube URL <span class="text-danger">*</span></label>
                <input type="url" name="youtube_url" class="form-control @error('youtube_url') is-invalid @enderror" value="{{ old('youtube_url', $video->youtube_url) }}" required>
                @error('youtube_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Duration (e.g. 4:32)</label>
                    <input type="text" name="duration" class="form-control" value="{{ old('duration', $video->duration) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Views Text (e.g. 1.2M views)</label>
                    <input type="text" name="views_text" class="form-control" value="{{ old('views_text', $video->views_text) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Time Ago (e.g. 2 दिन पहले)</label>
                    <input type="text" name="time_ago_text" class="form-control" value="{{ old('time_ago_text', $video->time_ago_text) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Custom Thumbnail (Optional)</label>
                @if($video->thumbnail)
                    <div class="mb-2">
                        <img src="{{ str_starts_with($video->thumbnail, 'http') ? $video->thumbnail : asset('storage/'.$video->thumbnail) }}" style="height: 100px; border-radius: 6px;">
                    </div>
                @endif
                <input type="file" name="thumbnail" class="form-control" accept="image/*">
            </div>
            
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ $video->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Active (Show on Frontend)</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Video</button>
            <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection
