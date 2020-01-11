<?php

namespace SpotifyWebAPI\Services;

use SpotifyWebAPI\SpotifyPagination;

/**
 * @author Kiril Kirkov
 * Spotify Player Service
 */

class Player
{
    const PLAYER_DEVICES = '/v1/me/player/devices';
    const ME_PLAYER = '/v1/me/player';
    const RECENT_PLAYED = '/v1/me/player/recently-played';
    const PLAYING_TRACK = '/v1/me/player/currently-playing';
    const PLAYER_PAUSE = '/v1/me/player/pause';
    const PLAYER_SEEK = '/v1/me/player/seek';
    const REPEAT_MODE = '/v1/me/player/repeat';
    const PLAYER_VOLUME = '/v1/me/player/volume';
    const PLAYER_NEXT = '/v1/me/player/next';
    const PLAYER_PREVIOUS = '/v1/me/player/previous';
    const PLAYER_PLAY = '/v1/me/player/play';
    const PLAYER_SHUFFLE = '/v1/me/player/shuffle';
    const PLAYER_TRANSFER = '/v1/me/player';

    /**
     * Get a User's Available Devices
     * Authorization - Required
     */
    public static function getPlayerDevices()
    {
        return [
            'requestType' => 'GET',
            'uri' => self::PLAYER_DEVICES,
        ];
    }

    /**
     * Get Information About The User's Current Playback
     * Authorization - Required
     */
    public static function getPlayer()
    {
        return [
            'requestType' => 'GET',
            'uri' => self::ME_PLAYER,
        ];
    }

    /**
     * Get Current User's Recently Played Tracks
     * Authorization - Required
     */
    public static function getRecentPlayedTracks()
    {
        SpotifyPagination::setHasPagination(true);
        return [
            'requestType' => 'GET',
            'uri' => self::RECENT_PLAYED,
        ];
    }

    /**
     * Get the User's Currently Playing Track
     * Authorization - Required
     */
    public static function getPlayingTrack()
    {
        return [
            'requestType' => 'GET',
            'uri' => self::PLAYING_TRACK,
        ];
    }

    /**
     * Pause a User's Playback
     * Authorization - Required
     */
    public static function playerPause()
    {
        return [
            'requestType' => 'PUT',
            'uri' => self::PLAYER_PAUSE,
        ];
    }

    /**
     * Seek To Position In Currently Playing Track
     * Authorization - Required
     * 
     * @param int|string $position_ms The position in milliseconds to seek to. Must be a positive number
     */
    public static function seekOnPosition(Int $position_ms)
    {
        return [
            'queryString' => ['position_ms' => $position_ms],
            'requestType' => 'PUT',
            'uri' => self::PLAYER_SEEK,
        ];
    }
    
    /**
     * Set Repeat Mode On User’s Playback
     * Authorization - Required
     * 
     * @param string $state
     */
    public static function setRepeatMode($state)
    {
        return [
            'queryString' => ['state' => $state],
            'requestType' => 'PUT',
            'uri' => self::REPEAT_MODE,
        ];
    }

    /**
     * Set Volume For User's Playback
     * Authorization - Required
     * 
     * @param int $volume_percent The volume to set. Must be a value from 0 to 100 inclusive. 
     */
    public static function setPlayerVolume($volume_percent)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'queryString' => ['volume_percent' => $volume_percent],
            'requestType' => 'PUT',
            'uri' => self::PLAYER_VOLUME,
        ];
    }

    /**
     * Skip User’s Playback To Next Track
     * Authorization - Required
     * 
     */
    public static function playerGoNext()
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'requestType' => 'POST',
            'uri' => self::PLAYER_NEXT,
        ];
    }

    /**
     * Skip User’s Playback To Previous Track
     * Authorization - Required
     * 
     */
    public static function playerGoPrevious()
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'requestType' => 'POST',
            'uri' => self::PLAYER_PREVIOUS,
        ];
    }

    /**
     * Start/Resume a User's Playback
     * Authorization - Required
     * 
     */
    public static function playerPlay()
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'requestType' => 'PUT',
            'uri' => self::PLAYER_PLAY,
        ];
    }

    /**
     * Toggle Shuffle For User’s Playback
     * Authorization - Required
     * @param string $state - true : Shuffle user’s playback, false : Do not shuffle user’s playback
     */
    public static function playerShuffle($state)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'queryString' => ['state' => $state],
            'requestType' => 'PUT',
            'uri' => self::PLAYER_SHUFFLE,
        ];
    }

    /**
     * Transfer a User's Playback
     * Authorization - Required
     * @param array $device_id - Device id to be started/transferred.
     */
    public static function transferPlayback($device_id)
    {
        SpotifyPagination::setHasPagination(false);
        return [
            'queryString' => ['device_ids' => json_encode(['device_ids' => [$device_id]])],
            'requestType' => 'PUT',
            'uri' => self::PLAYER_TRANSFER,
        ];
    }
}