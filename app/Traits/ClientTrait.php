<?php

namespace App\Traits;

use App\Models\Client;
use Illuminate\Support\Str;
use Telegram\Bot\Objects\User;

trait ClientTrait
{
    /**
     * @param User $user
     * @return Client
     */
    public static function createClientRecord(User $user): Client
    {
        return Client::create([
            "client_id" => $user->id,
            "first_name" => $user->firstName ?? null,
            "last_name" => $user->lastName ?? null,
            "username" => $user->username ?? null,
            "language" => $user->languageCode ?? null
        ]);
    }

}
