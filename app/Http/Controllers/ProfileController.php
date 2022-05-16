<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;

class ProfileController extends Controller
{
    
    public function show(){
        return view("profile");
    }

    public function update(ProfileUpdateRequest $request){
        \Auth::user()->update($request->only("name", "email"));

        return redirect()->route("profile.show");
    }
}
