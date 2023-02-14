<?php

namespace App\Http\Controllers;

use AshAllenDesign\ShortURL\Classes\Resolver;
use AshAllenDesign\ShortURL\Controllers\ShortURLController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Log;


class RedirectionController extends ShortURLController
{
    public function __invoke(Request $request, Resolver $resolver, string $shortURLKey): RedirectResponse
    {
       
        if(request()->query->has('lansdp')){

            // Log::info("We need a ");
        }
       
        return parent::__invoke($request, $resolver, $shortURLKey);
    }
}
