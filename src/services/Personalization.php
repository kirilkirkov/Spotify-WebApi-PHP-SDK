<?php

namespace SpotifyWebAPI\Services;

/**
 * @author Kiril Kirkov
 * Spotify Personalization Service
 */

class Personalization implements InterfaceSpotifyService
{
    const GET_TOP = '/v1/me/top/{type}';

    private $method;
    private $params;
    private $action;

    /**
     * Get a User's Top Artists and Tracks
     * Authorization - Required
     * @param string $type The type of entity to return. Valid values: artists or tracks.
     */
    public function getTop($type)
    {
        $this->setConnectionMethod('GET');
        $this->setAction(str_replace('{type}', $type, self::GET_TOP));
        return $this;
    }

    private function setConnectionMethod($method)
    {
        $this->method = $method;
    }

    public function getConnectionMethod()
    {
        return $this->method;
    }

    private function setConnectionParams($params)
    {
        $this->params = $params;
    }

    public function getConnectionParams()
    {
        return $this->params;
    }

    private function setAction($action)
    {
        $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }
}