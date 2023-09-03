<?php

namespace App\Http\Controllers\web;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function login(Request $request){

        if(Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
        ])){
            return redirect('/dashboard');
        }else{
            return redirect('/');
        }

    }

    public function logout(){
        Auth::logout();
        // Auth()->logout();
        return redirect('/');
        
    }

    
}
