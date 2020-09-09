<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
    {
        $payload = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'max:50']
        ]);


        if (auth()->attempt($payload, $request->remember == 'on')) {
            return redirect()->route('dashboard');
        }

        session()->flash('errorMsg', __('Invalid Credential'));
        return redirect()->back();
    }

    public function register(Request $request)
    {
        $payload = $request->validate([
            'name' => ['required', 'min:3', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:6', 'max:50']
        ]);

        $user = new User($payload);
        $user->save();

        session()->flash('successMsg', __('Successfully registered. Now login to your account'));

        return redirect()->route('auth.login');
    }


    public function logout()
    {
        auth()->logout();
        session()->flash('successMsg', __('Successfully logged out'));
        return redirect()->route('auth.login');
    }
}
