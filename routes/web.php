<?php

use App\Http\Controllers\RedirectionController;
use App\Http\Controllers\masterRedirect;
use App\Http\Controllers\FormController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testing;
use App\Telegram\Commands\Forms\GenerateCommand;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Illuminate\Support\Carbon;
use App\Telegram\Commands\Forms\TestCommand;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


 Route::get('/{shortURLKey}', RedirectionController::class);
 Route::get('/{shortURLKey}/lansdp',[masterRedirect::class,'index']);
 Route::get('formpage',function(){
  
    return view('FormPage');

 });
 Route::Post('submit', [LeadController::class,'index']);

// Route::get('/{shortURLKey}/lansdp', function($shortURLKey){
// 

//     return view('loading',['url'=>$finalurl_key]);
// });

// Route::get('test',[testing::class,'index']);

// Route::get('test',[TestCommand::class,'handle']);

