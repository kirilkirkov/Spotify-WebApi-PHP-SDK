<?php

namespace SpotifyWebAPI\Services;

use SpotifyWebAPI\SpotifyPagination;

/**
 * @author Kiril Kirkov
 * Spotify Library Service
 */

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
    public static function checkSavedAlbums(Array $ids)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'queryString' => ['ids' => implode(',', $ids)],
            'requestType' => 'GET',
            'uri' => self::CHECK_SAVED_ALBUMS,
        ];
    }

    /**
     * Check User's Saved Tracks
     * Authorization - Required
     * @param array $ids The ids of albums to check.
     */
    public static function checkSavedTracks(Array $ids)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'queryString' => ['ids' => implode(',', $ids)],
            'requestType' => 'GET',
            'uri' => self::CHECK_SAVED_TRACKS,
        ];
    }

    /**
     * Get Current User's Saved Albums
     * Authorization - Required
     */
    public static function getMyAlbums()
    {
        SpotifyPagination::setHasPagination(true);
        return [
            'requestType' => 'GET',
            'uri' => self::GET_MY_ALBUMS,
        ];
    }

    /**
     * Get a User's Saved Tracks
     * Authorization - Required
     */
    public static function getMyTracks()
    {
        SpotifyPagination::setHasPagination(true);
        return [
            'requestType' => 'GET',
            'uri' => self::GET_MY_TRACKS,
        ];
    }

    /**
     * Remove Albums for Current User
     * Authorization - Required
     * @param array $ids The ids of albums to remove.
     */
    public static function removeAlbum(Array $ids)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'queryString' => ['ids' => implode(',', $ids)],
            'requestType' => 'DELETE',
            'uri' => self::GET_MY_ALBUMS,
        ];
    }

    /**
     * Remove User's Saved Tracks
     * Authorization - Required
     * @param array $ids The ids of tracks to remove.
     */
    public static function removeTrack(Array $ids)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'queryString' => ['ids' => implode(',', $ids)],
            'requestType' => 'DELETE',
            'uri' => self::GET_MY_TRACKS,
        ];
    }

    /**
     * Save Albums for Current User
     * Authorization - Required
     * @param array $ids The ids of albums to add.
     */
    public static function addAlbums(Array $ids)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'queryString' => ['PUT' => implode(',', $ids)],
            'requestType' => 'DELETE',
            'uri' => self::GET_MY_ALBUMS,
        ];
    }

    /**
     * Save Tracks for User
     * Authorization - Required
     * @param array $ids The ids of tracks to add.
     */
    public static function addTracks(Array $ids)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'queryString' => ['PUT' => implode(',', $ids)],
            'requestType' => 'PUT',
            'uri' => self::GET_MY_TRACKS,
        ];
    }
}