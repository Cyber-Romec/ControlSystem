<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use App\Models\Currency;

class CurrencyService{

    public static function update(){
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
        Currency::truncate();
        Currency::insert($data);
        
        if($currencies->successful() && $rates->successful()){
            return true;
        }
        
        return false;
    }

    public static function exportCsv($currencies){

        $columns = array('Currency Name', 'Code', 'Course');

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=tasks.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );


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

        return array("callback" => $callback, "headers" => $headers);
    }

    public static function exportXls($currencies){
        
        $headers = array(
            "Content-type"        => "text/xls",
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

        return array("callback" => $callback, "headers" => $headers);
    }
}