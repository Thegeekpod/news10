@extends('layouts.admin')

@section('title', 'Manage Videos')
@section('page_title', 'Video News')

@section('content')

<div class="card">
    <div class="card-body">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h5 class="mb-0">All Videos</h5>
            <a href="{{ route('admin.videos.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Video</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="80">Thumbnail</th>
                        <th>Title</th>
                        <th>Duration</th>
                        <th>Views/Time</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($videos as $video)
                        <tr>
                            <td>
                                @if($video->thumbnail)
                                    <img src="{{ str_starts_with($video->thumbnail, 'http') ? $video->thumbnail : asset('storage/'.$video->thumbnail) }}" alt="Thumb" style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <div style="width: 80px; height: 50px; background: #eee; border-radius: 4px;"></div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $video->title }}</strong><br>
                                <a href="{{ $video->youtube_url }}" target="_blank" style="font-size: 12px;">Watch on YouTube <i class="fa-solid fa-external-link-alt"></i></a>
                            </td>
                            <td>{{ $video->duration ?? 'N/A' }}</td>
                            <td>
                                <span style="font-size:12px; color:#555;"><i class="fa-solid fa-eye"></i> {{ $video->views_text ?? '-' }}</span><br>
                                <span style="font-size:12px; color:#555;"><i class="fa-regular fa-clock"></i> {{ $video->time_ago_text ?? '-' }}</span>
                            </td>
                            <td>
                                @if($video->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.videos.edit', $video->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-edit"></i></a>
                                <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this video?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No videos found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
