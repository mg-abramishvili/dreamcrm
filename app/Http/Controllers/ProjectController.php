<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::all();
    }

    public function project($id)
    {
        return Project::with('user', 'client', 'calculations.boxes', 'offers.calculations.boxes')->find($id);
    }

    public function innCheck($inn)
    {
        return Project::with('client')->whereRelation('client', 'inn', $inn)->get();
    }
}
