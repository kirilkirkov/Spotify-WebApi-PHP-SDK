<?php

namespace SpotifyWebAPI;

use SpotifyWebAPI\SpotifyConnection;
/*
 * @author Kiril Kirkov
 * Spotify Web Api
 */

class SpotifyWebApi1
{
    private $connection = null;
    
    public function __construct()
    {
        $this->connection = SpotifyConnection::getInstance();
    }
    
    /**
     * Spotify Set Generated Access Token
     *
     * @param string $acccessToken Valid access token.
     */
    public function setAccessToken($acccessToken)
    {
        $this->connection->setAccessToken($acccessToken);
        return $this;
    }

    /**
     * Set up client credentials.
     *
     * @param string $clientId The client ID.
     * @param string $clientSecret The client secret.
     * @param string $redirectUrl The redirect URL.
     */
    public function setCredentials($clientId, $clientSecret, $redirectUrl)
    {
        $this->connection->setCredentials($clientId, $clientSecret, $redirectUrl);
        return $this;
    }

    public function generateNewToken()
    {
        $this->connection->generateNewToken($clientId, $clientSecret, $redirectUrl);
    }

    public function __set($set, $val) 
    {
        $this->connection->{$set} = $val;
    }
}