<?php

namespace App\Telegram\Commands\Forms;

use App\Models\Form;
use App\Telegram\Commands\Core\BaseCommand;
use Telegram\Bot\Keyboard\Keyboard;

class StatusCommand extends BaseCommand
{

    protected static $id;

    protected static $action;

    protected $name = 'status';

    protected $description = 'Status command';

    public function handle()
    {
    
        $this->authenticate();
    
        if (isset(self::$action)) {
    
            $form = Form::where('owner', $this->getClient()->id)->where('form_number', self::$id)->first();
    
            if (self::$action == 'reset') {
                // validate if there is previous status and delete it
                if ($form) {
                    $form->delete();
                    $this->sendInlineKeyboard('Previous settings have been reset!', '⬅️   Back', "status " . self::$id);
                } else {
                    $this->sendInlineKeyboard('No previous settings found!', '⬅️   Back', "status " . self::$id);
                }
            } else {
                $form['enabled'] = self::$action == 'enable';
                $form->save();
                $this->sendInlineKeyboard('Current State : ' . self::$action, '⬅️   Back', "status " . self::$id);
            }
    
        } else {
            $keyboard = Keyboard::make()
                ->inline()
                ->row(
                    Keyboard::inlineButton(['text' => 'Enable', 'callback_data' => "status " . self::$id . ' enable']),
                    Keyboard::inlineButton(['text' => 'Disable', 'callback_data' => "status " . self::$id . ' disable']),
                    Keyboard::inlineButton(['text' => 'Reset', 'callback_data' => "status " . self::$id . ' reset'])
                )
                ->row(Keyboard::inlineButton(['text' => '⬅️   Back', 'callback_data' => "form " . self::$id]));
    
            $this->replyWithMessage(['text' => 'Choice Status : ', 'reply_markup' => $keyboard]);
        }
    }
    
    // TODO: DRY!
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
}
