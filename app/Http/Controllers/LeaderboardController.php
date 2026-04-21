<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    /**
     * Show the national leaderboard (all-time points).
     */
    public function index()
    {
        $users = User::where('role', 'student')
            ->select('users.*', DB::raw('RANK() OVER (ORDER BY points_balance DESC) as rank'))
            ->orderBy('points_balance', 'desc')
            ->limit(100)
            ->get();

        return view('leaderboard.index', compact('users'));
    }

    /**
     * Show the leaderboard for a specific city (all-time points).
     */
    public function city($city)
    {
        $users = User::where('role', 'student')
            ->where('city', 'like', '%'.$city.'%')
            ->select('users.*', DB::raw('RANK() OVER (ORDER BY points_balance DESC) as rank'))
            ->orderBy('points_balance', 'desc')
            ->limit(100)
            ->get();

        return view('leaderboard.index', compact('users', 'city'));
    }

    /**
     * Show the leaderboard for a specific establishment (all-time points).
     */
    public function establishment($establishmentId)
    {
        $users = User::where('role', 'student')
            ->where('establishment_id', $establishmentId)
            ->select('users.*', DB::raw('RANK() OVER (ORDER BY points_balance DESC) as rank'))
            ->orderBy('points_balance', 'desc')
            ->limit(100)
            ->get();

        return view('leaderboard.index', compact('users', 'establishmentId'));
    }

    /**
     * Show the weekly leaderboard (points earned in the current calendar week).
     */
    public function weekly()
    {
        $weekStart = now()->startOfWeek(); // Monday
        $weekEnd = now()->endOfWeek(); // Sunday

        $users = User::where('role', 'student')
            ->select('users.*', DB::raw('
                (SELECT SUM(amount) 
                 FROM points_transactions 
                 WHERE points_transactions.user_id = users.id 
                   AND points_transactions.type = \'earned\'
                   AND points_transactions.created_at BETWEEN ? AND ?
                ) as weekly_points
            ', [$weekStart, $weekEnd]))
            ->having('weekly_points', '>', 0)
            ->orderByDesc('weekly_points')
            ->limit(100)
            ->get();

        // Add rank
        $rankedUsers = $users->map(function ($user, $index) {
            $user->rank = $index + 1;

            return $user;
        });

        return view('leaderboard.index', compact('rankedUsers'));
    }
}
