<?php

namespace SpotifyWebAPI\Services;

/**
 * @author Kiril Kirkov
 * Spotify Albums Service
 */

class Albums implements InterfaceSpotifyService
{
    const GET_ALBUMS = '/v1/albums';
    const GET_ALBUM_TRACKS = '/v1/albums/{id}/tracks';
    const GET_ALBUM = '/v1/albums/{id}';

    private $method;
    private $params;
    private $action;

    /**
     * Get Several Albums
     * Authorization - Required
     * @param array $ids Array with ids of ablums.
     */
    public function getAlbums($ids)
    {
        $ids = (array)$ids;
        $ids_string = implode(',', $ids);
        $this->setConnectionParams(['ids' => $ids_string]);
        $this->setConnectionMethod('GET');
        $this->setAction(self::GET_ALBUMS);
        return $this;
    }

    /**
     * Get an Album's Tracks
     * Authorization - Required
     * @param string $id Id of album.
     */
    public function getTracks($id)
    {
        $this->setConnectionMethod('GET');
        $this->setAction(str_replace('{id}', $id, self::GET_ALBUM_TRACKS));
        return $this;
    }

    /**
     * Get an Album
     * Authorization - Required
     * @param string $id Id of album.
     */
    public function getAlbum($id)
    {
        $this->setConnectionMethod('GET');
        $this->setAction(str_replace('{id}', $id, self::GET_ALBUM));
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