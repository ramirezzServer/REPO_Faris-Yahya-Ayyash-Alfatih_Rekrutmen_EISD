<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function publik()
    {
        return view('dashboard.publik');
    }
}
