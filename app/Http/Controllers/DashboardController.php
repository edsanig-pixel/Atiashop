<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $totalUsers = \App\Models\User::count();
    $totalProducts = \App\Models\Product::count();
    $lowStock = \App\Models\Product::where('stock', '<', 5)->count();

    return view('dashboard', compact(
        'totalUsers',
        'totalProducts',
        'lowStock'
    ));
}
}


