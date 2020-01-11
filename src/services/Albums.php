<?php

namespace SpotifyWebAPI\Services;

use \SpotifyWebAPI\SpotifyPagination;

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
    public static function getAlbums(Array $ids)
    {
        SpotifyPagination::setHasPagination(false);
        $ids_string = implode(',', $ids);
        return [
            'queryString' => ['ids' => $ids_string],
            'requestType' => 'GET',
            'uri' => self::GET_ALBUMS,
        ];
    }

    /**
     * Get an Album's Tracks
     * Authorization - Required
     * @param string $id Id of album.
     */
    public static function getTracks($id)
    {
        SpotifyPagination::setHasPagination(true);
        return [
            'requestType' => 'GET',
            'uri' => str_replace('{id}', $id, self::GET_ALBUM_TRACKS),
        ];
    }

    /**
     * Get an Album
     * Authorization - Required
     * @param string $id Id of album.
     */
    public static function getAlbum($id)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'requestType' => 'GET',
            'uri' => str_replace('{id}', $id, self::GET_ALBUM),
        ];
    }
}