<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function create()
    {
        return view('login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);

//        $user = User::where('email', $request->email)->first();
//        if($user && Hash::check($request->password, $user->password)){
//
//          Auth::login($user, $request->boolean('remember_me'));
//
//          return redirect()->to('/classrooms');
//        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
//            'status' => 'active'
        ];

        if(Auth::attempt($credentials, $request->boolean('remember'))){
            return redirect()->intended(route('c.index'));
        }

        return back()->withInput()->withErrors(['email' => 'Invalid Credentials']);
    }
}
