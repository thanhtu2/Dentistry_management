<?php

namespace App\Http\Controllers;

use App\Models\ChatHistory;

class ChatHistoryController extends Controller
{
 public function index()
{
    $chatHistories = ChatHistory::with('user')
        ->whereNotNull('conversation_id')
        ->orderBy('created_at', 'ASC')
        ->get()
        ->groupBy(function ($chat) {
            return $chat->conversation_id ?? 'OLD-' . $chat->id;
        })
        ->sortByDesc(function ($messages) {
            return $messages->first()->created_at;
        });

    return view('admin.chat_history.index', compact('chatHistories'));
}
}