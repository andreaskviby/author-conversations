<?php

use App\Http\Controllers\ConversationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Human interaction routes for conversations
Route::middleware(['auth'])->group(function () {
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])
        ->name('conversations.show');
    Route::post('/conversations/{conversation}/join', [ConversationController::class, 'join'])
        ->name('conversations.join');
    Route::post('/conversations/{conversation}/pause', [ConversationController::class, 'pause'])
        ->name('conversations.pause');
    Route::post('/conversations/{conversation}/resume', [ConversationController::class, 'resume'])
        ->name('conversations.resume');
    Route::post('/conversations/{conversation}/messages', [ConversationController::class, 'addMessage'])
        ->name('conversations.messages.store');
});
