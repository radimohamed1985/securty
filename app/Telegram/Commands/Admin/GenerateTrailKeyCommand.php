<?php

namespace App\Telegram\Commands\Admin;

use App\Telegram\Commands\Core\BaseCommand;
use App\Traits\SubscriptionTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


class GenerateTrailKeyCommand extends BaseCommand
{

    protected $name = "generatetrailkey";

    protected $description = "Generate Subscription trail Key.";

    public function handle()
    {
        if ($this->getClient()->client_id == config('settings.admin_id')) {
            // Generate a subscription key
            $generated_key = Str::uuid()->toString();
            $days=4;
            $expire =\Carbon\Carbon::now()->addDays($days);

            // save it
            $subscription = SubscriptionTrait::store($generated_key,$expire);

            if ($subscription) {
                // send it to the admin
                $this->replyWithMessage(["text" => $generated_key]);
            } else {
                $this->replyWithMessage(["text" => 'Something Wrong!.']);
            }
        }
    }

}
