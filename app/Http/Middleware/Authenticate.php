<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
//        return $request->url();
        if (!$request->expectsJson()) {
            session()->flash('errorMsg', __('Please login to your account first'));
            session()->put('redirect_to', $request->fullUrl());
            return route('auth.login');
        }
    }
}
