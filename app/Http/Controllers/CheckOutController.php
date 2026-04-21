<?php

namespace App\Http\Controllers;

use App\Actions\Event\CheckOutStudentAction;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckOutController extends Controller
{
    public function __construct(private CheckOutStudentAction $checkOut) {}

    public function store(Request $request, Event $event): RedirectResponse
    {
        abort_if(! auth()->user()->can('checkout.validate'), 403);

        $request->validate([
            'student_id' => ['required', 'exists:users,id'],
        ]);

        $student = User::findOrFail($request->student_id);

        $this->checkOut->execute($event, $student);

        return back()->with('success', "Check-out confirmed for {$student->name}.");
    }
}
