<?php

namespace SpotifyWebAPI\Services;

/**
 * @author Kiril Kirkov
 * Spotify Search Service
 */

class Search implements InterfaceSpotifyService
{
    const SEARCH = '/v1/search';

    private $method;
    private $params;
    private $action;

    /**
     * Search for an Item
     * Authorization - Required
     * @param string $q - Search query keywords and optional field filters and operators.
     * @param string $type - A comma-separated list of item types to search across. Valid types are: album , artist, playlist, and track.
     */
    public function search($q, $type)
    {
        $this->setConnectionParams(['q' => $q, 'type' => $type]);
        $this->setConnectionMethod('GET');
        $this->setAction(self::SEARCH);
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