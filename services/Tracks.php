<?php

namespace SpotifyWebAPI\Services;

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
    public function getAudioAnalysis($id)
    {
        $this->setConnectionMethod('GET');
        $this->action = str_replace('{id}', $id, self::GET_AUDIO_ANALYSIS); 
    }

    /**
     * Get Audio Features for a Track
     * Authorization - Required
     * @param string $id - Id of the track.
     */
    public function getAudioFeatures($id)
    {
        $this->setConnectionMethod('GET');
        $this->action = str_replace('{id}', $id, self::GET_AUDIO_FEATURES); 
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
        $this->action = self::GET_AUDIOS_FEATURES;
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
        $this->action = self::GET_TRACKS;
    }

    /**
     * Get Several Tracks
     * Authorization - Required
     * @param string $id - Id of the track.
     */
    public function getTrack($id)
    {
        $this->setConnectionMethod('GET');
        $this->action = str_replace('{id}', $id, self::GET_TRACK); 
    }
}