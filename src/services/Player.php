<?php

namespace SpotifyWebAPI\Services;

/**
 * @author Kiril Kirkov
 * Spotify Player Service
 */

class Player implements InterfaceSpotifyService
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

    private $method;
    private $params;
    private $action;

    /**
     * Get a User's Available Devices
     * Authorization - Required
     */
    public function getPlayerDevices()
    {
        $this->setConnectionMethod('GET');
        $this->setAction(self::PLAYER_DEVICES);
        return $this;
    }

    /**
     * Get Information About The User's Current Playback
     * Authorization - Required
     */
    public function getPlayer()
    {
        $this->setConnectionMethod('GET');
        $this->setAction(self::ME_PLAYER);
        return $this;
    }

    /**
     * Get Current User's Recently Played Tracks
     * Authorization - Required
     */
    public function getRecentPlayedTracks()
    {
        $this->setConnectionMethod('GET');
        $this->setAction(self::RECENT_PLAYED);
        return $this;
    }

    /**
     * Get the User's Currently Playing Track
     * Authorization - Required
     */
    public function getPlayingTrack()
    {
        $this->setConnectionMethod('GET');
        $this->setAction(self::PLAYING_TRACK);
        return $this;
    }

    /**
     * Pause a User's Playback
     * Authorization - Required
     */
    public function playerPause()
    {
        $this->setConnectionMethod('PUT');
        $this->setAction(self::PLAYER_PAUSE);
        return $this;
    }

    /**
     * Seek To Position In Currently Playing Track
     * Authorization - Required
     * 
     * @param int|string $position_ms The position in milliseconds to seek to. Must be a positive number
     */
    public function seekOnPosition($position_ms)
    {
        $this->setConnectionParams(['position_ms' => $position_ms]);
        $this->setConnectionMethod('PUT');
        $this->setAction(self::PLAYER_SEEK);
        return $this;
    }
    
    /**
     * Set Repeat Mode On User’s Playback
     * Authorization - Required
     * 
     * @param string $position_ms track, context or off track will repeat the current track.
     *  context will repeat the current context.
     *  off will turn repeat off.
     */
    public function setRepeatMode($state)
    {
        $this->setConnectionParams(['state' => $state]);
        $this->setConnectionMethod('PUT');
        $this->setAction(self::REPEAT_MODE);
        return $this;
    }

    /**
     * Set Volume For User's Playback
     * Authorization - Required
     * 
     * @param int $volume_percent The volume to set. Must be a value from 0 to 100 inclusive. 
     */
    public function setPlayerVolume($volume_percent)
    {
        $this->setConnectionParams(['volume_percent' => $volume_percent]);
        $this->setConnectionMethod('PUT');
        $this->setAction(self::PLAYER_VOLUME);
        return $this;
    }

    /**
     * Skip User’s Playback To Next Track
     * Authorization - Required
     * 
     */
    public function playerGoNext()
    {
        $this->setConnectionMethod('POST');
        $this->setAction(self::PLAYER_NEXT);
        return $this;
    }

    /**
     * Skip User’s Playback To Previous Track
     * Authorization - Required
     * 
     */
    public function playerGoPrevious()
    {
        $this->setConnectionMethod('POST');
        $this->setAction(self::PLAYER_PREVIOUS);
        return $this;
    }

    /**
     * Start/Resume a User's Playback
     * Authorization - Required
     * 
     */
    public function playerPlay()
    {
        $this->setConnectionMethod('PUT');
        $this->setAction(self::PLAYER_PLAY);
        return $this;
    }

    /**
     * Toggle Shuffle For User’s Playback
     * Authorization - Required
     * @param string $state - true : Shuffle user’s playback, false : Do not shuffle user’s playback
     */
    public function playerShuffle($state)
    {
        $this->setConnectionParams(['state' => $state]);
        $this->setConnectionMethod('PUT');
        $this->setAction(self::PLAYER_SHUFFLE);
        return $this;
    }

    /**
     * Transfer a User's Playback
     * Authorization - Required
     * @param array $device_id - Device id to be started/transferred.
     */
    public function transferPlayback($device_id)
    {
        $device_ids = json_encode(['device_ids' => [$device_id]]);
        $this->setConnectionParams(['device_ids' => $device_ids]);
        $this->setConnectionMethod('PUT');
        $this->setAction(self::PLAYER_TRANSFER);
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