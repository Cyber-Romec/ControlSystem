<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Currency;

class CurrencyControllerApi extends Controller
{
    public function updateCurrency(){
        $rates = Http::get('https://openexchangerates.org/api/latest.json?app_id=' . env("APP_ID"));
        $currencies = Http::get('https://openexchangerates.org/api/currencies.json?app_id=' . env("APP_ID"));
        
        $data = array();

        foreach($currencies->json() as $code => $currency){
            if(array_key_exists($code, $rates["rates"])){
                array_push($data, [
                    "currency_name" => $currency,
                    "code" => $code,
                    "course" => $rates["rates"][$code],
                ]);
            }
        }
        Currency::insert($data);
        if($currencies->successful() && $rates->successful()){
            return redirect()->route("currency.index");
        }
    }
    
    public function index(){
        if(count(Currency::all()) == 0){
            return redirect()->route("currency.update");
        }
        $arrayOfCodes = Currency::select("code")->take(40)->pluck("code")->toArray();
        $arrayOfCourse = Currency::select("course")->take(40)->pluck("course")->toArray();
        
        return view("exchange", ["currencies" => Currency::all()->take(40), "arrayCodes" => $arrayOfCodes, "arrayCourse" => $arrayOfCourse]);
    }
}
