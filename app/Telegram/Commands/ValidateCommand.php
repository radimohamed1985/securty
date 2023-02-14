<?php

namespace App\Telegram\Commands;

use App\Models\Client;
use App\Models\Message;
use App\Models\Subscription;
use App\Telegram\Commands\Core\BaseCommand;

class ValidateCommand extends BaseCommand
{

    const MAX_TRIES = 3;

    protected $name = "validate";

    protected $description = "Validate the subscription key";

    public function handle()
    {

    //    $this->authenticate();

        $updates = $this->getUpdate()->callbackQuery ?? $this->getUpdate();

        $chat_id = $updates->message->chat->id;

        $last_message = Message::lastInChat($chat_id);
        // $last_message = Message::lastInChat('2007516770');

        $client_id = $last_message->client_id;

        $client = Client::firstWhere('client_id', $client_id);

        if ($this->hasReachedSubscriptionLimit($client)) {

            // TODO: better if its a button
            $this->reply("You have reached the subscription tries limit. If something wrong contact us \n @ojprodev");

        } else {

            //  Check the key (NOT NULL, NOT EMPTY, STRING, NOT COMMAND)
            if ($last_message) {

                if (!$last_message->is_command && is_string($last_message->message)) {

                    if (Subscription::isValidKey($last_message->message)) {

                        $this->reply("You are now a subscribed 🤝");

                        // disable this key, by making it to used
                        Subscription::usedKey($last_message->message);

                        // TODO: not in the right place
                        // subscribe the client
                        $client->update([
                            "subscription_id" => Subscription::firstWhere('key', $last_message->message)->id
                        ]);

                        // add the try
                        $client->resetSubscriptionTry();

                        $this->triggerCommand('forms');

                    } else {

                        $this->reply("Invalid key");

                        // add a failed try for the client
                        $client->addSubscriptionTry();

                    }
                } else {
                    $this->sendInlineKeyboard("Please send the subscription key and", "Validate it", 'validate');
                }
            } else {
                $this->reply("Something wrong, please try again");
            }
        }
    }

    /**
     * @param $client
     * @return bool
     */
    public
    function hasReachedSubscriptionLimit($client): bool
    {
        return $client->subscription_tries >= self::MAX_TRIES;
    }
}
