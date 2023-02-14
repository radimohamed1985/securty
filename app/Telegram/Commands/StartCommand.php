<?php

namespace App\Telegram\Commands;

use App\Models\Client;
use App\Telegram\Commands\Core\BaseCommand;
use App\Traits\ClientTrait;
use Telegram\Bot\Objects\User;

class StartCommand extends BaseCommand
{

    use ClientTrait;

    // command name
    protected $name = 'start';

    // command description
    protected $description = "Let's start working.";

    /**
     * @inheritDoc
     */
    public function handle()
    {
        // Get The Message Information
        $updates = $this->getUpdate();

        // store user data
        // TODO: DRY (use getClient method)
        $user =  $updates->my_chat_member->from ?? $updates->message->from;

        // Say Hey
        $this->reply('Hey , ' . $user->firstName . ' ' . $user->lastName);

        // handle the process of verify and creating the client
        $client = $this->handleClient($user);

        // verify the client if it's subscribed or not, and do the needed
        $this->handleClientSubscription($client);

    }

    /**
     * Check if the Client Already exist in the database
     */

    protected function handleClient(User $user): Client
    {
        // query the client from the db
        $client = Client::firstWhere('client_id', $user->id);

        // check if it not found
        if (!$client) {

            // then save the client info
            $client = ClientTrait::createClientRecord($user);

        } else {
            $this->reply("Welcome back");
        }

        return $client;

    }

    /**
     * @param Client $client
     * @return void
     */
    public function handleClientSubscription(Client $client): void
    {
        // verify the subscription
        if ($client->is_subscribed() === null) {

            $this->sendInlineKeyboard("You are not Subscribed.", "Subscribe", "subscribe");

        } else {

            $this->triggerCommand('forms');
        }
    }
}
