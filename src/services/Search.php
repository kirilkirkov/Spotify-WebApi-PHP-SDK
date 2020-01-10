<?php

namespace SpotifyWebAPI\Services;

use SpotifyWebAPI\SpotifyPagination;

/**
 * @author Kiril Kirkov
 * Spotify Search Service
 */

class Search
{
    const SEARCH = '/v1/search';

    /**
     * Search for an Item
     * Authorization - Required
     * @param string $q - Search query keywords and optional field filters and operators.
     * @param string $type - A comma-separated list of item types to search across. Valid types are: album , artist, playlist, and track.
     */
    public static function search($q, $type)
    {
        SpotifyPagination::setHasPagination(true);
        return [
            'setQueryParams' => ['q' => $q, 'type' => $type],
            'requestType' => 'GET',
            'uri' => self::SEARCH,
        ];
    }
}