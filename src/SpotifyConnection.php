<?php

namespace SpotifyWebAPI;

use SpotifyWebAPI\SpotifyRequests;
/**
 * @author Kiril Kirkov
 * Spotify Service Api Connection
 */

class SpotifyConnection extends SpotifyRequests
{
    const ACCOUNT_URL = 'https://accounts.spotify.com';
    const API_URL = 'https://api.spotify.com';

    private $accessToken;
    private $clientId;
    private $clientSecret;
    private $redirectUrl;

    private $connectionUrl;
    private $connectionMethod = 'GET';
    private $connectionParams = [];

    protected $action;
    private $rawResponseBody;
    private $response;
    private $httpResponseCode;
    private $customHeaders;
    private $requestContentType;
    private $responseCodes = [
        '200' => 'Successful',
        '201' => 'Created',
        '202' => 'Accepted',
        '204' => 'Success but No Content returned',
        '304' => 'Not Modified',
        '400' => 'Bad request',
        '401' => 'Unauthorized',
        '403' => 'Forbidden',
        '404' => 'Not found',
        '429' => 'Too many requests',
        '500' => 'Internal server error',
        '502' => 'Bad Gateway',
        '503' => 'Service unavailable',
    ];

    /**
     * Spotify Set Generated Access Token
     *
     * @param string $acccessToken Valid access token.
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @param string $url Api url address who will be called
     */
    public function setAction($url)
    {
        $this->action = '/'.ltrim($url, '/');
        return $this;
    }

    public function account()
    {
        $this->connectionUrl = rtrim(self::ACCOUNT_URL, '/');
        return $this;
    }

    public function api()
    {
        $this->connectionUrl = rtrim(self::API_URL, '/');
        return $this;
    }

    public function setConnectionMethod($method)
    {
        $this->connectionMethod = strtoupper((string) $method);
    }

    public function setConnectionParams($params)
    {
        $this->connectionParams = (array)$params;
        return $this;
    }

    public function sendRequest()
    {
        $ch = curl_init();

        $parameters = http_build_query($this->connectionParams);
        switch($this->connectionMethod) 
        {
            case "POST":
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
            break;
            case "GET":
                
            break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
            break;
            case "PUT":
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
            break;
        }

        curl_setopt($ch, CURLOPT_URL, $this->getPreparedUrl());
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->connectionMethod);
        
        $headers = [
            'Accept: application/json',
        ];
        if($this->accessToken !== null) {
            array_push($headers, 'Authorization: Bearer ' . $this->accessToken);
        }
        if(!empty($this->customHeaders)) {
            array_push($headers, $this->customHeaders);
        }
        if(!empty($this->requestContentType)) {
            array_push($headers, "Content Type: {$this->requestContentType}");
        }
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        $fullResponse = curl_exec($ch);
        
        $this->httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($fullResponse, 0, $header_size);
        $this->rawResponseBody = substr($fullResponse, $header_size);

        $this->parseRawResponse();
        curl_close($ch);
        
        return $this;
    }

    protected function getResponse()
    {
        return $this->response;
    }

    /**
     * This function send prepared request and return parsed response
     */
    public function getResult()
    {
        return $this->sendRequest()->getResponse();
    }

    protected function setRequestContentType($contentType)
    {
        $this->requestContentType = $contentType;
    }

    private function parseRawResponse()
    {
        if((int)$this->httpResponseCode != 200) {
            $error = isset($this->responseCodes[$this->httpResponseCode]) ? 
                            $this->responseCodes[$this->httpResponseCode] :
                            'Unexpected HTTP code: ' . $this->httpResponseCode;
            throw new \Exception("Response error {$error}");
        }
        $decodedResponse = json_decode($this->rawResponseBody);
        if(json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('The response from spotify is not valid json');
        }
        $this->response = $decodedResponse;
    }

    /**
     * Generate token with code - Step 1/2
     * Send user to login after that redirect back with code for access token
     * 
     * @param string $clientId Client id.
     * @param string $redirectUri Callback url with returned $_GET['code'].
     * @param array $options Optional. Parameters - scope, show_dialog or state.
     * @return string Authorization url to open in browser
     */
    public function getUrlForCodeToken($clientId, $redirectUri, $options = [])
    {
        $options = (array) $options;
        $parameters = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $options['code'] ?? null,
            'show_dialog' => $options['show_dialog'] ?? null,
            'state' => $options['state'] ?? null,
        ];
        return $this->account()->authorize()->setConnectionParams($parameters)->getPreparedUrl();
    }

    /**
     * Generate token with code - Step 2/2
     * Get the access token with the returned code
     * 
     * @param string $clientId Client id.
     * @param string $clientSecret Client secret.
     * @param string $code Code for token.
     * @param string $redirectUri Callback url with returned access token.
     * @return string Access Token
     */
    public function getAccessTokenWithCode($clientId, $clientSecret, $code, $redirectUri)
    {
        $parameters = [
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUri,
        ];

        $this->customHeaders = 'Authorization: Basic ' . base64_encode("{$clientId}:{$clientSecret}");
        $response = $this->account()->token()->setConnectionParams($parameters)->sendRequest()->getResponse();
        if(!isset($response->access_token)) {
            throw new \Exception('Access token missing in response');
        }
        return $response->access_token;
    }

    /**
     * Get access token with client credentials
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
        $this->customHeaders = 'Authorization: Basic ' . base64_encode("{$clientId}:{$clientSecret}");
        $response = $this->account()->token()->setConnectionParams($parameters)->sendRequest()->getResponse();
        if(!isset($response->access_token)) {
            throw new \Exception('Access token missing in response');
        }
        return $response->access_token;
    }

    private function getPreparedUrl()
    {
        if(strtoupper($this->connectionMethod) == 'GET') {
            if(!empty($this->connectionParams)) {
                return $this->connectionUrl . $this->action . '?' . http_build_query($this->connectionParams);
            }
            return $this->connectionUrl . $this->action;
        }
        return $this->connectionUrl . $this->action;
    }
}