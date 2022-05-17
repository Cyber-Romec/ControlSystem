<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CurrencyControllerApi extends Controller
{
    public function getData(){
        $rates = Http::get('https://openexchangerates.org/api/latest.json?app_id=' . env("APP_ID"));
        $currencies = Http::get('https://openexchangerates.org/api/currencies.json?app_id=' . env("APP_ID"));
        
        if($currencies->successful() && $rates->successful()){
            return view("exchange", ["currencies" => $currencies->json(), "rates" => $rates->json()]);
        }
    }
    public function postData(){
        Http::asForm()->post('https://openexchangerates.org/api/currencies.json?app_id=' . env("APP_ID"), [
            'name' => 'Steve',
            'role' => 'Network Administrator',
        ]);
    }
}
