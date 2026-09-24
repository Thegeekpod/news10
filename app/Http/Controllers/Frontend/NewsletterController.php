<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        Subscriber::firstOrCreate(
            ['email' => $request->email],
            ['is_active' => true]
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'धन्यवाद! आप हमारे न्यूज़लेटर से सफलतापूर्वक जुड़ गए हैं।'
            ]);
        }

        return back()->with('success', 'धन्यवाद! आप हमारे न्यूज़लेटर से सफलतापूर्वक जुड़ गए हैं।');
    }
}
