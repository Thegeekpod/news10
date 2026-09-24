<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BreakingTickerController extends Controller
{
    public function index()
    {
        $tickers = \App\Models\BreakingTicker::latest()->get();
        return view('admin.tickers.index', compact('tickers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ticker_text' => 'required|string|max:500',
            'link_url' => 'nullable|string|max:500',
        ]);

        \App\Models\BreakingTicker::create([
            'ticker_text' => $request->ticker_text,
            'link_url' => $request->link_url,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Ticker added successfully.');
    }

    public function destroy(\App\Models\BreakingTicker $ticker)
    {
        $ticker->delete();
        return redirect()->back()->with('success', 'Ticker deleted successfully.');
    }

    public function toggleStatus(\App\Models\BreakingTicker $ticker)
    {
        $ticker->update(['is_active' => !$ticker->is_active]);
        return redirect()->back()->with('success', 'Ticker status updated.');
    }
}
