<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Currency;
use App\Http\Requests\FilterRequest;

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
        $fileName = 'tasks.csv';
        $currencies = session("currencies");
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Currency Name', 'Code', 'Course');

        $callback = function() use($currencies, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($currencies->items() as $currency) {
                $row['Currency Name']  = $currency->currency_name;
                $row['Code']    = $currency->code;
                $row['Course']    = $currency->course;
                
                fputcsv($file, array($row['Currency Name'], $row['Code'], $row['Course']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportXls(){
        $currencies = session("currencies");
       
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=example.xls",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        
        $columns = array('Currency Name', 'Code', 'Course');

        $callback = function() use($currencies, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($currencies->items() as $currency) {
                $row['Currency Name']  = $currency->currency_name;
                $row['Code']    = $currency->code;
                $row['Course']    = $currency->course;
                ;
                fwrite($file, $row['Currency Name'] . " \t " . $row['Code'] . " \t " . $row['Course'] . " \t \n");
            }

            fclose($file);
        
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function filter(FilterRequest $request){
    
        $currencies = session("currencies")->whereBetween("course", [$request->from, $request->to]);

        $codes = [];
        $courses = [];

        foreach($currencies as $code){
            array_push($codes, $code->code);
            array_push($courses, $code->course);
        }
        
        return view("exchange", ["filteredCurrencies" => $currencies, "currencies" => session("currencies"), "arrayCodes" => $codes, "arrayCourse" => $courses]);
    }
}
