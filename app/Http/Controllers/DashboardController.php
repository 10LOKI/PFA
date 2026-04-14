<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $role = auth()->user()->role;

        return match ($role) {
            'partner' => view('dashboard.partner'),
            'admin' => view('dashboard.admin'),
            default => view('dashboard.student'),
        };
    }

    public function student(): View
    {
        return view('dashboard.student');
    }

    public function partner(): View
    {
        return view('dashboard.partner');
    }

    public function admin(): View
    {
        return view('dashboard.admin');
    }
}
