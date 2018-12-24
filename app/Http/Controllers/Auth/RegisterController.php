<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\PrivateUserResource;

class RegisterController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        return new PrivateUserResource($user);
    }
}
