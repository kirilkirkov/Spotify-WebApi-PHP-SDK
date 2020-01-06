<?php

namespace SpotifyWebAPI;

/**
 * @author Kiril Kirkov
 * Spotify Service Api Connection
 */

class SpotifyConnection extends \SpotifyWebAPI\SpotifyRequests
{
    const ACCOUNT_URL = 'https://accounts.spotify.com';
    const API_URL = 'https://api.spotify.com';

    private $accessToken;
    private $refreshToken;
    private $clientId;
    private $clientSecret;
    private $redirectUrl;

    private $connectionUrl;
    private $connectionMethod = 'GET';
    private $connectionParams = [];

    protected $action;
    private $rawResponseBody;
    private $response;
    private $customHeaders;
    private $requestContentType;

    private $lastRequest = [];
    private $manualRefreshToken = false;

    /**
     * Set Generated Access Token
     *
     * @param string $acccessToken Valid access token.
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set Generated Refresh Token
     *
     * @param string $refreshToken Valid refresh token.
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Set Client Id
     *
     * @param string $clientId Valid client id.
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set Client Secret.
     *
     * @param string $clientSecret Valid client secret.
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $url Api url address who will be called
     */
    public function setAction($url)
    {
        $this->action = '/'.ltrim($url, '/');
        return $this;
    }

    public function getAction()
    {
        return $this->action;
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

    public function getConnectionMethod()
    {
        return $this->connectionMethod;
    }

    public function setConnectionParams($params)
    {
        $this->connectionParams = (array)$params;
        return $this;
    }

    public function getConnectionParams()
    {
       return $this->connectionParams;
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
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $this->getResponseBody($fullResponse, $header_size);

        try {
            $this->parseRawResponse();
        } catch(SpotifyWebAPIException $e) {
            $this->errorHandler($e);
        }

        if(curl_error($ch)) {
            throw new SpotifyWebAPIException('cURL transport error: ' . curl_errno($ch) . ' ' .  curl_error($ch));
        }

        curl_close($ch);
        return $this;
    }
    
    private function errorHandler(SpotifyWebAPIException $e)
    {
        if($e->hasExpiredToken()) {
            if($this->manualRefreshToken === false) {
                $this->refreshTokenAndReCallLastRequest();
            } else {
                $this->refreshTokenAndReturnBack();
            }
        } else {
            throw new SpotifyWebAPIException($e->getMessage());
        }
    }

    private function refreshTokenAndReturnBack()
    {
        $result = $this->refreshAccessToken();
        if(!isset($result->access_token)) {
            throw new SpotifyWebAPIException('Cant find access token in refresh token response');
        }
    }

    private function refreshTokenAndReCallLastRequest()
    {
        $this->setLastRequest();
        $result = $this->refreshAccessToken();
        if(isset($result->access_token)) {
            $this->setAccessToken($result->access_token);
            $this->putLastRequest();
            $this->setCustomHeaders(null);
            $this->getResult();
        } else {
            throw new SpotifyWebAPIException('Cant find access token in refresh token response');
        }
    }

    public function manualRefreshToken($status = true)
    {
        $this->manualRefreshToken = $status;
    }

    private function setLastRequest()
    {
        $this->lastRequest = [
            'params' => $this->getConnectionParams(),
            'method' => $this->getConnectionMethod(),
            'action' => $this->getAction(),
            'url' => $this->getConnectionUrl(),
        ];
    }

    private function putLastRequest()
    {
        if(isset($this->lastRequest['params'])) {
            $this->setConnectionParams($this->lastRequest['params']);
        }
        if(isset($this->lastRequest['method'])) {
            $this->setConnectionMethod($this->lastRequest['method']);
        }
        if(isset($this->lastRequest['action'])) {
            $this->setAction($this->lastRequest['action']);
        }
        if(isset($this->lastRequest['action'])) {
            $this->setAction($this->lastRequest['action']);
        }
        if(isset($this->lastRequest['url'])) {
            $this->setConnectionUrl($this->lastRequest['url']);
        }
    }
    
    private function getConnectionUrl()
    {
        return $this->connectionUrl;
    }

    private function setConnectionUrl()
    {
        return $this->connectionUrl;
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

    private function getResponseBody($fullResponse, $header_size)
    {
        $header = substr($fullResponse, 0, $header_size);
        $this->rawResponseBody = substr($fullResponse, $header_size);
    }

    private function parseRawResponse()
    {
        $decodedResponse = json_decode($this->rawResponseBody);
        if(json_last_error() !== JSON_ERROR_NONE) {
            throw new SpotifyWebAPIException('The response from Spotify is not valid json');
        }
        if(isset($decodedResponse->error)) {
            if(is_string($decodedResponse->error)) {
                throw new SpotifyWebAPIException($decodedResponse->error);
            }
            throw new SpotifyWebAPIException($decodedResponse->error->message, $decodedResponse->error->status);
        }
        $this->response = $decodedResponse;
    }

    public function getAuthorizationBasicHeader()
    {
        $payload = base64_encode($this->getClientId() . ':' . $this->getClientSecret());
        return 'Authorization: Basic ' . $payload;
    }

    public function getPreparedUrl()
    {
        if(strtoupper($this->connectionMethod) == 'GET') {
            if(!empty($this->connectionParams)) {
                return $this->connectionUrl . $this->action . '?' . http_build_query($this->connectionParams);
            }
            return $this->connectionUrl . $this->action;
        }
        return $this->connectionUrl . $this->action;
    }

    public function setCustomHeaders($customHeaders)
    {
        $this->customHeaders = $customHeaders;
    }

    /**
     * Auto refresh expired token
     * 
     * @return string Access Token
     */
    public function refreshAccessToken()
    {
        $parameters = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->getRefreshToken(),
        ];
        $this->setAccessToken(null);
        $this->setCustomHeaders($this->getAuthorizationBasicHeader());
        try {
            return $this->account()->token()->setConnectionParams($parameters)->sendRequest()->getResponse();
        } catch(SpotifyWebAPIException $e) {
            throw new SpotifyWebAPIException('Cant Refresh Access Token - '.$e->getMessage());
        }
    }
}