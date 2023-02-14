<?php

use App\Models\Message;
use App\Telegram\Handlers\CallbackQueryHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;



Route::post(config('telegram.bots.mybot.webhook_url_hash') . '/webhook', function () {
    // Accept command
    Telegram::commandsHandler(true);
    // Listen for Webhook's updates
    $update = Telegram::getWebhookUpdate();

    if ($update->isType('callback_query')) {
        CallbackQueryHandler::handle($update);
    }

    if ($update->message) {
        $message = $update->message;
        // TODO: not the right place to create a message
        // Save the message in database
        $message = Message::create([
            "message_id" => $message->messageId,
            "client_id" => $message->from->id,
            "is_bot" => $message->from->isBot,
            "chat_id" => $message->chat->id,
            "message" => $message->text,
            "is_command" => $message->hasCommand()
        ]);
    }


    Log::info($update);
});
