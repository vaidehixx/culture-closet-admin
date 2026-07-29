<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $history = session('notification_history', []);
        return view('notifications.index', compact('history'));
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'    => 'required|string|max:100',
            'body'     => 'required|string|max:500',
            'audience' => 'required|in:all,verified,unverified',
        ]);

        // Get push tokens for the target audience from profiles
        $query = User::whereNotNull('push_token')->where('push_token', '!=', '');

        if ($data['audience'] === 'verified') {
            $query->where('verified', true);
        } elseif ($data['audience'] === 'unverified') {
            $query->where('verified', false);
        }

        $tokens = $query->pluck('push_token')->filter()->values()->all();

        $sent = 0;
        $failed = 0;

        if (!empty($tokens)) {
            // Expo accepts up to 100 messages per request
            foreach (array_chunk($tokens, 100) as $batch) {
                $messages = array_map(fn($token) => [
                    'to'    => $token,
                    'title' => $data['title'],
                    'body'  => $data['body'],
                    'sound' => 'default',
                ], $batch);

                try {
                    $response = Http::withHeaders([
                        'Accept'       => 'application/json',
                        'Content-Type' => 'application/json',
                    ])->post(env('EXPO_PUSH_URL', 'https://exp.host/--/api/v2/push/send'), $messages);

                    $results = $response->json('data', []);
                    foreach ($results as $result) {
                        $result['status'] === 'ok' ? $sent++ : $failed++;
                    }
                } catch (\Throwable $e) {
                    $failed += count($batch);
                }
            }
        }

        // Log in session history
        $history = session('notification_history', []);
        array_unshift($history, [
            'title'    => $data['title'],
            'body'     => $data['body'],
            'audience' => $data['audience'],
            'sent'     => $sent,
            'failed'   => $failed,
            'tokens'   => count($tokens),
            'sent_at'  => now()->format('d M Y, H:i'),
        ]);
        session(['notification_history' => array_slice($history, 0, 20)]);

        $msg = count($tokens) === 0
            ? "No push tokens found for audience '{$data['audience']}'."
            : "Sent {$sent} notification(s) to {$data['audience']} users" . ($failed ? " ({$failed} failed)" : "") . ".";

        return back()->with('success', $msg);
    }
}
