<?php

namespace SpotifyWebAPI;

use SpotifyWebAPI\SpotifyConnection;

/**
 * @author Kiril Kirkov
 * @link https://github.com/kirilkirkov/Spotify-WebApi-PHP-SDK
 * @version 1.2
 * 
 * Spotify Web Api
 */

class SpotifyWebApi extends SpotifyConnection
{
    public function __construct($accessToken = null)
    {
        if(!is_null($accessToken)) {
            $this->setAccessToken($accessToken);
        }
    }
   
}