@extends('layouts.admin')

@section('title', 'Manage Videos')
@section('page_title', 'वीडियो समाचार (Video News)')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div>
        <p style="color: #64748b; font-size: 14px; margin: 0;">वेबसाइट पर प्रदर्शित होने वाले वीडियो समाचार और यूट्यूब लिंक्स प्रबंधित करें।</p>
    </div>
    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> नया वीडियो जोड़ें (Add Video)
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 140px;">थंबनेल (Thumbnail)</th>
                        <th>वीडियो शीर्षक (Title)</th>
                        <th style="width: 110px;">अवधि (Duration)</th>
                        <th style="width: 150px;">व्यूज / समय (Stats)</th>
                        <th style="width: 100px;">स्थिति (Status)</th>
                        <th style="text-align: right; width: 140px;">कार्रवाई (Actions)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($videos as $video)
                        <tr>
                            <td style="color: #94a3b8; font-weight: 600;">{{ $loop->iteration }}</td>
                            <td>
                                <div style="position: relative; width: 110px; height: 64px; border-radius: 6px; overflow: hidden; background: #0f172a; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                                    @if($video->thumbnail)
                                        <img src="{{ str_starts_with($video->thumbnail, 'http') ? $video->thumbnail : asset('storage/'.$video->thumbnail) }}" alt="{{ $video->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 20px;">
                                            <i class="fa-brands fa-youtube"></i>
                                        </div>
                                    @endif
                                    <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.25);">
                                        <i class="fa-solid fa-play" style="color: #ffffff; font-size: 13px; filter: drop-shadow(0 1px 3px rgba(0,0,0,0.6));"></i>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a; font-size: 14.5px; line-height: 1.4; margin-bottom: 4px; max-width: 380px;">
                                    {{ $video->title }}
                                </div>
                                @if($video->youtube_url)
                                    <div style="font-size: 12px; margin-top: 3px;">
                                        <a href="{{ $video->youtube_url }}" target="_blank" style="color: #e60000; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; font-weight: 600;">
                                            <i class="fa-brands fa-youtube"></i> Watch on YouTube <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 10px;"></i>
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($video->duration)
                                    <span style="display: inline-block; padding: 4px 8px; background: #f1f5f9; border-radius: 4px; font-family: monospace; font-size: 12px; font-weight: 600; color: #334155;">
                                        <i class="fa-regular fa-clock" style="font-size: 11px; margin-right: 3px;"></i>{{ $video->duration }}
                                    </span>
                                @else
                                    <span style="color: #94a3b8; font-size: 12px;">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 3px; font-size: 12px; color: #64748b;">
                                    <span><i class="fa-regular fa-eye" style="width: 15px; color: #94a3b8;"></i> {{ $video->views_text ?? '-' }}</span>
                                    <span><i class="fa-regular fa-clock" style="width: 15px; color: #94a3b8;"></i> {{ $video->time_ago_text ?? ($video->created_at ? $video->created_at->diffForHumans() : '-') }}</span>
                                </div>
                            </td>
                            <td>
                                @if($video->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-warning">Inactive</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    @if($video->youtube_url)
                                        <a href="{{ $video->youtube_url }}" target="_blank" class="btn btn-outline btn-sm" title="Watch on YouTube">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.videos.edit', $video->id) }}" class="btn btn-primary btn-sm" title="Edit Video">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this video?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Video">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: #64748b; padding: 48px 20px;">
                                <div style="font-size: 40px; margin-bottom: 10px;">📹</div>
                                <strong style="font-size: 16px; color: #0f172a;">No videos found</strong>
                                <p style="font-size: 13.5px; margin: 6px 0 16px; color: #64748b;">You haven't added any videos yet. Add your first video news.</p>
                                <a href="{{ route('admin.videos.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-plus"></i> Add First Video
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
