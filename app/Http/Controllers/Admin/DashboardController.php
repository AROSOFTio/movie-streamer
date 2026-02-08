<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Series;
use App\Models\Episode;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'active_subs' => Subscription::active()->count(),
            'movies' => Movie::count(),
            'series' => Series::count(),
            'episodes' => Episode::count(),
            'revenue' => Payment::sum('amount'),
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
        ]);
    }
}
