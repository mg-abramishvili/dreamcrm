<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return Client::all();
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

        if(isset($request->tel)) {
            $client->tel = $request->tel;
        }
        if(isset($request->email)) {
            $client->email = $request->email;
        }
        if(isset($request->kont_litso)) {
            $client->kont_litso = $request->kont_litso;
        }
        if(isset($request->yur_address)) {
            $client->yur_address = $request->yur_address;
        }
        if(isset($request->pocht_address)) {
            $client->pocht_address = $request->pocht_address;
        }
        if(isset($request->fakt_address)) {
            $client->fakt_address = $request->fakt_address;
        }
        if(isset($request->inn)) {
            $client->inn = $request->inn;
        }
        if(isset($request->kpp)) {
            $client->kpp = $request->kpp;
        }
        if(isset($request->ogrn)) {
            $client->ogrn = $request->ogrn;
        }
        if(isset($request->ras_schet)) {
            $client->ras_schet = $request->ras_schet;
        }
        if(isset($request->korr_schet)) {
            $client->korr_schet = $request->korr_schet;
        }
        if(isset($request->bank)) {
            $client->bank = $request->bank;
        }
        if(isset($request->bik)) {
            $client->bik = $request->bik;
        }

        $client->save();
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $client = Client::find($id);

        $client->name = $request->name;

        if(isset($request->tel)) {
            $client->tel = $request->tel;
        }
        if(isset($request->email)) {
            $client->email = $request->email;
        }
        if(isset($request->kont_litso)) {
            $client->kont_litso = $request->kont_litso;
        }
        if(isset($request->yur_address)) {
            $client->yur_address = $request->yur_address;
        }
        if(isset($request->pocht_address)) {
            $client->pocht_address = $request->pocht_address;
        }
        if(isset($request->fakt_address)) {
            $client->fakt_address = $request->fakt_address;
        }
        if(isset($request->inn)) {
            $client->inn = $request->inn;
        }
        if(isset($request->kpp)) {
            $client->kpp = $request->kpp;
        }
        if(isset($request->ogrn)) {
            $client->ogrn = $request->ogrn;
        }
        if(isset($request->ras_schet)) {
            $client->ras_schet = $request->ras_schet;
        }
        if(isset($request->korr_schet)) {
            $client->korr_schet = $request->korr_schet;
        }
        if(isset($request->bank)) {
            $client->bank = $request->bank;
        }
        if(isset($request->bik)) {
            $client->bik = $request->bik;
        }

        $client->save();
    }
}
