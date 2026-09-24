@extends('layouts.admin')

@section('page_title', 'Breaking Tickers')

@section('content')
<div style="display: flex; gap: 24px; flex-wrap: wrap;">
    <!-- Add New Ticker Form -->
    <div style="flex: 1; min-width: 300px; max-width: 400px;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Add New Ticker</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.tickers.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Ticker Text (Bengali) <span style="color:red">*</span></label>
                        <input type="text" name="ticker_text" class="form-control" placeholder="e.g. पश्चिमबंगे तीव्र तापप्रवाह..." required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Link URL (Optional)</label>
                        <input type="text" name="link_url" class="form-control" placeholder="e.g. /article/slug-name">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; background: #6366f1; border-radius: 8px;">
                        + Add Ticker
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Breaking Tickers List -->
    <div style="flex: 2; min-width: 400px;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Breaking Tickers List</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Ticker Text</th>
                                <th>Link</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickers as $ticker)
                                <tr>
                                    <td style="font-weight: 600;">{{ $ticker->ticker_text }}</td>
                                    <td>{{ $ticker->link_url ?? '-' }}</td>
                                    <td>
                                        @if($ticker->is_active)
                                            <span class="badge" style="background: #065f46; color: #34d399; font-size: 10px;">Active</span>
                                        @else
                                            <span class="badge badge-danger" style="font-size: 10px;">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 8px;">
                                            <form action="{{ route('admin.tickers.toggleStatus', $ticker) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm" style="background: #374151; color: #fff;">
                                                    {{ $ticker->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.tickers.destroy', $ticker) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticker?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; color: #64748b;">No breaking tickers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
