<?php

namespace SpotifyWebAPI;

/**
 * @author Kiril Kirkov
 * @link https://github.com/kirilkirkov/Spotify-WebApi-PHP-SDK
 * @version 1.2
 * 
 * Spotify Web Api
 */

class SpotifyWebApi
{
    private $connection;

    /**
     * @param array $credentials User credentials
     * - Client Id.
     * - Client Secret.
     * - (Optional) Refresh Token.
     * - (Optional) Access Token.
     */
    public function __construct($credentials = [])
    {
        $this->connection = new \SpotifyWebAPI\SpotifyConnection();
        if(!empty($credentials)) {
            $this->setCredentials($credentials);
        }
    }

    public function connection()
    {
        return $this->connection;
    }

    private function setCredentials($credentials)
    {
        if(isset($credentials['accessToken'])) {
            $this->connection->setAccessToken($credentials['accessToken']);
        }
        if(isset($credentials['refreshToken'])) {
            $this->connection->setRefreshToken($credentials['refreshToken']);
        }
        if(isset($credentials['clientId'])) {
            $this->connection->setClientId($credentials['clientId']);
        }
        if(isset($credentials['clientSecret'])) {
            $this->connection->setClientSecret($credentials['clientSecret']);
        }
    }

    /**
     * Generate token with code - Step 1/2
     * Send user to login after that redirect back with code for access token
     * 
     * @param string $redirectUri Callback url with returned $_GET['code'].
     * @param string $clientId Optional Client Id if is not set in instance constructor.
     * @param array $options Optional. Parameters - scope, show_dialog or state.
     * @return string Authorization url to open in browser
     */
    public function getUrlForCodeToken($redirectUri = null, $clientId = null, $options = [])
    {
        $options = (array) $options;
        $parameters = [
            'client_id' => $clientId ?? $this->connection->getClientId(),
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $options['code'] ?? null,
            'show_dialog' => $options['show_dialog'] ?? null,
            'state' => $options['state'] ?? null,
        ];
        return $this->connection->account()->authorize()->setConnectionParams($parameters)->getPreparedUrl();
    }

    /**
     * Generate token with code - Step 2/2
     * Get the access token with the returned code
     * 
     * @param string $code Code for token.
     * @param string $redirectUri Callback url with returned access token.
     * @return array Access Token and Refresh Token
     */
    public function getAccessTokenWithCode($code, $redirectUri)
    {
        $parameters = [
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUri,
        ];

        $authBasic = $this->connection->getAuthorizationBasicHeader();
        $this->connection->setCustomHeaders($authBasic);
        $response = $this->connection->account()->token()->setConnectionParams($parameters)->getResult();
        if(!isset($response->access_token)) {
            throw new \Exception('Access token missing in response');
        }
        return ['accessToken' => $response->access_token, 'refreshToken' => $response->refresh_token];
    }

    /**
     * Get access token with client credentials
     * Access token expires in 24 hours
     * 
     * @param string $clientId Client id.
     * @param string $clientSecret Client secret.
     * @return string Access Token
     */
    public function getAccessTokenWithCredentials($clientId, $clientSecret)
    {
        $parameters = [
            'grant_type' => 'client_credentials',
        ];
        $authBasic = $this->connection->getAuthorizationBasicHeader();
        $this->connection->setCustomHeaders($authBasic);
        $response = $this->connection->account()->token()->setConnectionParams($parameters)->getResult();
        if(!isset($response->access_token)) {
            throw new \Exception('Access token missing in response');
        }
        return $response->access_token;
    }
}