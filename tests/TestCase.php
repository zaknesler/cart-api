<?php

namespace Tests;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Call the given URI as a JWT-authenticated user.
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
