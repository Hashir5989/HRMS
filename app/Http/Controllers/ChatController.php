<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // All conversations the user is part of, with latest message
        $conversations = Conversation::whereHas('participants', fn($q) => $q->where('users.id', $user->id))
            ->with(['participants', 'latestMessage.user'])
            ->orderByDesc(function ($query) {
                // order by latest message
            })
            ->get()
            ->sortByDesc(fn($c) => $c->latestMessage?->created_at)
            ->values();

        $users = User::where('id', '!=', $user->id)->get();

        return view('chat.index', compact('conversations', 'users'));
    }

    public function show(Conversation $conversation)
    {
        $user = auth()->user();

        // Ensure user is a participant
        if (!$conversation->participants->contains($user->id)) {
            abort(403);
        }

        $conversations = Conversation::whereHas('participants', fn($q) => $q->where('users.id', $user->id))
            ->with(['participants', 'latestMessage.user'])
            ->get()
            ->sortByDesc(fn($c) => $c->latestMessage?->created_at)
            ->values();

        $messages = $conversation->messages()->with('user')->orderBy('created_at')->get();

        // Mark as read
        $conversation->participants()->updateExistingPivot($user->id, ['last_read_at' => now()]);

        $users = User::where('id', '!=', $user->id)->get();

        return view('chat.index', compact('conversations', 'conversation', 'messages', 'users'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'body' => 'required|string|max:2000',
            'conversation_id' => 'required|exists:conversations,id',
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);

        if (!$conversation->participants->contains($user->id)) {
            abort(403);
        }

        $message = $conversation->messages()->create([
            'user_id' => $user->id,
            'body' => $request->body,
            'type' => 'text',
        ]);

        $message->load('user');

        // Notify other participants
        foreach ($conversation->participants as $participant) {
            if ($participant->id !== $user->id) {
                $participant->notify(new NewMessageNotification($message));
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'id' => $message->id,
                'body' => $message->body,
                'user' => ['name' => $message->user->name, 'id' => $message->user_id],
                'created_at' => $message->created_at->format('h:i A'),
                'is_mine' => true,
            ]);
        }

        return back();
    }

    public function startConversation(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $authUser = auth()->user();
        $targetUser = User::findOrFail($request->user_id);

        // Check if private conversation already exists
        $existing = Conversation::where('type', 'private')
            ->whereHas('participants', fn($q) => $q->where('users.id', $authUser->id))
            ->whereHas('participants', fn($q) => $q->where('users.id', $targetUser->id))
            ->first();

        if ($existing) {
            return redirect()->route('chat.show', $existing);
        }

        $conversation = Conversation::create([
            'type' => 'private',
            'created_by' => $authUser->id,
        ]);

        $conversation->participants()->attach([$authUser->id, $targetUser->id]);

        return redirect()->route('chat.show', $conversation);
    }

    public function notifications()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->paginate(20);
        $user->unreadNotifications->markAsRead();
        return view('notifications.index', compact('notifications'));
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    public function getNotificationsJson()
    {
        $user = auth()->user();
        return response()->json([
            'unread_count' => $user->unreadNotifications->count(),
            'notifications' => $user->notifications()->take(10)->get()->map(fn($n) => [
                'id' => $n->id,
                'data' => $n->data,
                'read' => !is_null($n->read_at),
                'time' => $n->created_at->diffForHumans(),
            ])
        ]);
    }
}
