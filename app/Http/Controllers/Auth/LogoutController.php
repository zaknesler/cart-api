<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
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
