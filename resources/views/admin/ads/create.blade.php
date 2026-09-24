@extends('layouts.admin')

@section('title', 'नया विज्ञापन जोड़ें')
@section('page_title', 'नया विज्ञापन जोड़ें (Create Advertisement)')

@section('content')

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h4 class="card-title"><i class="fa-solid fa-rectangle-ad"></i> विज्ञापन विवरण (Ad Details)</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label" for="title">विज्ञापन का शीर्षक (Campaign Title) <span style="color: #dc2626;">*</span></label>
                <input type="text" id="title" name="title" class="form-control" placeholder="जैसे: हेडर बैनर - कंपनी X" value="{{ old('title') }}" required />
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;" class="ad-two-cols">
                <div class="form-group">
                    <label class="form-label" for="placement">प्लेसमेंट स्लॉट (Ad Placement Slot) <span style="color: #dc2626;">*</span></label>
                    <select name="placement" id="placement" class="form-select" required>
                        @foreach($placements as $key => $label)
                            <option value="{{ $key }}" {{ old('placement') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="type">विज्ञापन का प्रकार (Ad Type) <span style="color: #dc2626;">*</span></label>
                    <select name="type" id="adType" class="form-select" onchange="toggleAdType(this.value)">
                        <option value="image" {{ old('type', 'image') === 'image' ? 'selected' : '' }}>इमेज बैनर (Banner Image + URL)</option>
                        <option value="code" {{ old('type') === 'code' ? 'selected' : '' }}>कस्टम कोड (Google AdSense / HTML Script)</option>
                    </select>
                </div>
            </div>

            <!-- Image Fields -->
            <div id="imageFieldsSection" style="border: 1px dashed var(--border-color); padding: 18px; border-radius: 8px; margin-bottom: 18px; background: #f8fafc;">
                <div class="form-group">
                    <label class="form-label">बैनर फोटो अपलोड करें (Upload Image)</label>
                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewAdFile(event)" />
                </div>

                <div class="form-group">
                    <label class="form-label">या इमेज URL दर्ज करें (Image URL)</label>
                    <input type="url" name="image_url" class="form-control" placeholder="https://..." value="{{ old('image_url') }}" oninput="previewAdUrl(this.value)" />
                </div>

                <div id="adPreviewContainer" style="margin-top: 10px; display: none;">
                    <label class="form-label">प्रीव्यू (Preview):</label>
                    <img id="adPreview" src="" alt="Ad Preview" style="max-height: 100px; max-width: 100%; border-radius: 4px;" />
                </div>

                <div class="form-group" style="margin-top: 14px; margin-bottom: 0;">
                    <label class="form-label" for="target_url">टारगेट यूआरएल (Click Destination Link)</label>
                    <input type="url" id="target_url" name="target_url" class="form-control" placeholder="https://example.com/landing-page" value="{{ old('target_url') }}" />
                </div>
            </div>

            <!-- Code Fields -->
            <div id="codeFieldsSection" style="border: 1px dashed var(--border-color); padding: 18px; border-radius: 8px; margin-bottom: 18px; background: #f8fafc; display: none;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="custom_code">कस्टम HTML / जावास्क्रिप्ट / AdSense कोड</label>
                    <textarea id="custom_code" name="custom_code" class="form-control" rows="5" placeholder="<script async src='...'></script>">{{ old('custom_code') }}</textarea>
                </div>
            </div>

            <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">
                    <input type="checkbox" name="is_active" value="1" checked />
                    <span>सक्रिय रखें (Active immediately)</span>
                </label>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">
                    <i class="fa-solid fa-floppy-disk"></i> विज्ञापन सेव करें
                </button>
                <a href="{{ route('admin.ads.index') }}" class="btn btn-outline">रद्द करें</a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleAdType(val) {
        if (val === 'image') {
            document.getElementById('imageFieldsSection').style.display = 'block';
            document.getElementById('codeFieldsSection').style.display = 'none';
        } else {
            document.getElementById('imageFieldsSection').style.display = 'none';
            document.getElementById('codeFieldsSection').style.display = 'block';
        }
    }

    function previewAdFile(e) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('adPreview');
            output.src = reader.result;
            document.getElementById('adPreviewContainer').style.display = 'block';
        };
        if (e.target.files[0]) reader.readAsDataURL(e.target.files[0]);
    }

    function previewAdUrl(url) {
        if (url) {
            const output = document.getElementById('adPreview');
            output.src = url;
            document.getElementById('adPreviewContainer').style.display = 'block';
        }
    }

    // Initialize toggle state on page load
    document.addEventListener('DOMContentLoaded', () => {
        toggleAdType(document.getElementById('adType').value);
    });
</script>

<style>
@media (max-width: 600px) {
    .ad-two-cols {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
