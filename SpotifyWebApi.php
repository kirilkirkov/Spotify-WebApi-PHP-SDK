<?php

namespace SpotifyWebAPI;

use SpotifyWebAPI\SpotifyConnection;
/**
 * @author Kiril Kirkov
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

    /**
     * This function send prepared request and return parsed response
     */
    public function getResult()
    {
        return $this->sendRequest()->getResponse();
    }
}