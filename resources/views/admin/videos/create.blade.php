@extends('layouts.admin')

@section('title', 'Add Video')
@section('page_title', 'Add New Video')

@section('content')

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-body">
        <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Video Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">YouTube URL <span class="text-danger">*</span></label>
                <input type="url" name="youtube_url" class="form-control @error('youtube_url') is-invalid @enderror" value="{{ old('youtube_url') }}" placeholder="https://www.youtube.com/watch?v=..." required>
                <small class="text-muted">The video ID will be extracted from this link.</small>
                @error('youtube_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Duration (e.g. 4:32)</label>
                    <input type="text" name="duration" class="form-control" value="{{ old('duration') }}" placeholder="Optional">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Views Text (e.g. 1.2M views)</label>
                    <input type="text" name="views_text" class="form-control" value="{{ old('views_text') }}" placeholder="Optional">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Time Ago (e.g. 2 दिन पहले)</label>
                    <input type="text" name="time_ago_text" class="form-control" value="{{ old('time_ago_text') }}" placeholder="Optional">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Custom Thumbnail (Optional)</label>
                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                <small class="text-muted">If left blank, the YouTube thumbnail will be fetched automatically.</small>
            </div>
            
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked>
                    <label class="form-check-label" for="isActive">Active (Show on Frontend)</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Video</button>
            <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection
