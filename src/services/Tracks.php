<?php

namespace SpotifyWebAPI\Services;

/**
 * @author Kiril Kirkov
 * Spotify Tracks Service
 */

class Tracks implements InterfaceSpotifyService
{
    const GET_AUDIO_ANALYSIS = '/v1/audio-analysis/{id}';
    const GET_AUDIO_FEATURES = '/v1/audio-features/{id}';
    const GET_AUDIOS_FEATURES = '/v1/audio-features';
    const GET_TRACKS = '/v1/tracks';
    const GET_TRACK = '/v1/tracks/{id}';

    private $method;
    private $params;
    private $action;

    /**
     * Get Audio Analysis for a Track
     * Authorization - Required
     * @param string $id - Id of the track.
     */
    public function getAudioAnalysis($id)
    {
        $this->setConnectionMethod('GET');
        $this->setAction(str_replace('{id}', $id, self::GET_AUDIO_ANALYSIS)); 
    }

    /**
     * Get Audio Features for a Track
     * Authorization - Required
     * @param string $id - Id of the track.
     */
    public function getAudioFeatures($id)
    {
        $this->setConnectionMethod('GET');
        $this->setAction(str_replace('{id}', $id, self::GET_AUDIO_FEATURES)); 
    }

    /**
     * Get Audio Features for Several Tracks
     * Authorization - Required
     * @param string $ids - Ids of the tracks.
     */
    public function getAudiosFeatures($ids)
    {
        $ids = (array)$ids;
        $ids_string = implode(',', $ids);
        $this->setConnectionParams(['ids' => $ids_string]);
        $this->setConnectionMethod('GET');
        $this->setAction(self::GET_AUDIOS_FEATURES);
    }

    /**
     * Get Several Tracks
     * Authorization - Required
     * @param string $ids - Ids of the tracks.
     */
    public function getTracks($ids)
    {
        $ids = (array)$ids;
        $ids_string = implode(',', $ids);
        $this->setConnectionParams(['ids' => $ids_string]);
        $this->setConnectionMethod('GET');
        $this->setAction(self::GET_TRACKS);
    }

    /**
     * Get Several Tracks
     * Authorization - Required
     * @param string $id - Id of the track.
     */
    public function getTrack($id)
    {
        $this->setConnectionMethod('GET');
        $this->setAction(str_replace('{id}', $id, self::GET_TRACK)); 
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