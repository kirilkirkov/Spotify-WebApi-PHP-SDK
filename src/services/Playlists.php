<?php

namespace SpotifyWebAPI\Services;

use SpotifyWebAPI\SpotifyPagination;

/**
 * @author Kiril Kirkov
 * Spotify Playlists Service
 */

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
    public static function addTrackToPlaylist($playlist_id, Array $uris)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'setQueryParams' => ['uris' => implode(',', $uris)],
            'requestType' => 'POST',
            'uri' => str_replace('{playlist_id}', $playlist_id, self::PLAYLIST_TRACKS),
        ];
    }

    /**
     * Change a Playlist's Details
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     * @param array $values - name - string, public - Boolean, collaborative - Boolen, description - string
     */
    public static function updatePlaylist(String $playlist_id, Array $values)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'setQueryParams' => $values,
            'requestType' => 'PUT',
            'uri' => str_replace('{playlist_id}', $playlist_id, self::GET_PLAYLIST),
        ];
    }

    /**
     * Create a Playlist
     * Authorization - Required
     * @param string $user_id - The user’s Spotify user ID.
     * @param array $values - name - string, public - Boolean, collaborative - Boolen, description - string
     */
    public static function createPlaylist(String $user_id, Array $values)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'setQueryParams' => $values,
            'requestType' => 'POST',
            'uri' => str_replace('{user_id}', $user_id, self::USERS_PLAYLISTS),
        ];
    }

    /**
     * Get a List of Current User's Playlists
     * Authorization - Required
     */
    public static function getPlaylists()
    {
        SpotifyPagination::setHasPagination(true);
        return [
            'requestType' => 'GET',
            'uri' => self::GET_PLAYLISTS,
        ];
    }

    /**
     * Get a List of a User's Playlists
     * Authorization - Required
     * @param string $user_id - The user’s Spotify user ID.
     */
    public static function getUsersPlaylists($user_id)
    {
        SpotifyPagination::setHasPagination(true);
        return [
            'requestType' => 'GET',
            'uri' => str_replace('{user_id}', $user_id, self::USERS_PLAYLISTS),
        ];
    }

    /**
     * Get a Playlist Cover Image
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     */
    public static function getPlaylistCover($playlist_id)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'requestType' => 'GET',
            'uri' => str_replace('{playlist_id}', $playlist_id, self::PLAYLIST_IMAGES),
        ];
    }

    /**
     * Get a Playlist
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     */
    public static function getPlaylist($playlist_id)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'requestType' => 'GET',
            'uri' => str_replace('{playlist_id}', $playlist_id, self::GET_PLAYLIST),
        ];
    }

    /**
     * Get a Playlist's Tracks
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     */
    public static function getPlaylistTracks(String $playlist_id)
    {
        SpotifyPagination::setHasPagination(true);
        return [
            'requestType' => 'GET',
            'uri' => str_replace('{playlist_id}', $playlist_id, self::PLAYLIST_TRACKS),
        ];
    }

    /**
     * Get a Playlist's Tracks
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     * @param array $tracks - Uris of tracks to add. Example one uri: spotify:track:4iV5W9uYEdYUVa79Axb7Rh
     */
    public static function playlistRemoveTracks(String $playlist_id, Array $tracks)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'setQueryParams' => ['tracks' => implode(',', $tracks)],
            'requestType' => 'DELETE',
            'uri' => str_replace('{playlist_id}', $playlist_id, self::PLAYLIST_TRACKS),
        ];
    }

    /**
     * Reorder a Playlist's Tracks
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     * @param array $params - range_start: integer, range_length: integer, insert_before: integer, snapshot_id: string
     */
    public static function reorderPlaylistTracks(String $playlist_id, Array $params)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'setQueryParams' => $params,
            'requestType' => 'PUT',
            'uri' => str_replace('{playlist_id}', $playlist_id, self::PLAYLIST_TRACKS),
        ];
    }

    /**
     * Replace a Playlist's Tracks
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     * @param array $uris - Uris of tracks to add. Example one uri: spotify:track:4iV5W9uYEdYUVa79Axb7Rh
     */
    public static function replacePlaylistTracks(String $playlist_id, Array $uris)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'setQueryParams' => ['uris' => implode(',', $uris)],
            'requestType' => 'PUT',
            'uri' => str_replace('{playlist_id}', $playlist_id, self::PLAYLIST_TRACKS),
        ];
    }

    /**
     * Replace a Playlist's Tracks
     * Authorization - Required
     * @param string $playlist_id - Playlist id.
     * @param string $image - Base64 encoded JPEG image data, maximum payload size is 256 KB
     */
    public static function uploadPlaylistCover(String $playlist_id, $image)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'setQueryParams' => $image,
            'requestType' => 'PUT',
            'uri' => str_replace('{playlist_id}', $playlist_id, self::PLAYLIST_IMAGES),
        ];
    }
}