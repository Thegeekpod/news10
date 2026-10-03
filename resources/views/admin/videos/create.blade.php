@extends('layouts.admin')

@section('title', 'Add Video')
@section('page_title', 'नया वीडियो जोड़ें (Add Video)')

@section('content')

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h4 class="card-title"><i class="fa-brands fa-youtube" style="color: #e60000;"></i> वीडियो विवरण (Video Information)</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label class="form-label" for="title">वीडियो शीर्षक (Title) <span style="color: #e60000;">*</span></label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" placeholder="Enter news video headline..." required>
                @error('title') <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label" for="youtube_url">YouTube URL <span style="color: #e60000;">*</span></label>
                <input type="url" id="youtube_url" name="youtube_url" class="form-control" value="{{ old('youtube_url') }}" placeholder="https://www.youtube.com/watch?v=..." required>
                <small style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">YouTube URL दर्ज करें (जैसे: https://www.youtube.com/watch?v=XYZ123)</small>
                @error('youtube_url') <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 18px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">अवधि (Duration, e.g. 4:32)</label>
                    <input type="text" name="duration" class="form-control" value="{{ old('duration') }}" placeholder="04:25">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">व्यूज (Views, e.g. 1.2M views)</label>
                    <input type="text" name="views_text" class="form-control" value="{{ old('views_text') }}" placeholder="15K views">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">समय (Time Ago, e.g. 2 घंटे पहले)</label>
                    <input type="text" name="time_ago_text" class="form-control" value="{{ old('time_ago_text') }}" placeholder="2 घंटे पहले">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">कस्टम थंबनेल (Custom Thumbnail - Optional)</label>
                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                <small style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">यदि खाली छोड़ दिया जाए, तो YouTube से थंबनेल अपने आप ले लिया जाएगा।</small>
            </div>
            
            <div class="form-group" style="margin-top: 12px; margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; font-weight: 600; color: #334155;">
                    <input type="checkbox" name="is_active" value="1" checked style="width: 18px; height: 18px; accent-color: var(--primary);">
                    सक्रिय रखें (Show on Website)
                </label>
            </div>

            <div style="display: flex; gap: 10px; align-items: center;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> सेव करें (Save Video)</button>
                <a href="{{ route('admin.videos.index') }}" class="btn btn-outline">रद्द करें (Cancel)</a>
            </div>
        </form>
    </div>
</div>

@endsection
