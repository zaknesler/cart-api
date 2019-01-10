<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    /**
     * Create a new controller instance
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Invalidate the JWT token.
     *
     * @return \Response
     */
    public function destroy()
    {
        auth()->logout();
    }
}
