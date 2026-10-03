@extends('layouts.admin')

@section('title', 'वेबसाइट सेटिंग्स')
@section('page_title', 'वेबसाइट सेटिंग्स (Site Settings)')

@section('content')

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;" class="settings-grid">
        
        <!-- General Info Card -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><i class="fa-solid fa-globe"></i> सामान्य सेटिंग्स (General Info)</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label" for="site_name">वेबसाइट का नाम (Site Name)</label>
                    <input type="text" id="site_name" name="site_name" class="form-control" value="{{ old('site_name', $settings['site_name'] ?? 'News 10') }}" required />
                </div>

                <div class="form-group">
                    <label class="form-label" for="site_tagline">टैगलाइन (Tagline)</label>
                    <input type="text" id="site_tagline" name="site_tagline" class="form-control" value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="site_description">साइट का विवरण (Site Description for SEO)</label>
                    <textarea id="site_description" name="site_description" class="form-control" rows="3">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="epaper_url">ई-पेपर यूआरएल (E-Paper URL)</label>
                    <input type="text" id="epaper_url" name="epaper_url" class="form-control" value="{{ old('epaper_url', $settings['epaper_url'] ?? '#') }}" />
                </div>
            </div>
        </div>

        <!-- Social Media Links -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><i class="fa-solid fa-share-nodes"></i> सोशल मीडिया लिंक्स (Social Media)</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label" for="social_facebook"><i class="fa-brands fa-facebook-f" style="color: #1877f2;"></i> Facebook URL</label>
                    <input type="url" id="social_facebook" name="social_facebook" class="form-control" value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="social_twitter"><i class="fa-brands fa-x-twitter"></i> X (Twitter) URL</label>
                    <input type="url" id="social_twitter" name="social_twitter" class="form-control" value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="social_youtube"><i class="fa-brands fa-youtube" style="color: #ff0000;"></i> YouTube URL</label>
                    <input type="url" id="social_youtube" name="social_youtube" class="form-control" value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="social_instagram"><i class="fa-brands fa-instagram" style="color: #e4405f;"></i> Instagram URL</label>
                    <input type="url" id="social_instagram" name="social_instagram" class="form-control" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="social_whatsapp"><i class="fa-brands fa-whatsapp" style="color: #25d366;"></i> WhatsApp Channel / Number</label>
                    <input type="text" id="social_whatsapp" name="social_whatsapp" class="form-control" value="{{ old('social_whatsapp', $settings['social_whatsapp'] ?? '') }}" />
                </div>
            </div>
        </div>

        <!-- Contact & Footer -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><i class="fa-solid fa-address-book"></i> संपर्क एवं फुटर (Contact & Footer)</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label" for="contact_email">संपर्क ईमेल (Contact Email)</label>
                    <input type="email" id="contact_email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="contact_phone">फोन नंबर (Phone)</label>
                    <input type="text" id="contact_phone" name="contact_phone" class="form-control" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="footer_about">फुटर परिचय (Footer About Text)</label>
                    <textarea id="footer_about" name="footer_about" class="form-control" rows="3">{{ old('footer_about', $settings['footer_about'] ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="copyright_text">कॉपीराइट टेक्स्ट (Copyright Text)</label>
                    <input type="text" id="copyright_text" name="copyright_text" class="form-control" value="{{ old('copyright_text', $settings['copyright_text'] ?? '') }}" />
                </div>
            </div>
        </div>

        <!-- Custom Scripts & Tracking -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><i class="fa-solid fa-code"></i> कस्टम कोड एवं स्क्रिप्ट्स (Header/Footer Scripts)</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label" for="header_script">हेडर स्क्रिप्ट्स (&lt;head&gt; Code - Google Analytics / Meta Pixel)</label>
                    <textarea id="header_script" name="header_script" class="form-control" rows="4" placeholder="<script>...</script>">{{ old('header_script', $settings['header_script'] ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="footer_script">फुटर स्क्रिप्ट्स (Before &lt;/body&gt; Code)</label>
                    <textarea id="footer_script" name="footer_script" class="form-control" rows="4" placeholder="<script>...</script>">{{ old('footer_script', $settings['footer_script'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

    </div>

    <div style="margin-top: 10px; margin-bottom: 40px; text-align: right;">
        <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 15px;">
            <i class="fa-solid fa-floppy-disk"></i> सभी सेटिंग्स सहेजें (Save All Settings)
        </button>
    </div>
</form>

<style>
@media (max-width: 900px) {
    .settings-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
