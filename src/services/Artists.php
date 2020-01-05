<?php

namespace SpotifyWebAPI\Services;

class Artists
{
    const GET_ARTIST = '/v1/artists/{id}';
    const GET_ARTIST_ALBUMS = '/v1/artists/{id}/albums';
    const GET_ARTIST_TOP_TRACKS = '/v1/artists/{id}/top-tracks';
    const GET_ARTIST_RELATED_ARTISTS = '/v1/artists/{id}/related-artists';
    const GET_ARTISTS = '/v1/artists';

    /**
     * Get an Artist
     * Authorization - Required
     * @param string $id Id of artist.
     */
    public function getArtist($id)
    {
        $this->setConnectionMethod('GET');
        $this->action = str_replace('{id}', $id, self::GET_ARTIST);
        return $this;
    }

    /**
     * Get an Artist's Albums
     * Authorization - Required
     * @param string $id Id of artist.
     */
    public function getArtistAlbums($id)
    {
        $this->setConnectionMethod('GET');
        $this->action = str_replace('{id}', $id, self::GET_ARTIST_ALBUMS);
        return $this;
    }

    /**
     * Get an Artist's Top Tracks
     * Authorization - Required
     * @param string $id Id of artist.
     */
    public function getArtistTopTracks($id)
    {
        $this->setConnectionMethod('GET');
        $this->action = str_replace('{id}', $id, self::GET_ARTIST_TOP_TRACKS);
        return $this;
    }

    /**
     * Get an Artist's Related Artists
     * Authorization - Required
     * @param string $id Id of artist.
     */
    public function getArtistRelatedArtists($id)
    {
        $this->setConnectionMethod('GET');
        $this->action = str_replace('{id}', $id, self::GET_ARTIST_RELATED_ARTISTS);
        return $this;
    }

    /**
     * Get Several Artists
     * Authorization - Required
     * @param array $ids Array with ids.
     */
    public function getArtists($ids)
    {
        $ids = (array)$ids;
        $ids_string = implode(',', $ids);
        $this->setConnectionParams(['ids' => $ids_string]);
        $this->setConnectionMethod('GET');
        return $this;
    }
}