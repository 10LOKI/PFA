<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KycController extends Controller
{
    public function index(): View
    {
        abort_if(! auth()->user()->can('partner.kyc-approve'), 403);

        $pending = Partner::where('kyc_status', 'pending')
                          ->with('user')
                          ->latest()
                          ->paginate(20);

        return view('admin.kyc.index', compact('pending'));
    }

    public function approve(Partner $partner): RedirectResponse
    {
        abort_if(! auth()->user()->can('partner.kyc-approve'), 403);

        $partner->update(['kyc_status' => 'approved']);
        $partner->user->update(['kyc_verified' => true]);
        $partner->user->givePermissionTo('event.approve');

        return back()->with('success', "{$partner->company_name} approved.");
    }

    public function reject(Partner $partner): RedirectResponse
    {
        abort_if(! auth()->user()->can('partner.kyc-reject'), 403);

        $partner->update(['kyc_status' => 'rejected']);

        return back()->with('success', "{$partner->company_name} rejected.");
    }
}
