<?php

namespace SpotifyWebAPI;

use GuzzleHttp\Client;

/**
 * @author Kiril Kirkov
 * @link https://github.com/kirilkirkov/Spotify-WebApi-PHP-SDK
 * @version 1.2
 * 
 * Spotify Web Api
 */

class SpotifyWebApi
{
    const ACCOUNT_URL = 'https://accounts.spotify.com';
    const API_URL = 'https://api.spotify.com';

    private $accessToken;
    private $refreshToken;
    private $clientId;
    private $clientSecret;
    private $redirectUrl;

    private $baseUri;
    private $requestType = 'GET';
    private $queryString = [];
    private $uri;
    private $headers = [];

    private $rawResponseBody;
    private $response;
    private $customHeaders;
    private $requestContentType;

    private $lastRequest = [];
    private $returnNewTokenIfIsExpired = false;

    /**
     * @param array $credentials User credentials
     * - Client Id.
     * - Client Secret.
     * - (Optional) Refresh Token.
     * - (Optional) Access Token.
     */
    public function __construct(Array $credentials = [])
    {
        if(!empty($credentials)) {
            $this->setCredentials($credentials);
        }
    }

    private function setCredentials($credentials)
    {
        if(isset($credentials['accessToken'])) {
            $this->setAccessToken($credentials['accessToken']);
        }
        if(isset($credentials['refreshToken'])) {
            $this->setRefreshToken($credentials['refreshToken']);
        }
        if(isset($credentials['clientId'])) {
            $this->setClientId($credentials['clientId']);
        }
        if(isset($credentials['clientSecret'])) {
            $this->setClientSecret($credentials['clientSecret']);
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
    public function getUrlForCodeToken(String $redirectUri = null, String $clientId = null, Array $options = [])
    {
        $options = $options;
        $parameters = [
            'client_id' => $clientId ?? $this->getClientId(),
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $options['code'] ?? null,
            'show_dialog' => $options['show_dialog'] ?? null,
            'state' => $options['state'] ?? null,
        ];
        return $this->account()->authorize()->setQueryString($parameters)->getPreparedUrl();
    }

    /**
     * Generate token with code - Step 2/2
     * Get the access token with the returned code
     * 
     * @param string $code Code for token.
     * @param string $redirectUri Callback url with returned access token.
     * @return array Access Token and Refresh Token
     */
    public function getAccessTokenWithCode(String $code, String $redirectUri)
    {
        $parameters = [
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUri,
        ];

        $this->setHeaders($this->getAuthorizationBasicHeader());
        $response = $this->account()->token()->setQueryString($parameters)->getResult();
        if(!isset($response->access_token)) {
            throw new SpotifyWebAPIException('Access token missing in response');
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
    public function getAccessTokenWithCredentials(String $clientId, String $clientSecret)
    {
        $parameters = [
            'grant_type' => 'client_credentials',
        ];

        $this->setHeaders($this->getAuthorizationBasicHeader());
        $response = $this->account()->token()->setQueryString($parameters)->getResult();
        if(!isset($response->access_token)) {
            throw new SpotifyWebAPIException('Access token missing in response');
        }
        return $response->access_token;
    }

    public function setPaginationLimit(Int $limit)
    {
        SpotifyPagination::setLimit($limit);
    }

    public function setPaginationOffset(Int $offset)
    {
        SpotifyPagination::setOffset($offset);
    }

    public function getPaginationTotal()
    {
        return SpotifyPagination::getTotal();
    }

 
 
    // Connector
 
 
 
    /**
     * Set Generated Access Token
     *
     * @param string $acccessToken Valid access token.
     */
    public function setAccessToken(String $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->setHeaders('Authorization: Bearer ' . $this->accessToken);
        return $this;
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
    public function setRefreshToken(String $refreshToken)
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
    public function setClientId(String $clientId)
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
    public function setClientSecret(String $clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $uri Api uri
     */
    public function setUri(String $uri)
    {
        $this->uri = '/'.ltrim($uri, '/');
        return $this;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function account()
    {
        $this->setBaseUri(rtrim(self::ACCOUNT_URL, '/'));
        return $this;
    }

    public function api()
    {
        $this->setBaseUri(rtrim(self::API_URL, '/'));
        return $this;
    }

    public function setRequestType(String $method)
    {
        $this->requestType = strtoupper($method);
    }

    public function getRequestType()
    {
        return $this->requestType;
    }

    public function setQueryString(Array $params)
    {
        $this->queryString = $params;
        return $this;
    }

    public function getQueryString()
    {
       return $this->queryString;
    }

    private function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string|array $headers Headers to send
     */
    public function setHeaders($headers)
    {
        if($headers === null) {
            $this->headers = [];
            return $this;
        }
        array_push($this->headers, $headers);
        return $this;
    }

    public function sendRequest()
    {

        $client = new GuzzleHttp\Client(['base_uri' => $this->getBaseUri(), 'headers' => $this->getHeaders()]);
        $response = $client->request($this->getRequestType(), $this->getUri(), $this->getQueryString());

        return $this;
    }
    
    private function errorHandler(SpotifyWebAPIException $e)
    {
        if($e->hasExpiredToken()) {
            if($this->returnNewTokenIfIsExpired === false) {
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
            $this->setHeaders([]);
            $this->getResult();
        } else {
            throw new SpotifyWebAPIException('Cant find access token in refresh token response');
        }
    }

    /**
     * @param boolean $status Return as result new token if is expired
     */
    public function returnNewTokenIfIsExpired($status = true)
    {
        $this->returnNewTokenIfIsExpired = $status;
    }

    private function setLastRequest()
    {
        $this->lastRequest = [
            'params' => $this->getQueryString(),
            'method' => $this->getRequestType(),
            'action' => $this->getAction(),
            'url' => $this->getBaseUri(),
        ];
    }

    private function putLastRequest()
    {
        if(isset($this->lastRequest['params'])) {
            $this->setQueryString($this->lastRequest['params']);
        }
        if(isset($this->lastRequest['method'])) {
            $this->setRequestType($this->lastRequest['method']);
        }
        if(isset($this->lastRequest['action'])) {
            $this->setAction($this->lastRequest['action']);
        }
        if(isset($this->lastRequest['action'])) {
            $this->setAction($this->lastRequest['action']);
        }
        if(isset($this->lastRequest['url'])) {
            $this->setBaseUri($this->lastRequest['url']);
        }
    }
    
    private function getBaseUri()
    {
        return $this->baseUri;
    }

    private function setBaseUri()
    {
        return $this->baseUri;
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

        SpotifyPagination::parsePagination($decodedResponse);
        $this->response = $decodedResponse;
    }

    public function getAuthorizationBasicHeader()
    {
        $payload = base64_encode($this->getClientId() . ':' . $this->getClientSecret());
        return 'Authorization: Basic ' . $payload;
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
        $this->setHeaders($this->getAuthorizationBasicHeader());
        try {
            return $this->account()->token()->setQueryString($parameters)->sendRequest()->getResponse();
        } catch(SpotifyWebAPIException $e) {
            throw new SpotifyWebAPIException('Cant Refresh Access Token - '.$e->getMessage());
        }
    }
}