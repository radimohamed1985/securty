<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Client;
use  App\Models\Subscription;
use  App\Models\Message;
use  App\Models\Form;
use  App\Models\Lead;
use  App\Models\User;
use App\Telegram\Commands\Core\BaseCommand;
use AshAllenDesign\ShortURL\Models\ShortURL;


class testing extends Controller
{
    public function index(){
        // dd('hello');
        // dd(ShortURL::class);
        // $updates = $this->getUpdate()->callbackQuery ?? $this->getUpdate();

        // $chat_id = $updates->message->chat->id;
        // $last_message = Message::lastInChat('2007516770');

        // $client_id = $last_message->client_id;

// return Client::get();
    // return    Client::get();
    return    User::get();
    // return    Message::get();
    // return    ShortURL::get();
    // return    Lead::get();
    return    Subscription::get();
    // return    Form::get();
    }
}
