@extends('layouts.admin')

@section('title', 'Manage News Posts')
@section('page_title', 'All News Posts')

@section('content')

<!-- ── Action & Filter Header ── -->
<div class="card">
    <div class="card-body" style="padding: 16px 20px;">
        <form action="{{ route('admin.posts.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
            <div style="display: flex; gap: 10px; flex: 1; min-width: 280px; max-width: 600px;">
                <input type="text" name="search" class="form-control" placeholder="Search by title or keyword..." value="{{ request('search') }}" />
                <select name="category_id" class="form-select" style="max-width: 180px;">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select" style="max-width: 140px;">
                    <option value="">All Statuses</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-filter"></i> Filter</button>
            </div>

            <div>
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Add New Post
                </a>
            </div>
        </form>
    </div>
</div>

<!-- ── Posts Table ── -->
<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Flags</th>
                        <th>Views</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>{{ $loop->iteration + ($posts->currentPage() - 1) * $posts->perPage() }}</td>
                            <td>
                                <img src="{{ $post->image_url }}" alt="" style="width: 60px; height: 42px; object-fit: cover; border-radius: 4px;" />
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a; max-width: 320px; line-height: 1.35;">
                                    {{ $post->title }}
                                </div>
                                <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">
                                    Author: {{ $post->author ? $post->author->name : 'N/A' }}
                                </div>
                            </td>
                            <td>
                                @if($post->category)
                                    <span class="badge" style="background: {{ $post->category->color }}20; color: {{ $post->category->color }};">
                                        {{ $post->category->name }}
                                    </span>
                                @else
                                    <span class="badge badge-warning">Uncategorized</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                    @if($post->is_breaking)
                                        <span class="badge badge-danger" title="Breaking News">⚡ Breaking</span>
                                    @endif
                                    @if($post->is_featured)
                                        <span class="badge badge-warning" title="Featured">⭐ Featured</span>
                                    @endif
                                    @if($post->is_trending)
                                        <span class="badge badge-info" title="Trending">🔥 Trending</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span style="font-weight: 700; font-size: 13px;">{{ number_format($post->views_count) }}</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.posts.toggleStatus', $post->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="badge {{ $post->status === 'published' ? 'badge-success' : 'badge-warning' }}" style="border: none; cursor: pointer;">
                                        {{ $post->status === 'published' ? 'Published' : 'Draft' }}
                                    </button>
                                </form>
                            </td>
                            <td style="font-size: 12px; color: #64748b; white-space: nowrap;">
                                {{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    <a href="{{ route('post.show', $post->slug) }}" target="_blank" class="btn btn-outline btn-sm" title="View on Frontend">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; color: #64748b; padding: 40px;">
                                No news posts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($posts->hasPages())
        <div style="padding: 16px 20px; border-top: 1px solid var(--border-color);">
            {{ $posts->links() }}
        </div>
    @endif
</div>

@endsection
