<?php

namespace App\Telegram\Commands\Forms;

use App\Models\Form;
use App\Models\Message;
use App\Telegram\Commands\Core\BaseCommand;
use Telegram\Bot\Keyboard\Keyboard;

class DestinationCommand extends BaseCommand
{

    private static int $id;
    private static string $action;

    protected $name = 'destination';

    protected $description = 'Set form destination link';

    public static function setVariables(mixed $arguments): string
    {

        if (isset($arguments[1])) {

            self::$id = $arguments[0];
            self::$action = $arguments[1];

        } else {
            self::$id = $arguments;
        }

        return self::class;
    }

    // TODO: DRY!

    public function handle()
    {

        $this->authenticate();

        $update = $this->getUpdate()->callbackQuery;

        if (isset(self::$action)) {

            $destination = null;

            if (self::$action == 'custom') {
                $this->sendInlineKeyboard(
                    "Please send the form's destination link then ",
                    'Set Destination',
                    "destination " . self::$id . " set"
                );
            } else if (self::$action == 'set') {
                $destination = Message::lastInChat($update->message->chat->id)->message;
            } else if (self::$action == 'default') {
                $destination = config('settings.default_destination');
                // $destination = url('formpage');
            } else {
                $this->reply("Something else");
            }


            if ($destination) {

                $form = Form::where('owner', $this->getClient()->id)->where('form_number', self::$id)->first();
                $form['destination'] = $destination;

                $form->save();

                $this->sendInlineKeyboard("Successfully set destination", '⬅️   Back ', "form " . self::$id . " set");
            }
        } else {

            $keyboard = Keyboard::make()
                ->inline()
                ->row(
                    Keyboard::inlineButton(['text' => 'Custom', 'callback_data' => "destination " . self::$id . " custom"]),
                    Keyboard::inlineButton(['text' => 'Default', 'callback_data' => "destination " . self::$id . " default"])
                    )
                ->row(Keyboard::inlineButton(['text' => 'Cuurent status', 'callback_data' => "destination " . self::$id . " default"]))
                ->row(Keyboard::inlineButton(['text' => '⬅️   Back ', 'callback_data' => "form " . self::$id]));

            $this->replyWithMessage(['text' => 'Select Destination Type :', 'reply_markup' => $keyboard]);
        }
    }
}
