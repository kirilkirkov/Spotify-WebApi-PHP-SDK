<?php

namespace SpotifyWebAPI\Services;

class Library
{
    const CHECK_SAVED_ALBUMS = '/v1/me/albums/contains';
    const CHECK_SAVED_TRACKS = '/v1/me/tracks/contains';
    const GET_MY_ALBUMS = '/v1/me/albums';
    const GET_MY_TRACKS = '/v1/me/tracks';
    
    /**
     * Check User's Saved Albums
     * Authorization - Required
     * @param array $ids The ids of albums to check.
     */
    public function checkSavedAlbums($ids)
    {
        $ids = (array)$ids;
        $ids_string = implode(',', $ids);
        $this->setConnectionParams(['ids' => $ids_string]);
        $this->setConnectionMethod('GET');
        $this->action = self::CHECK_SAVED_ALBUMS;
        return $this;
    }

    /**
     * Check User's Saved Tracks
     * Authorization - Required
     * @param array $ids The ids of albums to check.
     */
    public function checkSavedTracks($ids)
    {
        $ids = (array)$ids;
        $ids_string = implode(',', $ids);
        $this->setConnectionParams(['ids' => $ids_string]);
        $this->setConnectionMethod('GET');
        $this->action = self::CHECK_SAVED_TRACKS;
        return $this;
    }

    /**
     * Get Current User's Saved Albums
     * Authorization - Required
     */
    public function getMyAlbums()
    {
        $this->setConnectionMethod('GET');
        $this->action = self::GET_MY_ALBUMS;
        return $this;
    }

    /**
     * Get a User's Saved Tracks
     * Authorization - Required
     */
    public function getMyTracks()
    {
        $this->setConnectionMethod('GET');
        $this->action = self::GET_MY_TRACKS;
        return $this;
    }

    /**
     * Remove Albums for Current User
     * Authorization - Required
     * @param array $ids The ids of albums to remove.
     */
    public function removeAlbum($ids)
    {
        $ids = (array)$ids;
        $ids_string = implode(',', $ids);
        $this->setConnectionParams(['ids' => $ids_string]);
        $this->setConnectionMethod('DELETE');
        $this->action = self::GET_MY_ALBUMS;
        return $this;
    }

    /**
     * Remove User's Saved Tracks
     * Authorization - Required
     * @param array $ids The ids of tracks to remove.
     */
    public function removeTrack($ids)
    {
        $ids = (array)$ids;
        $ids_string = implode(',', $ids);
        $this->setConnectionParams(['ids' => $ids_string]);
        $this->setConnectionMethod('DELETE');
        $this->action = self::GET_MY_TRACKS;
        return $this;
    }

    /**
     * Save Albums for Current User
     * Authorization - Required
     * @param array $ids The ids of albums to add.
     */
    public function addAlbums($ids)
    {
        $ids = (array)$ids;
        $ids_string = implode(',', $ids);
        $this->setConnectionParams(['ids' => $ids_string]);
        $this->setConnectionMethod('PUT');
        $this->action = self::GET_MY_ALBUMS;
        return $this;
    }

    /**
     * Save Tracks for User
     * Authorization - Required
     * @param array $ids The ids of tracks to add.
     */
    public function addTracks($ids)
    {
        $ids = (array)$ids;
        $ids_string = implode(',', $ids);
        $this->setConnectionParams(['ids' => $ids_string]);
        $this->setConnectionMethod('PUT');
        $this->action = self::GET_MY_TRACKS;
        return $this;
    }
}