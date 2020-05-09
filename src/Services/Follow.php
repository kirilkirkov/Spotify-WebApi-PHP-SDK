<?php

namespace SpotifyWebAPI\Services;

use SpotifyWebAPI\SpotifyPagination;

/**
 * @author Kiril Kirkov
 * Spotify Follow Service
 */

class Follow
{
    const CHECK_USER_FOLLOWS = '/v1/me/following/contains';
    const CHECK_USER_FOLLOW_PLAYLIST = '/v1/playlists/{playlist_id}/followers/contains';
    const FOLLOW_PLAYLIST = '/v1/playlists/{playlist_id}/followers';
    const ME_FOLLOWING = '/v1/me/following';

    /**
     * Check if Current User Follows Artists or Users
     * Authorization - Required
     * @param string $type The ID type: either artist or user.
     * @param array $ids Array with ids
     */
    public static function checkUserFollows($type, Array $ids)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'queryString' => ['type' => $type, 'ids' => implode(',', $ids)],
            'requestType' => 'GET',
            'uri' => self::CHECK_USER_FOLLOWS,
        ];
    }

    /**
     * Check if Users Follow a Playlist
     * Authorization - Required
     * @param string $playlist_id The ID of playlist.
     * @param array $ids The ids of the users that you want to check to see if they follow the playlist.
     */
    public static function checkUserFollowPlaylist($playlist_id, Array $ids)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'queryString' => ['ids' => implode(',', $ids)],
            'requestType' => 'GET',
            'uri' => self::CHECK_USER_FOLLOW_PLAYLIST,
        ];
    }

    /**
     * Follow Artists or Users
     * Authorization - Required
     * @param string $type The ID type: either artist or user.
     * @param array $ids The ids of artists/users that want to start follow
     */
    public static function followArtistsOrUsers($type, Array $ids)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'queryString' => ['type' => $type, 'ids' => implode(',', $ids)],
            'requestType' => 'PUT',
            'uri' => self::ME_FOLLOWING,
        ];
    }

    /**
     * Follow a Playlist
     * Authorization - Required
     * @param string $playlist_id The ID of playlist that wants to follow.
     */
    public static function followPaylist(String $playlist_id)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'requestType' => 'PUT',
            'uri' => str_replace('{playlist_id}', $playlist_id, self::FOLLOW_PLAYLIST),
        ];
    }

    /**
     * Get User's Followed Artists
     * Authorization - Required
     */
    public static function whatIFollow()
    {
        // Currently only artist is supported from spotify
        SpotifyPagination::setHasPagination(true);
        return [
            'queryString' => ['type' => 'artist'],
            'requestType' => 'GET',
            'uri' => self::ME_FOLLOWING,
        ];
    }

    /**
     * Unfollow Artists or Users
     * Authorization - Required
     * @param string $type The ID type: either artist or user.
     * @param array $ids Optional. A comma-separated list of the artist or the user Spotify IDs.
     */
    public static function unfollow($type, Array $ids)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'queryString' => ['type' => $type, 'ids' => implode(',', $ids)],
            'requestType' => 'DELETE',
            'uri' => self::ME_FOLLOWING,
        ];
    }

    /**
     * Unfollow a Playlist
     * Authorization - Required
     * @param string $playlist_id The ID of playlist that wants to unfollow.
     */
    public static function unfollowPlaylist(String $playlist_id)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'requestType' => 'DELETE',
            'uri' => str_replace('{playlist_id}', $playlist_id, self::FOLLOW_PLAYLIST),
        ];
    }
}