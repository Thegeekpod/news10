<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = \App\Models\Video::where('is_active', true)->orderBy('created_at', 'desc')->paginate(12);
        return view('frontend.videos.index', compact('videos'));
    }
}
