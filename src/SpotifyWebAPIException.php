<?php

namespace SpotifyWebAPI;

class SpotifyWebAPIException extends \Exception
{
    const TOKEN_EXPIRED = 'The access token expired';
    const INVALID_CLIENT = 'invalid_client';
    const RATE_LIMIT_STATUS = 429;

    /**
     * @return bool
     */
    public function hasExpiredToken()
    {
        return $this->getMessage() === self::TOKEN_EXPIRED;
    }
    /**
     * @return bool
     */
    public function invalidClient()
    {
        return $this->getMessage() === self::INVALID_CLIENT;
    }

    /**
     * @return bool
     */
    public function isRateLimited()
    {
        return $this->getCode() === self::RATE_LIMIT_STATUS;
    }
}