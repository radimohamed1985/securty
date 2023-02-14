<?php

namespace App\Telegram\Commands\Forms;

use App\Telegram\Commands\Core\BaseCommand;
use Telegram\Bot\Keyboard\Keyboard;

class MainFormsCommand extends BaseCommand
{
    /**
     * The Command name.
     *
     * @var string
     */
    protected $name = 'forms';

    /**
     * The Command description.
     *
     * @var string
     */
    protected $description = 'Create a new form';

    public function handle()
    {

        if ($this->getClient()->is_subscribed() == null) {
            $this->triggerCommand('subscribe', $this->getUpdate());
        } else {
            $keyboard = Keyboard::make()
                ->inline()
                ->row(
                    Keyboard::inlineButton(['text' => 'Form 1', 'callback_data' => 'form 1']),
                    Keyboard::inlineButton(['text' => 'Form 2', 'callback_data' => 'form 2']),
                    Keyboard::inlineButton(['text' => 'Form 3', 'callback_data' => 'form 3'])
                    )
                ->row(
                    Keyboard::inlineButton(['text' => 'Report', 'callback_data' => 'example1']),
                    Keyboard::inlineButton(['text' => 'Downlaod', 'callback_data' => 'example2']),
                    Keyboard::inlineButton(['text' => 'Settings', 'callback_data' => 'example3'])
                );

            $this->replyWithMessage(['text' => "Select a Form to Setup", 'reply_markup' => $keyboard]);
        }


    }
}
