<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Ambil top 50 user dengan EXP tertinggi
        $leaderboard = User::orderBy('total_exp', 'desc')
            ->limit(50)
            ->get(['id', 'name', 'total_exp']);
        
        // Tambahkan level ke setiap user
        foreach ($leaderboard as $user) {
            $user->level = $user->level;
        }
        
        // Cari rank user yang sedang login
        $currentUser = Auth::user();
        $userRank = User::where('total_exp', '>', $currentUser->total_exp)->count() + 1;
        
        return view('leaderboard', compact('leaderboard', 'userRank'));
    }
}