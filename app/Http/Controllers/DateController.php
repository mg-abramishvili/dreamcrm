<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class DateController extends Controller
{
    public function date()
    {
        
        $t=time();
        echo($t . "<br>");
        echo(date("Y-m-d",$t));
        // return Carbon::now();
    }
}
