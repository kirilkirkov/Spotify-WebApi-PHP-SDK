<?php

namespace SpotifyWebAPI;

/**
 * @author Kiril Kirkov
 * @link https://github.com/kirilkirkov/Spotify-WebApi-PHP-SDK
 * @version 1.2
 * 
 * Spotify Web Api
 */

class SpotifyWebApi
{
    private $connection;

    /**
     * @param array $credentials User credentials
     * - Client Id.
     * - Client Secret.
     * - Refresh Token.
     * - (Optional) Access Token.
     */
    public function __construct($credentials = [])
    {
        $this->connection = new SpotifyWebAPI\SpotifyConnection();
        if(!empty($credentials)) {
            $this->setCredentials($credentials);
        }
    }

    private function setCredentials($credentials)
    {
        if(isset($credentials['accessToken'])) {
            $this->connection->setAccessToken($credentials['accessToken']);
        }
        if(isset($credentials['refreshToken'])) {
            $this->connection->setRefreshToken($credentials['refreshToken']);
        }
    }
}