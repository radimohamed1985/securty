<?php

namespace App\Telegram\Commands\Core;

use App\Models\Client;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

class BaseCommand extends Command
{
    public function handle()
    {
    }

    /**
     * @return void
     */
    public function sendInlineKeyboard(string $message, string $title, string $command): void
    {
        $keyboard = Keyboard::make()
            ->inline()
            ->row(
                Keyboard::inlineButton(['text' => $title, 'callback_data' => $command]),
            );

        $this->replyWithMessage(['text' => $message, 'reply_markup' => $keyboard]);
    }

    public function authenticate()
    {
        if (!$this->getClient()->is_subscribed()) {
            $this->triggerCommand('subscribe', $this->getUpdate());

            die();
        }
    }

    public function getClient(): ?Client
    {
        // Get the client id
        $request_client_id = $this->getUpdate()->callbackQuery->from->id ?? $this->getUpdate()->message->from->id;

        // Find the Client in the database
        return Client::firstWhere("client_id", $request_client_id);
    }

    /**
     * @return void
     */
    protected function reply(string $message): void
    {
        $this->replyWithMessage(["text" => $message]);
    }
}
