@extends('layouts.admin')

@section('title', 'विज्ञापन संपादित करें')
@section('page_title', 'विज्ञापन संपादित करें (Edit Advertisement)')

@section('content')

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h4 class="card-title"><i class="fa-solid fa-rectangle-ad"></i> विज्ञापन विवरण (Edit Ad)</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.ads.update', $ad->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="title">विज्ञापन का शीर्षक (Campaign Title) <span style="color: #dc2626;">*</span></label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $ad->title) }}" required />
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;" class="ad-two-cols">
                <div class="form-group">
                    <label class="form-label" for="placement">प्लेसमेंट स्लॉट (Placement Slot) <span style="color: #dc2626;">*</span></label>
                    <select name="placement" id="placement" class="form-select" required>
                        @foreach($placements as $key => $label)
                            <option value="{{ $key }}" {{ old('placement', $ad->placement) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="type">विज्ञापन का प्रकार (Ad Type) <span style="color: #dc2626;">*</span></label>
                    <select name="type" id="adType" class="form-select" onchange="toggleAdType(this.value)">
                        <option value="image" {{ old('type', $ad->type) === 'image' ? 'selected' : '' }}>इमेज बैनर (Banner Image + URL)</option>
                        <option value="code" {{ old('type', $ad->type) === 'code' ? 'selected' : '' }}>कस्टम कोड (Google AdSense / HTML Script)</option>
                    </select>
                </div>
            </div>

            <!-- Image Fields -->
            <div id="imageFieldsSection" style="border: 1px dashed var(--border-color); padding: 18px; border-radius: 8px; margin-bottom: 18px; background: #f8fafc;">
                @if($ad->image_url)
                    <div style="margin-bottom: 12px;">
                        <label class="form-label">वर्तमान इमेज (Current):</label>
                        <img id="adPreview" src="{{ $ad->image_url }}" alt="Ad Preview" style="max-height: 100px; max-width: 100%; border-radius: 4px;" />
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label">नई इमेज बदलें (Upload New Image)</label>
                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewAdFile(event)" />
                </div>

                <div class="form-group">
                    <label class="form-label">या इमेज URL</label>
                    <input type="url" name="image_url" class="form-control" placeholder="https://..." value="{{ old('image_url', str_starts_with($ad->image_path ?? '', 'http') ? $ad->image_path : '') }}" oninput="previewAdUrl(this.value)" />
                </div>

                <div class="form-group" style="margin-top: 14px; margin-bottom: 0;">
                    <label class="form-label" for="target_url">टारगेट यूआरएल (Click Destination Link)</label>
                    <input type="url" id="target_url" name="target_url" class="form-control" value="{{ old('target_url', $ad->target_url) }}" />
                </div>
            </div>

            <!-- Code Fields -->
            <div id="codeFieldsSection" style="border: 1px dashed var(--border-color); padding: 18px; border-radius: 8px; margin-bottom: 18px; background: #f8fafc; display: none;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="custom_code">कस्टम HTML / जावास्क्रिप्ट / AdSense कोड</label>
                    <textarea id="custom_code" name="custom_code" class="form-control" rows="5">{{ old('custom_code', $ad->custom_code) }}</textarea>
                </div>
            </div>

            <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $ad->is_active) ? 'checked' : '' }} />
                    <span>सक्रिय रखें (Active)</span>
                </label>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">
                    <i class="fa-solid fa-floppy-disk"></i> बदलाव सहेजें
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
            if (output) {
                output.src = reader.result;
            }
        };
        if (e.target.files[0]) reader.readAsDataURL(e.target.files[0]);
    }

    function previewAdUrl(url) {
        if (url) {
            const output = document.getElementById('adPreview');
            if (output) output.src = url;
        }
    }

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
