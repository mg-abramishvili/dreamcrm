<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index($inn = null)
    {
        if(!$inn) {
            return Client::all();
        }

        return Client::where('inn', $inn)->with('projects')->first();
    }

    public function client($id)
    {
        return Client::find($id);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $client = new Client();

        $client->name = $request->name;
        $client->tel = $request->tel;
        $client->email = $request->email;
        $client->kont_litso = $request->kont_litso;
        $client->yur_address = $request->yur_address;
        $client->pocht_address = $request->pocht_address;
        $client->fakt_address = $request->fakt_address;
        $client->inn = $request->inn;
        $client->kpp = $request->kpp;
        $client->ogrn = $request->ogrn;
        $client->ras_schet = $request->ras_schet;
        $client->korr_schet = $request->korr_schet;
        $client->bank = $request->bank;
        $client->bik = $request->bik;

        $client->save();

        return $client;
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $client = Client::find($id);

        $client->name = $request->name;
        $client->tel = $request->tel;
        $client->email = $request->email;
        $client->kont_litso = $request->kont_litso;
        $client->yur_address = $request->yur_address;
        $client->pocht_address = $request->pocht_address;
        $client->fakt_address = $request->fakt_address;
        $client->inn = $request->inn;
        $client->kpp = $request->kpp;
        $client->ogrn = $request->ogrn;
        $client->ras_schet = $request->ras_schet;
        $client->korr_schet = $request->korr_schet;
        $client->bank = $request->bank;
        $client->bik = $request->bik;

        $client->save();
    }
}
