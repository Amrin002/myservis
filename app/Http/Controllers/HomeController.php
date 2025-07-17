<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page with admin and tuser selection
     */
    public function index(): View
    {
        return view('home.index');
    }

    /**
     * Redirect to admin login
     */
    public function admin()
    {
        return redirect()->route('dashboard');
    }

    /**
     * Redirect to tuser login/dashboard
     */
    public function tuser()
    {
        // Jika ada route khusus untuk tuser login, bisa disesuaikan
        return redirect()->route('tuser.tuser.dashboard');
    }
}
