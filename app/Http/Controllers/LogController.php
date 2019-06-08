<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class LogController extends Controller
{
    public function logUserInput(Request $request)
    {
        $filename = date('Y-W', strtotime('today')).".txt";
        $input = "---------------------"."\n\r".\Request::getClientIp(true)." --- at ".gmdate('d M Y H:i:s')." UTC time"."\n\r".$request->post('input')."\n\r".$request->post('options')."\n\r".$request->post('format')."\n\r"."------------------------------------";
        
        $exists = Storage::exists($filename);

        if(Storage::exists($filename)) {
            Storage::append($filename, $input);
        } else {
            Storage::put($filename, $input);
        }
        
    }
}


