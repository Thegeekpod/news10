<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginForm()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (! Auth::user()->is_admin) {
                Auth::logout();

                return back()->withErrors(['email' => 'आपके पास एडमिन पैनल का एक्सेस नहीं है।']);
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'सफलतापूर्वक लॉगिन हो गया! News 10 एडमिन पैनल में आपका स्वागत है।');
        }

        return back()->withErrors([
            'email' => 'ईमेल या पासवर्ड अमान्य है।',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'आप सफलतापूर्वक लॉगआउट हो चुके हैं।');
    }
}
