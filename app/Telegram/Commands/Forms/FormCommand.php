<?php

namespace App\Telegram\Commands\Forms;

use App\Models\Form;
use App\Telegram\Commands\Core\BaseCommand;
use Str;
use Telegram\Bot\Keyboard\Keyboard;

class FormCommand extends BaseCommand
{
    private static int $id;
    /**
     * The Command's name.
     *
     * @var string
     */
    protected $name = 'form';
    /**
     * The Command's short description.
     *
     * @var string
     */
    protected $description = 'Form Command';

    public static function setVariables(mixed $arguments): string
    {
        self::$id = $arguments[0];

        return self::class;
    }

    public function handle()
    {

        $this->authenticate();

        // TODO: Refactor
        $form = Form::where('owner', $this->getClient()->id)->where('form_number', self::$id)->first();

        if (!$form) {
            $form = new Form();
            $form['name'] = "Form " . self::$id;
            $form['form_number'] = self::$id;
            $form['hash'] = Str::uuid();
            $form["owner"] = $this->getClient()->id;

            $form->save();
        }

        if (isset(self::$action)) {

            $form['enabled'] = self::$action == 'enable';

            $form->save();

            $this->sendInlineKeyboard('Current State : ' . self::$action, '⬅️   Back', "status " . self::$id);
        } else {
            $keyboard = Keyboard::make()
                ->inline()
                ->row(
                    Keyboard::inlineButton(['text' => 'Set Destination', 'callback_data' => "destination " . self::$id]),
                    Keyboard::inlineButton(['text' => 'Generate Link', 'callback_data' => "generate " . self::$id])
                    )
                ->row(Keyboard::inlineButton(['text' => 'Enable \ Disable \ Reset', 'callback_data' => "status " . self::$id]))
                ->row(Keyboard::inlineButton(['text' => '⬅️   Back ', 'callback_data' => "forms"]));

            $status = $form['enabled'] ? "Enabled" : "Disabled";
            $this->replyWithMessage(['text' => "Current status for ". $form['name'] . " : " . $status, 'reply_markup' => $keyboard]);
        }
    }
} 