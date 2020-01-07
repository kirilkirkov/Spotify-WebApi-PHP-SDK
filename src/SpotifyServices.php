<?php

namespace SpotifyWebAPI;

/**
 * @author Kiril Kirkov
 * Spotify Service Api Prepared Requests
 */

class SpotifyServices
{
    const AUTHORIZE = '/authorize';
    const TOKEN = '/api/token';

    public static function albums()
    {
        return new \SpotifyWebAPI\Services\Albums();
    }

    public static function artists()
    {
        return new \SpotifyWebAPI\Services\Artists();
    }

    public static function browse()
    {
        return new \SpotifyWebAPI\Services\Browse();
    }

    public static function follow()
    {
        return new \SpotifyWebAPI\Services\Follow();
    }

    public static function library()
    {
        return new \SpotifyWebAPI\Services\Library();
    }

    public static function personalization()
    {
        return new \SpotifyWebAPI\Services\Personalization();
    }

    public static function player()
    {
        return new \SpotifyWebAPI\Services\Player();
    }

    public static function playlists()
    {
        return new \SpotifyWebAPI\Services\Playlists();
    }

    public static function search()
    {
        return new \SpotifyWebAPI\Services\Search();
    }

    public static function tracks()
    {
        return new \SpotifyWebAPI\Services\Tracks();
    }

    public static function users()
    {
        return new \SpotifyWebAPI\Services\UsersProfile();
    }
        
    public static function authorize()
    {
        return [
            'requestType' => 'GET',
            'uri' => self::AUTHORIZE,
        ];
    }

    public static function token()
    {
        return [
            'requestType' => 'POST',
            'uri' => self::TOKEN,
        ];
    }
}