<?php

namespace App\Http\Controllers;

use App\Actions\Reward\RedeemRewardAction;
use App\Models\Reward;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RewardController extends Controller
{
    public function __construct(private RedeemRewardAction $redeem) {}

    public function index(): View
    {
        $this->authorize('viewAny', Reward::class);

        $user = auth()->user();
        $query = Reward::query();

        // Students see only available rewards (marketplace)
        if ($user->isStudent()) {
            $query->where('is_active', true)
                ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
                ->where(fn ($q) => $q->whereNull('stock')->orWhere('stock', '>', 0));
        }
        // Partners see only their own created rewards (management view)
        elseif ($user->isPartner()) {
            $query->where('partner_id', $user->id);
        }
        // Admins see all rewards (no additional filter)

        $rewards = $query->latest()->paginate(12);

        return view('rewards.index', compact('rewards'));
    }

    public function redeem(Reward $reward): RedirectResponse
    {
        $this->authorize('redeem', $reward);

        $this->redeem->execute(auth()->user(), $reward);

        return back()->with('success', "Reward \"{$reward->title}\" redeemed successfully.");
    }

    public function create(): View
    {
        $this->authorize('create', Reward::class);

        return view('rewards.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Reward::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
            'points_cost' => 'required|integer|min:1',
            'stock' => 'nullable|integer|min:0',
            'min_grade' => 'in:novice,pilier,ambassadeur',
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $data = $validated;
        $data['partner_id'] = auth()->id();
        $data['is_premium'] = $request->boolean('is_premium', false);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rewards', 'public');
        }

        Reward::create($data);

        return redirect()->route('dashboard.partner')->with('success', 'Reward created successfully.');
    }

    public function edit(Reward $reward): View
    {
        $this->authorize('update', $reward);

        return view('rewards.edit', compact('reward'));
    }

    public function update(Request $request, Reward $reward): RedirectResponse
    {
        $this->authorize('update', $reward);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
            'points_cost' => 'required|integer|min:1',
            'stock' => 'nullable|integer|min:0',
            'min_grade' => 'required|in:novice,pilier,ambassadeur',
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $data = $validated;
        $data['is_premium'] = $request->boolean('is_premium', false);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rewards', 'public');
        }

        $reward->update($data);

        return redirect()->route('dashboard.partner')->with('success', 'Reward updated successfully.');
    }

    public function destroy(Reward $reward): RedirectResponse
    {
        $this->authorize('delete', $reward);

        $reward->delete();

        return redirect()->route('dashboard.partner')->with('success', 'Reward deleted successfully.');
    }
}
