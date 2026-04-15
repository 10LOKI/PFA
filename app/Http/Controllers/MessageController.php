<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $conversations = auth()->user()
            ->conversations()
            ->with('participants')
            ->with(['messages' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->latest('updated_at')
            ->get();

        return view('messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation): View
    {
        if (! $conversation->isParticipant(auth()->user())) {
            abort(403);
        }

        $conversation->load(['participants', 'messages' => function ($q) {
            $q->oldest();
        }]);

        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.show', compact('conversation'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id|different:auth()->id',
            'body' => 'required|string|max:1000',
        ]);

        $conversation = Conversation::whereHas('participants', function ($q) {
            $q->where('user_id', auth()->id());
        })->whereHas('participants', function ($q) use ($request) {
            $q->where('user_id', $request->recipient_id);
        })->first();

        if (! $conversation) {
            $conversation = Conversation::create();
            $conversation->participants()->attach([auth()->id(), $request->recipient_id]);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'body' => $request->body,
        ]);

        event(new MessageSent($message));

        $conversation->touch();

        return redirect()->route('messages.show', $conversation);
    }

    public function start(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|different:auth()->id',
        ]);

        $conversation = Conversation::whereHas('participants', function ($q) {
            $q->where('user_id', auth()->id());
        })->whereHas('participants', function ($q) use ($request) {
            $q->where('user_id', $request->user_id);
        })->first();

        if (! $conversation) {
            $conversation = Conversation::create();
            $conversation->participants()->attach([auth()->id(), $request->user_id]);
        }

        return redirect()->route('messages.show', $conversation);
    }
}
