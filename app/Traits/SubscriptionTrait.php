<?php

namespace App\Traits;

use App\Models\Subscription;

trait SubscriptionTrait
{

    /**
     * @param string $key
     * @return ?Subscription
     */
    public static function store(string $key,$expire): ?Subscription
    {
        return Subscription::create([
            "key" => $key,
            "expire_at"=>$expire
        ]);
    }

}
