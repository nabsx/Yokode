<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GamificationController extends Controller
{
    /**
     * Get user's current level and EXP
     */
    public function getUserStats()
    {
        $user = Auth::user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'name' => $user->name,
                'level' => $user->level,
                'total_exp' => $user->total_exp,
                'exp_to_next_level' => $user->exp_to_next_level,
                'level_progress' => $user->level_progress,
                'is_premium' => $user->is_premium_active,
            ]
        ]);
    }
    
    /**
     * Get leaderboard data
     */
    public function getLeaderboard()
    {
        $users = User::orderBy('total_exp', 'desc')
            ->limit(50)
            ->get(['id', 'name', 'total_exp']);
        
        foreach ($users as $user) {
            $user->level = $user->level;
        }
        
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }
    
    /**
     * Get user rank
     */
    public function getUserRank()
    {
        $user = Auth::user();
        $rank = User::where('total_exp', '>', $user->total_exp)->count() + 1;
        
        return response()->json([
            'success' => true,
            'rank' => $rank
        ]);
    }
}