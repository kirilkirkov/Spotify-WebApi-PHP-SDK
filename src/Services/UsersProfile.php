<?php

namespace SpotifyWebAPI\Services;

use SpotifyWebAPI\SpotifyPagination;

/**
 * @author Kiril Kirkov
 * Spotify UsersProfile Service
 */

class UsersProfile
{
    
    const GET_USER = '/v1/me';
    const GET_USERS = '/v1/users/{user_id}';

    /**
     * Get Current User's Profile
     * Authorization - Required
     */
    public static function getUser()
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'requestType' => 'GET',
            'uri' => self::GET_USER,
        ];
    }

    /**
     * Get a User's Profile
     * Authorization - Required
     * @param string $id - Id of the track.
     */
    public static function getUsers($user_id)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'requestType' => 'GET',
            'uri' => str_replace('{user_id}', $user_id, self::GET_USERS),
        ];
    }
}