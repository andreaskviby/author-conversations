<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ConversationController extends Controller
{
    public function show(Conversation $conversation): View
    {
        $conversation->load(['book', 'messages.author', 'messages.user']);
        
        $aiAuthors = $conversation->getParticipantAuthors();
        $humanParticipants = $conversation->getHumanParticipants();
        $messages = $conversation->messages()->with(['author', 'user'])->orderBy('created_at')->get();
        
        return view('conversations.show', compact('conversation', 'aiAuthors', 'humanParticipants', 'messages'));
    }

    public function join(Request $request, Conversation $conversation): RedirectResponse
    {
        $conversation->addHumanParticipant(auth()->user());
        
        return redirect()->route('conversations.show', $conversation)
            ->with('success', 'You have joined the conversation!');
    }

    public function pause(Request $request, Conversation $conversation): RedirectResponse
    {
        $conversation->pauseForHuman(auth()->user());
        
        // Add a system message about the pause
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'sender_type' => 'human_user',
            'content' => '🔄 ' . auth()->user()->name . ' paused the conversation for human input.',
            'type' => 'human_pause',
            'metadata' => ['system_message' => true],
        ]);
        
        return redirect()->route('conversations.show', $conversation)
            ->with('success', 'Conversation paused successfully!');
    }

    public function resume(Request $request, Conversation $conversation): RedirectResponse
    {
        // Add a system message about resuming
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'sender_type' => 'human_user',
            'content' => '▶️ ' . auth()->user()->name . ' resumed the conversation.',
            'type' => 'human_resume',
            'metadata' => ['system_message' => true],
        ]);
        
        $conversation->resume();
        
        return redirect()->route('conversations.show', $conversation)
            ->with('success', 'Conversation resumed successfully!');
    }

    public function addMessage(Request $request, Conversation $conversation): RedirectResponse
    {
        $request->validate([
            'content' => 'required|string|max:10000',
            'type' => 'required|in:human_input,story_contribution,revision_suggestion',
        ]);

        // Check if user is a participant
        if (!in_array(auth()->id(), $conversation->human_participants ?? [])) {
            return redirect()->route('conversations.show', $conversation)
                ->withErrors(['error' => 'You must join the conversation first to send messages.']);
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'sender_type' => 'human_user',
            'content' => $request->content,
            'type' => $request->type,
            'metadata' => [
                'human_author' => auth()->user()->name,
                'timestamp' => now()->toISOString(),
            ],
        ]);

        return redirect()->route('conversations.show', $conversation)
            ->with('success', 'Your message has been added to the conversation!');
    }
}