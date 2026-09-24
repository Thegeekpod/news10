@extends('layouts.admin')

@section('title', 'एडमिन प्रोफाइल')
@section('page_title', 'एडमिन प्रोफाइल एवं सुरक्षा (Profile & Security)')

@section('content')

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <h4 class="card-title"><i class="fa-solid fa-user-shield"></i> प्रोफाइल विवरण अपडेट करें</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="name">नाम (Full Name) <span style="color: #dc2626;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required />
            </div>

            <div class="form-group">
                <label class="form-label" for="email">ईमेल पता (Email Address) <span style="color: #dc2626;">*</span></label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required />
            </div>

            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 25px 0;" />

            <h4 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 14px;">पासवर्ड बदलें (Change Password)</h4>
            <p style="font-size: 13px; color: #64748b; margin-bottom: 16px;">यदि आप पासवर्ड नहीं बदलना चाहते तो नीचे दिए गए फ़ील्ड खाली छोड़ें।</p>

            <div class="form-group">
                <label class="form-label" for="current_password">वर्तमान पासवर्ड (Current Password)</label>
                <input type="password" id="current_password" name="current_password" class="form-control" placeholder="••••••••" />
            </div>

            <div class="form-group">
                <label class="form-label" for="new_password">नया पासवर्ड (New Password)</label>
                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="न्यूनतम 6 अक्षर" />
            </div>

            <div class="form-group">
                <label class="form-label" for="new_password_confirmation">नए पासवर्ड की पुष्टि करें (Confirm New Password)</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" placeholder="••••••••" />
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 10px;">
                <i class="fa-solid fa-floppy-disk"></i> प्रोफाइल अपडेट करें
            </button>
        </form>
    </div>
</div>

@endsection
