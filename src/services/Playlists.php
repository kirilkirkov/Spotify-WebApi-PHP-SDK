<?php

namespace SpotifyWebAPI\Services;

class Playlists
{

    const PLAYLIST_TRACKS = '/v1/playlists/{playlist_id}/tracks';
    const GET_PLAYLIST = '/v1/playlists/{playlist_id}';
    const USERS_PLAYLISTS = '/v1/users/{user_id}/playlists';
    const GET_PLAYLISTS = '/v1/me/playlists';
    const PLAYLIST_IMAGES = '/v1/playlists/{playlist_id}/images';

    /**
     * Add Tracks to a Playlist
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     * @param array $uris - Uris of tracks to add. Example one uri: spotify:track:4iV5W9uYEdYUVa79Axb7Rh
     */
    public function addTrackToPlaylist($playlist_id, $uris)
    {
        $uris = (array)$uris;
        $uris_string = implode(',', $uris);
        $this->setConnectionParams(['uris' => $uris_string]);
        $this->setConnectionMethod('POST');
        $this->action = str_replace('{playlist_id}', $playlist_id, self::PLAYLIST_TRACKS);
        return $this;
    }

    /**
     * Change a Playlist's Details
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     * @param array $values - name - string, public - Boolean, collaborative - Boolen, description - string
     */
    public function updatePlaylist($playlist_id, $values)
    {
        $this->setConnectionParams($values);
        $this->setConnectionMethod('PUT');
        $this->action = str_replace('{playlist_id}', $playlist_id, self::GET_PLAYLIST);
        return $this;
    }

    /**
     * Create a Playlist
     * Authorization - Required
     * @param string $user_id - The user’s Spotify user ID.
     * @param array $values - name - string, public - Boolean, collaborative - Boolen, description - string
     */
    public function createPlaylist($user_id, $values)
    {
        $this->setConnectionParams($values);
        $this->setConnectionMethod('POST');
        $this->action = str_replace('{user_id}', $user_id, self::USERS_PLAYLISTS);
        return $this;
    }

    /**
     * Get a List of Current User's Playlists
     * Authorization - Required
     */
    public function getPlaylists()
    {
        $this->setConnectionMethod('GET');
        $this->action = self::GET_PLAYLISTS;
        return $this;
    }

    /**
     * Get a List of a User's Playlists
     * Authorization - Required
     * @param string $user_id - The user’s Spotify user ID.
     */
    public function getUsersPlaylists($user_id)
    {
        $this->setConnectionMethod('GET');
        $this->action = str_replace('{user_id}', $user_id, self::USERS_PLAYLISTS);
        return $this;
    }

    /**
     * Get a Playlist Cover Image
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     */
    public function getPlaylistCover($playlist_id)
    {
        $this->setConnectionMethod('GET');
        $this->action = str_replace('{playlist_id}', $playlist_id, self::PLAYLIST_IMAGES);
        return $this;
    }

    /**
     * Get a Playlist
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     */
    public function getPlaylist($playlist_id)
    {
        $this->setConnectionMethod('GET');
        $this->action = str_replace('{playlist_id}', $playlist_id, self::GET_PLAYLIST);
        return $this;
    }

    /**
     * Get a Playlist's Tracks
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     */
    public function getPlaylistTracks($playlist_id)
    {
        $this->setConnectionMethod('GET');
        $this->action = str_replace('{playlist_id}', $playlist_id, self::PLAYLIST_TRACKS);
        return $this;
    }

    /**
     * Get a Playlist's Tracks
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     * @param array $tracks - Uris of tracks to add. Example one uri: spotify:track:4iV5W9uYEdYUVa79Axb7Rh
     */
    public function playlistRemoveTracks($playlist_id, $tracks)
    {
        $tracks = (array)$uris;
        $tracks_string = implode(',', $tracks);
        $this->setConnectionParams(['tracks' => $tracks_string]);
        $this->setConnectionMethod('DELETE');
        $this->action = str_replace('{playlist_id}', $playlist_id, self::PLAYLIST_TRACKS);
        return $this;
    }

    /**
     * Reorder a Playlist's Tracks
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     * @param array $params - range_start: integer, range_length: integer, insert_before: integer, snapshot_id: string
     */
    public function reorderPlaylistTracks($playlist_id, $params)
    {
        $this->setConnectionParams($params);
        $this->setConnectionMethod('PUT');
        $this->action = str_replace('{playlist_id}', $playlist_id, self::PLAYLIST_TRACKS);
        return $this;
    }

    /**
     * Replace a Playlist's Tracks
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     * @param array $uris - Uris of tracks to add. Example one uri: spotify:track:4iV5W9uYEdYUVa79Axb7Rh
     */
    public function replacePlaylistTracks($playlist_id, $uris)
    {
        $uris = (array)$uris;
        $uris_string = implode(',', $uris);
        $this->setConnectionParams(['uris' => $uris_string]);
        $this->setConnectionMethod('PUT');
        $this->action = str_replace('{playlist_id}', $playlist_id, self::PLAYLIST_TRACKS); 
    }

    /**
     * Replace a Playlist's Tracks
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     * @param string $image - Base64 encoded JPEG image data, maximum payload size is 256 KB
     */
    public function uploadPlaylistCover($playlist_id, $image)
    {
        $this->setConnectionParams($image);
        $this->setRequestContentType('image/jpeg');
        $this->setConnectionMethod('PUT');
        $this->action = str_replace('{playlist_id}', $playlist_id, self::PLAYLIST_IMAGES); 
    }
}