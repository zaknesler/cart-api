<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\PrivateUserResource;

class LoginController extends Controller
{
    /**
     * Attempt to authenticate a user.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(LoginRequest $request)
    {
        $token = auth()->attempt($request->validated());

        if (!$token) {
            return response()->json([
                'errors' => [
                    'email' => ['Could not sign you in with those credentials.'],
                ],
            ], 422);
        }

        return (new PrivateUserResource($request->user()))
            ->additional([
                'meta' => [
                    'token' => $token,
                ],
            ]);
    }
}
