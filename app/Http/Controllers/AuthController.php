<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

            if (session()->exists('redirect_to')) {
                return redirect(session()->get('redirect_to'));
            } else {
                return redirect()->route('dashboard');
            }
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


    public function updateProfile(Request $request)
    {
        $payload = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->user())],
        ]);

        auth()->user()->update($payload);
        session()->flash('successMsg', 'Successfully updated your profile');

        return redirect()->back();
    }


    public function updatePassword(Request $request)
    {

        $request->validate([
            'oldPassword' => ['required'],
            'newPassword' => ['required', 'confirmed']
        ]);

        if (!auth()->validate([
            'email' => auth()->user()->email,
            'password' => $request->oldPassword
        ])) {
            session()->flash('errorMsg', 'Invalid old password');
        }

        auth()->user()->update(['password' => bcrypt($request->newPassword)]);
        session()->flash('successMsg', 'Password updated successfully');

        return redirect()->back();
    }


    public function logout()
    {
        auth()->logout();
        session()->flash('successMsg', __('Successfully logged out'));
        return redirect()->route('auth.login');
    }
}
