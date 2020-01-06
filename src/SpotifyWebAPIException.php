<?php

namespace SpotifyWebAPI;

class SpotifyWebAPIException extends \Exception
{
    const TOKEN_EXPIRED = 'The access token expired';

    public function hasExpiredToken()
    {
        return $this->getMessage() === self::TOKEN_EXPIRED;
    }
}