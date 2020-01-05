<?php

namespace SpotifyWebAPI\Services;

/**
 * @author Kiril Kirkov
 * Spotify Personalization Service
 */

class Personalization
{
    const GET_TOP = '/v1/me/top/{type}';

    /**
     * Get a User's Top Artists and Tracks
     * Authorization - Required
     * @param string $type The type of entity to return. Valid values: artists or tracks.
     */
    public function getTop($type)
    {
        $this->setConnectionMethod('GET');
        $this->action = str_replace('{type}', $type, self::GET_TOP);
        return $this;
    }
}