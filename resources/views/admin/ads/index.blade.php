@extends('layouts.admin')

@section('title', 'विज्ञापन प्रबंधन')
@section('page_title', 'विज्ञापन प्रबंधक (Manage Advertisements)')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <p style="color: #64748b; font-size: 14px; margin: 0;">वेबसाइट के अलग-अलग स्लॉट पर दिखने वाले इमेज बैनर या कस्टम ऐडसेंस कोड प्रबंधित करें।</p>
    <a href="{{ route('admin.ads.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> नया विज्ञापन जोड़ें (New Ad)
    </a>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>शीर्षक</th>
                        <th>प्लेसमेंट स्लॉट (Placement)</th>
                        <th>प्रकार (Type)</th>
                        <th>प्रीव्यू (Preview)</th>
                        <th>स्थिति (Status)</th>
                        <th style="text-align: right;">कार्रवाई</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $placementLabels = [
                            'header_banner' => 'हेडर बैनर (Header 728x90)',
                            'home_middle' => 'होमपेज मिडिल (Home 970x120)',
                            'sidebar_top' => 'साइडबार टॉप (Sidebar 300x250)',
                            'sidebar_bottom' => 'साइडबार बॉटम (Sidebar 300x400)',
                            'detail_top' => 'आर्टिकल इनसाइड (Detail 728x120)',
                            'detail_sidebar' => 'डिटेल साइडबार (Detail 300x250)',
                            'detail_bottom' => 'डिटेल बॉटम (Detail 728x90)',
                            'category_sidebar' => 'कैटेगरी साइडबार (Category 300x250)',
                        ];
                    @endphp
                    @forelse($ads as $ad)
                        <tr>
                            <td>
                                <strong style="color: #0f172a; font-size: 14.5px;">{{ $ad->title }}</strong>
                                @if($ad->target_url)
                                    <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                                        <a href="{{ $ad->target_url }}" target="_blank" style="color: #2563eb; text-decoration: none;">
                                            <i class="fa-solid fa-link"></i> {{ Str::limit($ad->target_url, 40) }}
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info" style="font-size: 11px;">
                                    {{ $placementLabels[$ad->placement] ?? $ad->placement }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $ad->type === 'image' ? 'badge-warning' : 'badge-purple' }}">
                                    {{ $ad->type === 'image' ? 'इमेज बैनर' : 'कस्टम कोड / AdSense' }}
                                </span>
                            </td>
                            <td>
                                @if($ad->type === 'image' && $ad->image_url)
                                    <img src="{{ $ad->image_url }}" alt="" style="height: 40px; max-width: 120px; object-fit: contain; border-radius: 4px; border: 1px solid var(--border-color); background: #f8fafc;" />
                                @else
                                    <code style="font-size: 11px; background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">&lt;HTML Code&gt;</code>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.ads.toggleStatus', $ad->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="badge {{ $ad->is_active ? 'badge-success' : 'badge-danger' }}" style="border: none; cursor: pointer;">
                                        {{ $ad->is_active ? 'सक्रिय (Active)' : 'निष्क्रिय' }}
                                    </button>
                                </form>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    <a href="{{ route('admin.ads.edit', $ad->id) }}" class="btn btn-primary btn-sm" title="एडिट करें">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.ads.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('क्या आप वाकई इस विज्ञापन को हटाना चाहते हैं?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="हटाएं">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #64748b; padding: 40px;">
                                कोई विज्ञापन नहीं मिला। ऊपर दिए गए बटन से नया विज्ञापन जोड़ें।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($ads->hasPages())
        <div style="padding: 14px 20px; border-top: 1px solid var(--border-color);">
            {{ $ads->links() }}
        </div>
    @endif
</div>

@endsection
