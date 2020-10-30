<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware(['signed'])->only('verifyMail');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Login user
     * @param LoginRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login(LoginRequest $request)
    {

        if (auth()->attempt($request->only(['email', 'password']), $request->remember == 'on')) {
            if (session()->exists('redirect_to')) {
                return redirect(session()->get('redirect_to'));
            } else {
                return redirect()->route('dashboard');
            }
        }

        session()->flash('errorMsg', __('Invalid Credential'));
        return back();
    }

    /**
     * Register user profile
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create(array_merge($request->all()));
        event(new Registered($user));

        session()->flash('successMsg', __('Successfully registered. Now login to your account'));
        return redirect()->route('auth.login');
    }

    /**
     * Update profile
     * @param UpdateProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        auth()->user()->update($request->all());
        session()->flash('successMsg', 'Successfully updated your profile');

        return redirect()->back();
    }


    /**
     * Update password
     * @param UpdatePasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        auth()->user()->update(['password' => $request->newPassword]);
        session()->flash('successMsg', 'Password updated successfully');
        return redirect()->back();
    }

    /**
     * @param EmailVerificationRequest $request
     * @return mixed
     */
    public function verifyMail(Request $request)
    {
        auth()->loginUsingId($request->id);

        if (auth()->user()->hasVerifiedEmail())
            abort(403, 'Email already verified');


        if (!hash_equals((string)$request->id, (string)auth()->id())) {
            return false;
        }

        if (!hash_equals(
            (string)$request->hash,
            sha1(auth()->user()->getEmailForVerification())
        )) {
            return false;
        }

        auth()->user()->markEmailAsVerified(); // verify email

        return redirect()->route('dashboard');
    }

    /**
     * Logout current user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth()->logout();
        session()->flash('successMsg', __('Successfully logged out'));
        return redirect()->route('auth.login');
    }
}
