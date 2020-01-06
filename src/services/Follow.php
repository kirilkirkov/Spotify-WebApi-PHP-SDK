<?php

namespace SpotifyWebAPI\Services;

/**
 * @author Kiril Kirkov
 * Spotify Follow Service
 */

class Follow implements InterfaceSpotifyService
{
    const CHECK_USER_FOLLOWS = '/v1/me/following/contains';
    const CHECK_USER_FOLLOW_PLAYLIST = '/v1/playlists/{playlist_id}/followers/contains';
    const FOLLOW_PLAYLIST = '/v1/playlists/{playlist_id}/followers';
    const ME_FOLLOWING = '/v1/me/following';
    
    private $method;
    private $params;
    private $action;

    /**
     * Check if Current User Follows Artists or Users
     * Authorization - Required
     * @param string $type The ID type: either artist or user.
     * @param array $ids Array with ids
     */
    public function checkUserFollows($type, $ids)
    {
        $ids = (array)$ids;
        $ids_string = implode(',', $ids);
        $this->setConnectionParams(['type' => $type, 'ids' => $ids_string]);
        $this->setConnectionMethod('GET');
        $this->setAction(self::CHECK_USER_FOLLOWS);
        return $this;
    }

    /**
     * Check if Users Follow a Playlist
     * Authorization - Required
     * @param string $playlist_id The ID of playlist.
     * @param array $ids The ids of the users that you want to check to see if they follow the playlist.
     */
    public function checkUserFollowPlaylist($playlist_id, $ids)
    {
        $ids = (array)$ids;
        $ids_string = implode(',', $ids);
        $this->setConnectionParams(['ids' => $ids_string]);
        $this->setConnectionMethod('GET');
        $this->setAction(self::CHECK_USER_FOLLOW_PLAYLIST);
        return $this;
    }

    /**
     * Follow Artists or Users
     * Authorization - Required
     * @param string $type The ID type: either artist or user.
     * @param array $ids The ids of artists/users that want to start follow
     */
    public function followArtistsOrUsers($type, $ids)
    {
        $ids = (array)$ids;
        $ids_string = implode(',', $ids);
        $this->setConnectionParams(['type' => $type, 'ids' => $ids_string]);
        $this->setConnectionMethod('PUT');
        $this->setAction(self::ME_FOLLOWING);
        return $this;
    }

    /**
     * Follow a Playlist
     * Authorization - Required
     * @param string $playlist_id The ID of playlist that wants to follow.
     */
    public function followPaylist($playlist_id)
    {
        $this->setConnectionMethod('PUT');
        $this->setAction(str_replace('{playlist_id}', $playlist_id, self::FOLLOW_PLAYLIST));
        return $this;
    }

    /**
     * Get User's Followed Artists
     * Authorization - Required
     */
    public function whatIFollow()
    {
        // Currently only artist is supported from spotify
        $this->setConnectionParams(['type' => 'artist']);
        $this->setConnectionMethod('GET');
        $this->setAction(self::ME_FOLLOWING);
        return $this;
    }

    /**
     * Unfollow Artists or Users
     * Authorization - Required
     * @param string $type The ID type: either artist or user.
     * @param array $ids Optional. A comma-separated list of the artist or the user Spotify IDs.
     */
    public function unfollow($type, $ids)
    {
        $ids = (array)$ids;
        $ids_string = implode(',', $ids);
        $this->setConnectionParams(['type' => $type, 'ids' => $ids_string]);
        $this->setConnectionMethod('DELETE');
        $this->setAction(self::ME_FOLLOWING);
        return $this;
    }

    /**
     * Unfollow a Playlist
     * Authorization - Required
     * @param string $playlist_id The ID of playlist that wants to unfollow.
     */
    public function unfollowPlaylist($playlist_id)
    {
        $this->setConnectionMethod('DELETE');
        $this->setAction(str_replace('{playlist_id}', $playlist_id, self::FOLLOW_PLAYLIST));
        return $this;
    }

    private function setConnectionMethod($method)
    {
        $this->method = $method;
    }

    public function getConnectionMethod()
    {
        return $this->method;
    }

    private function setConnectionParams($params)
    {
        $this->params = $params;
    }

    public function getConnectionParams()
    {
        return $this->params;
    }

    private function setAction($action)
    {
        $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }
}