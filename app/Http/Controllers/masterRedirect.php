<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\RedirectionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testing;
use App\Telegram\Commands\Forms\GenerateCommand;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Illuminate\Support\Carbon;

class masterRedirect extends Controller
{
    public function index($shortURLKey){
        $activestat = ShortURL::where('url_key',$shortURLKey)->first();
        $myurl =parse_url($activestat->destination_url);
        dd($myurl);
        if(array_key_exists('path',$myurl) && array_key_exists('query',$myurl)){
            parse_str($myurl['query'],$params);
            $iod=$params['iod'];
            $newurl =ShortURL::where('destination_url', 'LIKE', '%'.$iod.'%')->where('destination_url','!=','http://127.0.0.1:8000/formpage?iod=8fe29129-1bf5-4b54-88d8-ba23468117a5')->get()->last();
          
            $final_url =$newurl->destination_url;
        
            $path =$myurl['path'];
            if($path == '/formpage'){
                return view('FormPage',compact(['final_url','iod']));
            }
          
        }
        if(array_key_exists('query',$myurl)){
           parse_str($myurl['query'],$params);
           if(array_key_exists('_done',$params)){
               parse_str($params['_done'],$params);
           }
   
        }elseif(array_key_exists('fragment',$myurl)){
           parse_str($myurl['fragment'],$params);
        }
   
       $param_array =(array_keys($params));
       $myarray_key =(substr($param_array[0],strpos($param_array[0],'iod')));
       if(array_key_exists($myarray_key,$params)){
           $iod =$params['iod'];
       }else{
        $iod=$params[$param_array[0]];
       }
           
      $lasturl =ShortURL::where('destination_url', 'LIKE', '%'.$iod.'%')->get()->last();
   

    $finalurl_key = ($lasturl->url_key);
    dd( $finalurl_key);

           // if($activestat->deactivated_at < \Carbon\Carbon::now()){
           //     return abort(404,'sorry expired');
           // };
   
       return view('loading',['url'=>$finalurl_key]);
    }
}
