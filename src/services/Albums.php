<?php

namespace SpotifyWebAPI\Services;

use \SpotifyWebAPI\SpotifyPagination;

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
        $this->setQueryString(['ids' => $ids_string]);
        $this->setRequestType('GET');
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
        $this->setRequestType('GET');
        $this->setAction(str_replace('{id}', $id, self::GET_ALBUM_TRACKS));
        SpotifyPagination::setHasPagination(true);
        return $this;
    }

    /**
     * Get an Album
     * Authorization - Required
     * @param string $id Id of album.
     */
    public function getAlbum($id)
    {
        $this->setRequestType('GET');
        $this->setAction(str_replace('{id}', $id, self::GET_ALBUM));
        return $this;
    }

    private function setRequestType($method)
    {
        $this->method = $method;
    }

    public function getRequestType()
    {
        return $this->method;
    }

    private function setQueryString($params)
    {
        $this->params = $params;
    }

    public function getQueryString()
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