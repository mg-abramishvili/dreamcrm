<?php

namespace App\Http\Controllers;

use App\Models\Reserve;
use App\Http\Resources\ReserveResource;
use Illuminate\Http\Request;

class ReserveController extends Controller
{
    public function index()
    {
        return ReserveResource::collection(Reserve::all());
    }
}
