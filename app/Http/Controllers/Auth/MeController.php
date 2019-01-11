<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PrivateUserResource;

class MeController extends Controller
{
    /**
     * Get the currently authenticated user.
     *
     * @param  \Illuminate\Http\Request  $Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(Request $request)
    {
        return new PrivateUserResource($request->user());
    }
}
