<?php

namespace Tests;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Call the given URI via a JWT-authenticated user.
     *
     * @param  \Tymon\JWTAuth\Contracts\JWTSubject  $user
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function jsonAs(JWTSubject $user, $method, $uri, $data = [], $headers = [])
    {
        $token = auth()->tokenById($user->id);

        return $this->json($method, $uri, $data, array_merge($headers, [
            'Authorization' => 'Bearer ' . $token,
        ]));
    }
}
