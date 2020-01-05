<?php

namespace SpotifyWebAPI;

/**
 * @author Kiril Kirkov
 * Spotify Service Api Connection Prepared Requests
 */

class SpotifyRequests
{
    const AUTHORIZE = '/authorize';
    const TOKEN = '/api/token';

    public function albums()
    {
        return new \SpotifyWebAPI\Services\Albums();
    }

    public function artists()
    {
        return new \SpotifyWebAPI\Services\Artists();
    }

    public function browse()
    {
        return new \SpotifyWebAPI\Services\Browse();
    }

    public function follow()
    {
        return new \SpotifyWebAPI\Services\Follow();
    }

    public function library()
    {
        return new \SpotifyWebAPI\Services\Library();
    }

    public function personalization()
    {
        return new \SpotifyWebAPI\Services\Personalization();
    }

    public function player()
    {
        return new \SpotifyWebAPI\Services\Player();
    }

    public function playlists()
    {
        return new \SpotifyWebAPI\Services\Playlists();
    }

    public function search()
    {
        return new \SpotifyWebAPI\Services\Search();
    }

    public function tracks()
    {
        return new \SpotifyWebAPI\Services\Tracks();
    }

    public function users()
    {
        return new \SpotifyWebAPI\Services\UsersProfile();
    }
        
    public function authorize()
    {
        $this->setConnectionMethod('DELETE');
        $this->action = self::AUTHORIZE;
        return $this;
    }

    public function token()
    {
        $this->setConnectionMethod('POST');
        $this->action = self::TOKEN;
        return $this;
    }
}