<?php

namespace SpotifyWebAPI;

class SpotifyWebAPIException extends \Exception
{
    const TOKEN_EXPIRED = 'The access token expired';
    const INVALID_CLIENT = 'invalid_client';

    public function hasExpiredToken()
    {
        return $this->getMessage() === self::TOKEN_EXPIRED;
    }

    public function invalidClient()
    {
        return $this->getMessage() === self::INVALID_CLIENT;
    }
}