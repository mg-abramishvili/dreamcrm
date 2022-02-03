<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        // $uniqueId = Str::uuid();
    }

    public function user($uid)
    {
        return User::with('permissions')->where('uid', $uid)->first();
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email:rfc|unique:users,email,' . $id
        ]);

        $user = User::find($id);

        if(isset($request->name)) {
            $user->name = $request->name;
        }

        if(isset($request->email)) {
            $user->email = $request->email;
        }

        if(isset($request->password)) {
            $user->password = Hash::make($request->password);
        }

        if (!isset($request->avatar)) {
            $user->avatar = null;
        } else {
            $user->avatar = $request->avatar;
        }

        $user->save();
    }
}
