<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::orderBy('created_at', 'desc')->get();
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.videos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'youtube_url' => 'required',
        ]);

        $data = $request->except('_token', 'thumbnail');
        $data['is_active'] = $request->has('is_active');
        
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('videos', 'public');
        } else {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $request->youtube_url, $match);
            if(isset($match[1])) {
                $data['thumbnail'] = 'https://img.youtube.com/vi/' . $match[1] . '/hqdefault.jpg';
            }
        }

        Video::create($data);
        return redirect()->route('admin.videos.index')->with('success', 'Video added successfully.');
    }

    public function show(Video $video)
    {
        return view('admin.videos.show', compact('video'));
    }

    public function edit(Video $video)
    {
        return view('admin.videos.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required',
            'youtube_url' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'thumbnail');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('videos', 'public');
        } elseif (!$video->thumbnail || $video->youtube_url !== $request->youtube_url) {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $request->youtube_url, $match);
            if(isset($match[1])) {
                $data['thumbnail'] = 'https://img.youtube.com/vi/' . $match[1] . '/hqdefault.jpg';
            }
        }

        $video->update($data);
        return redirect()->route('admin.videos.index')->with('success', 'Video updated successfully.');
    }

    public function destroy(Video $video)
    {
        $video->delete();
        return redirect()->route('admin.videos.index')->with('success', 'Video deleted successfully.');
    }
}
