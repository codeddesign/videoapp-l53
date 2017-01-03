<?php

namespace App\JWT;

use Firebase\JWT\JWT as FirebaseJWT;
use Illuminate\Http\Request;
use Illuminate\Log\Writer as Log;

class JWT
{
    protected $header = 'authorization';
    protected $prefix = 'bearer';

    protected $secret;
    protected $log;

    public function __construct(Log $log)
    {
        $this->secret = config('app.jwt_secret');
        $this->log    = $log;
    }

    public function generateJwt($payload)
    {
        return FirebaseJWT::encode($payload, $this->secret);
    }

    public function getPayloadFromRequest(Request $request)
    {
        $token = $this->parseHeader($request) ?: $this->parseParameter($request);

        if (! $token) {
            return;
        }

        return $this->decodeJwt($token);
    }

    public function decodeJwt($token)
    {
        $payload = null;
        try {
            $payload = (array) FirebaseJWT::decode($token, $this->secret, ['HS256']);
        } catch (\Exception $e) {
            $this->log->notice("Firebase JWT could not decode token ({$e->getMessage()}): {$token}");
        }

        return $payload;
    }

    protected function parseHeader(Request $request)
    {
        $header = $request->header($this->header);

        if ($header && stripos($header, $this->prefix) === 0) {
            return trim(str_ireplace($this->prefix, '', $header));
        }
    }

    protected function parseParameter(Request $request)
    {
        $token = $request->get('jwt');

        return $token;
    }
}
