@extends('layouts.admin')

@section('title', 'टैग्स प्रबंधन')
@section('page_title', 'टैग्स (Manage News Tags)')

@section('content')

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;" class="tag-admin-grid">
    
    <!-- Left: Add Tag Form -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><i class="fa-solid fa-plus-circle"></i> नया टैग जोड़ें (Add Tag)</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.tags.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="name">टैग का नाम (Tag Name) <span style="color: #dc2626;">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="जैसे: नरेंद्र मोदी, चंद्रयान-4, आईपीएल" required value="{{ old('name') }}" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="slug">कस्टम स्लग (Slug - Optional)</label>
                    <input type="text" id="slug" name="slug" class="form-control" placeholder="narendra-modi" value="{{ old('slug') }}" />
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    <i class="fa-solid fa-plus"></i> टैग सेव करें
                </button>
            </form>
        </div>
    </div>

    <!-- Right: Tags Table with Search -->
    <div class="card">
        <div class="card-header" style="flex-wrap: wrap; gap: 10px;">
            <h4 class="card-title"><i class="fa-solid fa-tags"></i> सभी टैग्स</h4>
            <form action="{{ route('admin.tags.index') }}" method="GET" style="display: flex; gap: 6px;">
                <input type="text" name="search" class="form-control" placeholder="टैग खोजें..." value="{{ request('search') }}" style="padding: 6px 10px; font-size: 13px;" />
                <button type="submit" class="btn btn-secondary btn-sm"><i class="fa-solid fa-search"></i></button>
            </form>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>टैग नाम</th>
                            <th>स्लग</th>
                            <th>जुड़ी खबरें</th>
                            <th style="text-align: right;">कार्रवाई</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $tag)
                            <tr>
                                <td>
                                    <strong style="color: #0f172a;"># {{ $tag->name }}</strong>
                                </td>
                                <td><code>{{ $tag->slug }}</code></td>
                                <td>
                                    <span class="badge badge-info">{{ $tag->posts_count }} खबरें</span>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                        <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" onsubmit="return confirm('क्या आप वाकई इस टैग को हटाना चाहते हैं?');" style="display: inline;">
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
                                <td colspan="4" style="text-align: center; color: #64748b; padding: 30px;">कोई टैग नहीं मिला।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($tags->hasPages())
            <div style="padding: 14px 20px; border-top: 1px solid var(--border-color);">
                {{ $tags->links() }}
            </div>
        @endif
    </div>

</div>

<style>
@media (max-width: 900px) {
    .tag-admin-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
