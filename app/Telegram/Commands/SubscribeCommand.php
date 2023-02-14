<?php

namespace App\Telegram\Commands;

use App\Models\Client;
use App\Telegram\Commands\Core\BaseCommand;
use App\Traits\ClientTrait;
use Telegram\Bot\Objects\User;

class SubscribeCommand extends BaseCommand
{
    protected $name = "subscribe";

    protected $description = "Activate your subscription";

    public function handle()
    {

        // Get Client's Information
        $client = $this->getUpdate()->callbackQuery->from ?? $this->getUpdate()->message->from ?? $this->getUpdate()->my_chat_member->from;

        // TODO: no need for this now
        // Check if the Client is registered
        $client = $this->handleClient($client);


        if ($this->getClient()->is_subscribed() != null) {
            $this->reply("Sorry, You are already subscribed.");

            $this->triggerCommand('forms');

            // TODO: WHAT TO DO?
        } else {
            $this->reply("Please send your subscription key :");

            // TODO: this should a keyboard key not inline
            $this->sendInlineKeyboard("and then click validate.", "Validate", "validate");
        }
    }

    /**
     * Check if the Client Already exist in the database
     */

    protected function handleClient(User $user): Client
    {
        // query the client from the db
        $client = Client::where('client_id', $user->id)->first();

        // check if it not found
        if (!$client) {
            $this->reply("Client Not Found");

            // then save the client info
            $client = ClientTrait::createClientRecord($user);

            $this->reply("Client Account Created ");

        } else {
            $this->reply("Welcome back");
        }

        return $client;

    }

}
