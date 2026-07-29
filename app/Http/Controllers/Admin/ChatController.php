<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $filter = $request->get('filter', 'all');
        $q = Conversation::with(['user1','user2','latestMessage']);
        if ($search) {
            $q->where(function($outer) use ($search) {
                $outer->whereHas('user1', fn($u) => $u->where('name','like',"%$search%"))
                      ->orWhereHas('user2', fn($u) => $u->where('name','like',"%$search%"));
            });
        }
        $conversations = $q->orderByDesc('last_message_at')->paginate(25)->withQueryString();
        $stats = [
            'total'   => Conversation::count(),
            'flagged' => 0, // no is_flagged column in Supabase messages
            'today'   => Conversation::whereDate('created_at', today())->count(),
        ];
        return view('chat.index', compact('conversations','stats','filter'));
    }

    public function show(Conversation $conversation)
    {
        $messages = $conversation->messages()->with('sender')->oldest()->get();
        return view('chat.show', compact('conversation','messages'));
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return back()->with('success', 'Message removed.');
    }
}
