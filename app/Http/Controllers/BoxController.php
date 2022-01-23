<?php

namespace App\Http\Controllers;

use App\Models\Box;
use Illuminate\Http\Request;

class BoxController extends Controller
{
    public function index()
    {
        return Box::all();
    }

    public function indexByType($id)
    {
        return Box::whereRelation('types', 'type_id', $id)->get();
    }

    public function box($id)
    {
        return Box::with('types')->find($id);
    }
}