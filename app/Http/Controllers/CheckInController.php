<?php

namespace App\Http\Controllers;

use App\Actions\Event\CheckInStudentAction;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function __construct(private CheckInStudentAction $checkIn) {}

    public function store(Request $request, Event $event): RedirectResponse
    {
        abort_if(! auth()->user()->can('checkin.validate'), 403);

        $request->validate([
            'qr_token'  => ['required', 'string'],
            'student_id' => ['required', 'exists:users,id'],
        ]);

        $student = User::findOrFail($request->student_id);

        $this->checkIn->execute($event, $student, $request->qr_token);

        return back()->with('success', "Check-in confirmed for {$student->name}.");
    }
}
