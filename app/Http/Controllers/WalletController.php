<?php

namespace App\Http\Controllers;

use App\Models\PointsTransaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = PointsTransaction::query()
            ->where('user_id', $user->id)
            ->with('source')
            ->orderBy('created_at', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(15)->withQueryString();

        $totalEarned = $user->pointsTransactions()
            ->where('type', 'earned')
            ->sum('amount');
        $totalSpent = $user->pointsTransactions()
            ->whereIn('type', ['spent', 'burned'])
            ->sum('amount');

        return view('wallet.index', compact('transactions', 'totalEarned', 'totalSpent'));
    }
}
