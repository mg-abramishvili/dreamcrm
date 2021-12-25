<?php

namespace App\Http\Controllers;

use App\Models\Element;
use Illuminate\Http\Request;

class ElementController extends Controller
{
    public function index()
    {
        return Element::all();
    }

    public function element($id)
    {
        return Element::where('id', $id)->first();
    }
}
