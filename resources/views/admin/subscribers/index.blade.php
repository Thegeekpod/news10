@extends('layouts.admin')

@section('title', 'सब्सक्राइबर्स')
@section('page_title', 'न्यूज़लेटर सब्सक्राइबर्स (Newsletter Subscribers)')

@section('content')

<div class="card">
    <div class="card-header">
        <h4 class="card-title"><i class="fa-solid fa-envelope-open-text"></i> कुल ईमेल सदस्य (Total Subscribers: {{ $subscribers->total() }})</h4>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>ईमेल पता (Email Address)</th>
                        <th>जुड़ने का दिनांक (Subscribed Date)</th>
                        <th>स्थिति</th>
                        <th style="text-align: right;">कार्रवाई</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscribers as $sub)
                        <tr>
                            <td>{{ $loop->iteration + ($subscribers->currentPage() - 1) * $subscribers->perPage() }}</td>
                            <td>
                                <strong style="color: #0f172a;">{{ $sub->email }}</strong>
                            </td>
                            <td>{{ $sub->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <span class="badge badge-success">सक्रिय (Subscribed)</span>
                            </td>
                            <td style="text-align: right;">
                                <form action="{{ route('admin.subscribers.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('क्या आप वाकई इस सब्सक्राइबर को हटाना चाहते हैं?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i> हटाएं
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #64748b; padding: 30px;">अभी कोई सब्सक्राइबर नहीं है।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($subscribers->hasPages())
        <div style="padding: 14px 20px; border-top: 1px solid var(--border-color);">
            {{ $subscribers->links() }}
        </div>
    @endif
</div>

@endsection
