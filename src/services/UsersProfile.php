<?php

namespace SpotifyWebAPI\Services;

/**
 * @author Kiril Kirkov
 * Spotify UsersProfile Service
 */

class UsersProfile implements InterfaceSpotifyService
{
    
    const GET_USER = '/v1/me';
    const GET_USERS = '/v1/users/{user_id}';

    private $method;
    private $params;
    private $action;

    /**
     * Get Current User's Profile
     * Authorization - Required
     */
    public function getUser()
    {
        $this->setConnectionMethod('GET');
        $this->action = self::GET_USER;
    }

    /**
     * Get a User's Profile
     * Authorization - Required
     * @param string $id - Id of the track.
     */
    public function getUsers($user_id)
    {
        $this->setConnectionMethod('GET');
        $this->setAction(str_replace('{user_id}', $user_id, self::GET_USERS)); 
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