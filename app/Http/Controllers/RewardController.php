<?php

namespace App\Http\Controllers;

use App\Actions\Reward\RedeemRewardAction;
use App\Models\Reward;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RewardController extends Controller
{
    public function __construct(private RedeemRewardAction $redeem) {}

    public function index(): View
    {
        abort_if(! auth()->user()->can('reward.browse'), 403);

        $rewards = Reward::where('is_active', true)
                         ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
                         ->where(fn($q) => $q->whereNull('stock')->orWhere('stock', '>', 0))
                         ->latest()
                         ->paginate(12);

        return view('rewards.index', compact('rewards'));
    }

    public function redeem(Reward $reward): RedirectResponse
    {
        abort_if(! auth()->user()->can('reward.redeem'), 403);

        $this->redeem->execute(auth()->user(), $reward);

        return back()->with('success', "Reward \"{$reward->title}\" redeemed successfully.");
    }
}
