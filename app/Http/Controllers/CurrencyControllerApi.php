<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Http\Requests\FilterRequest;
USE App\Services\CurrencyService;

class CurrencyControllerApi extends Controller
{
    public function updateCurrency(){
        if(CurrencyService::update()){
            return redirect()->route("currency.index");
        }
        abort(403);
    }
    
    public function index(){
        if(count(Currency::all()) == 0){
            return redirect()->route("currency.update");
        }
        $currencies = Currency::paginate(15);
        session(["currencies" => $currencies]);
        $codes = [];
        $courses = [];
        foreach($currencies->items() as $code){
            array_push($codes, $code->code);
            array_push($courses, $code->course);
        }
        
        return view("exchange", ["currencies" => $currencies, "arrayCodes" => $codes, "arrayCourse" => $courses]);
    }

    public function exportCsv(){
        $currencies = session("currencies");
        
        $result = CurrencyService::exportCsv($currencies);

        return response()->stream($result["callback"], 200, $result["headers"]);
    }

    public function exportXls(){
        $currencies = session("currencies");
        
        $result = CurrencyService::exportXls($currencies);

        return response()->stream($result["callback"], 200, $result["headers"]);
    }

    public function filter(FilterRequest $request){
    
        $currencies = Currency::whereBetween("course", [$request->from, $request->to])->paginate(15);
        $codes = [];
        $courses = [];

        foreach($currencies->items() as $currency){
            array_push($codes, $currency->code);
            array_push($courses, $currency->course);
        }
        
        return view("exchange", ["currencies" => $currencies, "arrayCodes" => $codes, "from" => $request->from, "to" => $request->to, "arrayCourse" => $courses]);
    }
}
