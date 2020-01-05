<?php

namespace SpotifyWebAPI\Services;

class UsersProfile
{
    
    const GET_USER = '/v1/me';
    const GET_USERS = '/v1/users/{user_id}';

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
        $this->action = str_replace('{user_id}', $user_id, self::GET_USERS); 
    }
}