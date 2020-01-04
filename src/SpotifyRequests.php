<?php

namespace SpotifyWebAPI;

/*
 * @author Kiril Kirkov
 * Spotify Service Api Connection
 */

class SpotifyRequests
{
    const GET_ALBUMS = '/v1/albums';
    const GET_ALBUM = '/v1/albums/{id}';
    const GET_ALBUM_TRACKS = '/v1/albums/{id}/tracks';

    protected function getAlbums()
    {
        $this->action = self::GET_ALBUMS;
        return $this;
    }

    protected function getAlbum($id)
    {
        $this->action = str_replace('{id}', $id, self::GET_ALBUM);
        return $this;
    }

    protected function getAlbumTracks($id)
    {
        $this->action = str_replace('{id}', $id, self::GET_ALBUM_TRACKS);
        return $this;
    }
}