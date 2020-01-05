<?php

namespace SpotifyWebAPI\Services;

/**
 * @author Kiril Kirkov
 * Spotify Browse Service
 */

class Browse
{
    const GET_CATEGORY = '/v1/browse/categories/{category_id}';
    const GET_CATEGORY_PLAYLISTS = '/v1/browse/categories/{category_id}/playlists';
    const GET_CATEGORIES_LIST = '/v1/browse/categories';
    const GET_FEATURED_PLAYLISTS = '/v1/browse/featured-playlists';
    const GET_NEW_RELEASES = '/v1/browse/new-releases';
    const GET_RECOMMENDATIONS_SEEDS = '/v1/recommendations';

    /**
     * Get a Category
     * Authorization - Required
     * @param string $category_id Id of category.
     */
    public function getCategory($category_id)
    {
        $this->setConnectionMethod('GET');
        $this->action = str_replace('{category_id}', $category_id, self::GET_CATEGORY);
        return $this;
    }

    /**
     * Get a Category's Playlists
     * Authorization - Required
     * @param string $category_id Id of category.
     */
    public function getCategoryPlaylists($category_id)
    {
        $this->setConnectionMethod('GET');
        $this->action = str_replace('{category_id}', $category_id, self::GET_CATEGORY_PLAYLISTS);
        return $this;
    }

    /**
     * Get a List of Categories
     * Authorization - Required
     */
    public function getCategoriesList()
    {
        $this->setConnectionMethod('GET');
        $this->action = self::GET_CATEGORIES_LIST;
        return $this;
    }

    /**
     * Get a List of Featured Playlists
     * Authorization - Required
     */
    public function getFeaturedPlaylists()
    {
        $this->setConnectionMethod('GET');
        $this->action = self::GET_FEATURED_PLAYLISTS;
        return $this;
    }

    /**
     * Get a List of New Releases
     * Authorization - Required
     */
    public function getNewReleases()
    {
        $this->setConnectionMethod('GET');
        $this->action = self::GET_NEW_RELEASES;
        return $this;
    }

    /**
     * Get Recommendations Based on Seeds
     * Authorization - Required
     */
    public function getRecommendationsSeeds()
    {
        $this->setConnectionMethod('GET');
        $this->action = self::GET_RECOMMENDATIONS_SEEDS;
        return $this;
    }
}