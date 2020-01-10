<?php

namespace SpotifyWebAPI\Services;

/**
 * @author Kiril Kirkov
 * Spotify Tracks Service
 */

class Tracks
{
    const GET_AUDIO_ANALYSIS = '/v1/audio-analysis/{id}';
    const GET_AUDIO_FEATURES = '/v1/audio-features/{id}';
    const GET_AUDIOS_FEATURES = '/v1/audio-features';
    const GET_TRACKS = '/v1/tracks';
    const GET_TRACK = '/v1/tracks/{id}';

    /**
     * Get Audio Analysis for a Track
     * Authorization - Required
     * @param string $id - Id of the track.
     */
    public static function getAudioAnalysis($id)
    {
        return [
            'requestType' => 'GET',
            'uri' => str_replace('{id}', $id, self::GET_AUDIO_ANALYSIS),
        ];
    }

    /**
     * Get Audio Features for a Track
     * Authorization - Required
     * @param string $id - Id of the track.
     */
    public static function getAudioFeatures($id)
    {
        return [
            'requestType' => 'GET',
            'uri' => str_replace('{id}', $id, self::GET_AUDIO_FEATURES),
        ];
    }

    /**
     * Get Audio Features for Several Tracks
     * Authorization - Required
     * @param string $ids - Ids of the tracks.
     */
    public static function getAudiosFeatures($ids)
    {
        return [
            'setQueryParams' => ['ids' => implode(',', $ids)],
            'requestType' => 'GET',
            'uri' => self::GET_AUDIOS_FEATURES,
        ];
    }

    /**
     * Get Several Tracks
     * Authorization - Required
     * @param string $ids - Ids of the tracks.
     */
    public static function getTracks(Array $ids)
    {
        return [
            'setQueryParams' => ['ids' => implode(',', $ids)],
            'requestType' => 'GET',
            'uri' => self::GET_TRACKS,
        ];
    }

    /**
     * Get Several Tracks
     * Authorization - Required
     * @param string $id - Id of the track.
     */
    public static function getTrack($id)
    {
        return [
            'requestType' => 'GET',
            'uri' => str_replace('{id}', $id, self::GET_TRACK),
        ];
    }
}