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

    private $serviceContainer;

    public function albums()
    {
        $this->serviceContainer = new \SpotifyWebAPI\Services\Albums();
        return $this;
    }

    public function artists()
    {
        $this->serviceContainer = new \SpotifyWebAPI\Services\Artists();
        return $this;
    }

    public function browse()
    {
        $this->serviceContainer = new \SpotifyWebAPI\Services\Browse();
        return $this;
    }

    public function follow()
    {
        $this->serviceContainer = new \SpotifyWebAPI\Services\Follow();
        return $this;
    }

    public function library()
    {
        $this->serviceContainer = new \SpotifyWebAPI\Services\Library();
        return $this;
    }

    public function personalization()
    {
        $this->serviceContainer = new \SpotifyWebAPI\Services\Personalization();
        return $this;
    }

    public function player()
    {
        $this->serviceContainer = new \SpotifyWebAPI\Services\Player();
        return $this;
    }

    public function playlists()
    {
        $this->serviceContainer = new \SpotifyWebAPI\Services\Playlists();
        return $this;
    }

    public function search()
    {
        $this->serviceContainer = new \SpotifyWebAPI\Services\Search();
        return $this;
    }

    public function tracks()
    {
        $this->serviceContainer = new \SpotifyWebAPI\Services\Tracks();
        return $this;
    }

    public function users()
    {
        $this->serviceContainer = new \SpotifyWebAPI\Services\UsersProfile();
        return $this;
    }
        
    public function authorize()
    {
        $this->setRequestType('GET');
        $this->setAction(self::AUTHORIZE);
        return $this;
    }

    public function token()
    {
        $this->setRequestType('POST');
        $this->setAction(self::TOKEN);
        return $this;
    }

    /**
     * Typically calls methods from loaded Service
     */
    public function __call($name, $arguments)
    {
        if(method_exists($this->serviceContainer, $name)) {

            call_user_func_array(array($this->serviceContainer, $name), $arguments);
            
            if($this->serviceContainer->getRequestType() != null) {
                $this->setRequestType($this->serviceContainer->getRequestType());
            }
            if($this->serviceContainer->getQueryString() != null) {
                $this->setQueryString($this->serviceContainer->getQueryString());
            }
            if($this->serviceContainer->getAction() != null) {
                $this->setAction($this->serviceContainer->getAction());
            }

            return $this;
        } else {
            throw new SpotifyWebAPIException("Cant resolve request {$name}");
        }
    }
}