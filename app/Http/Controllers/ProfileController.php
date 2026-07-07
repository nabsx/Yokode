<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserProgress;
use App\Models\UserAchievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil
     */
    public function index()
    {
        $user = Auth::user();
        
        // Statistik tambahan
        $totalCorrectAnswers = UserAnswer::where('user_id', $user->id)
            ->where('is_correct', true)
            ->count();
        
        $totalWrongAnswers = UserAnswer::where('user_id', $user->id)
            ->where('is_correct', false)
            ->count();
        
        $accuracy = $totalCorrectAnswers + $totalWrongAnswers > 0 
            ? round(($totalCorrectAnswers / ($totalCorrectAnswers + $totalWrongAnswers)) * 100, 1)
            : 0;
        
        $recentActivities = UserProgress::where('user_id', $user->id)
            ->where('completed', true)
            ->with('lesson')
            ->orderBy('completed_at', 'desc')
            ->limit(10)
            ->get();
        
        $achievements = $user->achievements()->orderBy('user_achievements.earned_at', 'desc')->get();
        
        $totalAchievements = $achievements->count();
        $totalPossibleAchievements = \App\Models\Achievement::count();
        
        return view('profile.index', compact(
            'user',
            'totalCorrectAnswers',
            'totalWrongAnswers',
            'accuracy',
            'recentActivities',
            'achievements',
            'totalAchievements',
            'totalPossibleAchievements'
        ));
    }
    
    /**
     * Menampilkan form edit profil
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    
    /**
     * Update profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        
        return redirect()->route('profile')
            ->with('success', 'Profil berhasil diperbarui!');
    }
    
    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah!');
        }
        
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return redirect()->route('profile')
            ->with('success', 'Password berhasil diubah!');
    }
    
    /**
     * Menampilkan halaman statistik lengkap
     */
    public function statistics()
    {
        $user = Auth::user();
        
        // Statistik per hari (30 hari terakhir)
        $dailyStats = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $expGained = \App\Models\UserProgress::where('user_id', $user->id)
                ->whereDate('completed_at', $date)
                ->with('lesson')
                ->get()
                ->sum(function ($progress) {
                    return $progress->lesson->exp_reward ?? 0;
                });
            
            $dailyStats[] = [
                'date' => $date,
                'exp' => $expGained,
            ];
        }
        
        // Statistik per league
        $leagueStats = [
            'Bronze' => 0,
            'Silver' => 0,
            'Gold' => 0,
            'Sapphire' => 0,
            'Ruby' => 0,
            'Pearl' => 0,
            'Obsidian' => 0,
            'Legend' => 0,
        ];
        
        return view('profile.statistics', compact('user', 'dailyStats', 'leagueStats'));
    }
}