<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function publik()
    {
        return view('dashboard.publik');
    }

    public function index(Request $request)
    {
        return match ($request->user()->role) {
            'admin' => redirect()->route('dashboard.admin'),
            'operator' => redirect()->route('dashboard.operator'),
            default => redirect()->route('dashboard.warga'),
        };
    }

    public function admin()
    {
        return view('dashboard.admin', ['user' => auth()->user()]);
    }

    public function operator()
    {
        return view('dashboard.operator', ['user' => auth()->user()]);
    }

    public function warga()
    {
        return view('dashboard.warga', ['user' => auth()->user()]);
    }
}
