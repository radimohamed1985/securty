<?php

namespace App\Telegram\Handlers;

use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class CallbackQueryHandler
{

    public static function handle(Update $update)
    {
        $query = $update->callbackQuery;
        $data = $query->data;

        if (str_contains($data, ' ')) {
            $data = explode(' ', $data);

            if (count($data) > 2) {
                $parameters = array_slice($data, 1);
            } else {
                $parameters = $data[1];
            }

            $classname = "App\Telegram\Commands\Forms\\" . ucfirst($data[0]) . "Command";

            Telegram::triggerCommand($classname::setVariables($parameters), $update);
        } else {
            Telegram::triggerCommand($data, $update);
        }
    }
}
