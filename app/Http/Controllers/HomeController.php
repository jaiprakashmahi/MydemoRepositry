<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application welcome page.
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Show the user dashboard after login.
     */
    public function dashboard()
    {
        // Replace with real DB queries once Property model exists
        $stats = [
            'total_properties' => 248,
            'total_views'      => 12540,
            'new_inquiries'    => 186,
            'closed_deals'     => 32,
        ];

        return view('dashboard', compact('stats'));
    }
}
