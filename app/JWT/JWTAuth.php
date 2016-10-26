<?php

namespace App\JWT;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class JWTAuth implements Guard
{
    use GuardHelpers;

    /**
     * @var \App\JWT\JWT
     */
    protected $jwt;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(JWT $jwt, UserProvider $provider, Request $request)
    {
        $this->jwt      = $jwt;
        $this->provider = $provider;
        $this->request  = $request;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $payload = $this->jwt->getPayloadFromRequest($this->request);

        if ($payload) {
            return $this->user = $this->provider->retrieveById($payload['uid']);
        }
    }

    public function fromUser(Authenticatable $user)
    {
        $payload = [
            'uid' => $user->getAuthIdentifier(),
        ];

        $this->setUser($user);

        return $this->jwt->generateJwt($payload);
    }

    public function attempt($credentials)
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            return $this->fromUser($user);
        }

        return false;
    }

    public function validate(array $credentials = [])
    {
        return $this->attempt($credentials);
    }

    protected function hasValidCredentials($user, $credentials)
    {
        return ! is_null($user) && $this->provider->validateCredentials($user, $credentials);
    }
}
