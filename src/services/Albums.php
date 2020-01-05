<?php

namespace SpotifyWebAPI\Services;

/**
 * @author Kiril Kirkov
 * Spotify Albums Service
 */

class Albums
{
    const GET_ALBUMS = '/v1/albums';
    const GET_ALBUM_TRACKS = '/v1/albums/{id}/tracks';
    const GET_ALBUM = '/v1/albums/{id}';

    /**
     * Get Several Albums
     * Authorization - Required
     * @param array $ids Array with ids of ablums.
     */
    public function get($ids)
    {
        $ids = (array)$ids;
        $ids_string = implode(',', $ids);
        $this->setConnectionParams(['ids' => $ids_string]);
        $this->setConnectionMethod('GET');
        $this->action = self::GET_ALBUMS;
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
        $this->action = str_replace('{id}', $id, self::GET_ALBUM_TRACKS);
        return $this;
    }

    /**
     * Get an Album
     * Authorization - Required
     * @param string $id Id of album.
     */
    public function getOne($id)
    {
        $this->setConnectionMethod('GET');
        $this->action = str_replace('{id}', $id, self::GET_ALBUM);
        return $this;
    }
}