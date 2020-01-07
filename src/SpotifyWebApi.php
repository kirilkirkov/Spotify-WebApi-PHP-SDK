<?php

namespace SpotifyWebAPI;

use \GuzzleHttp\Client as GuzzleClient;
use Guzzle\Http\Exception\ClientErrorResponseException;

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

    // User Credentials
    private $accessToken;
    private $refreshToken;
    private $clientId;
    private $clientSecret;
    private $redirectUrl;

    // Request params
    private $baseUri;
    private $requestType = 'GET';
    private $requestParams = [];
    private $uri;
    private $headers = [
        'Accept' => 'application/json',
    ];

    // Request result params
    private $rawResponseBody;
    private $response;

    // Refresh token
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
        $this->requestParams['form_params'] = $params;
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

    private function getBaseUri()
    {
        return $this->baseUri;
    }

    private function setBaseUri($base_uri)
    {
        $this->baseUri = $base_uri;
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

    public function provider(Array $service)
    {
        array_walk($service, function(&$value, &$key) {
            if(property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        });
        return $this; 
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
        $this->setRequestParam('auth', [$this->getClientId(), $this->getClientSecret()]);
        return $this->account()->provider($this->service()->token())->setQueryString([
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUri,
        ])->getResult();

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
        $this->setRequestParam('auth', [$this->getClientId(), $this->getClientSecret()]);
        return $this->account()->provider($this->service()->token())->setQueryString([
            'grant_type' => 'client_credentials',
        ])->getResult();

        if(!isset($response->access_token)) {
            throw new SpotifyWebAPIException('Access token missing in response');
        }
        return $response->access_token;
    }
 
    /**
     * Set Generated Access Token
     *
     * @param string $acccessToken Valid access token.
     */
    public function setAccessToken(String $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->setHeaders(['Authorization' => 'Bearer ' . $this->accessToken]);
        return $this;
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

    /**
     * @param string|array $headers Headers to send
     */
    public function setHeaders($headers)
    {
        if($headers === null) {
            $this->headers = [];
            return $this;
        }
        foreach($headers as $key=>$value) {
            $this->headers[$key] = $value;
        }
        return $this;
    }

    private function getRequestParams()
    {
        return $this->requestParams;
    }

    /**
     * @param string $key Name of guzzle query parameter
     * @param string $value Value of guzzle query parameter
     */
    private function setRequestParam(String $key, $value)
    {
        $this->requestParams[$key] = $value;
    }
    
    private function setRequestParams(Array $params)
    {
        $this->requestParams = $params;
    }

    private function clearAccessToken()
    {
        $this->accessToken = null;
        unset($this->headers['Authorization']);
    }

    public function sendRequest()
    {
        try {
            $client = new GuzzleClient(['base_uri' => $this->getBaseUri(), 'headers' => $this->getHeaders()]);
            $response = $client->request($this->getRequestType(), $this->getUri(), $this->getRequestParams());
            $body = $response->getBody();
            $this->response = $this->parseRawResponse((string)$body);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody()->getContents());
            $this->errorHandler(new SpotifyWebAPIException($responseBody->error->message ?? $responseBody->error));
        } catch (SpotifyWebAPIException $e) {
            throw new SpotifyWebAPIException($e->getMessage());
        }
       
        return $this;
    }
    
    private function errorHandler(SpotifyWebAPIException $e)
    {
        if($e->hasExpiredToken()) {
            $this->clearAccessToken();
            if($this->returnNewTokenIfIsExpired === false) {
                $this->refreshTokenAndReCallLastRequest();
            } else {
                $this->refreshTokenAndReturnBack();
            }
        } elseif($e->invalidClient()) {
            throw new SpotifyWebAPIException('Probably missing header Content-Type: application/x-www-form-urlencoded');
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
        return $result;
    }

    private function refreshTokenAndReCallLastRequest()
    {
        $this->setLastRequest();
        $result = $this->refreshAccessToken();
        if(isset($result->access_token)) {
            $this->setAccessToken($result->access_token)->returnLastRequest()->getResult();
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
            'requestParams' => $this->getRequestParams(),
            'requestType' => $this->getRequestType(),
            'baseUri' => $this->getBaseUri(),
            'uri' => $this->getUri(),
        ];
    }

    private function returnLastRequest()
    {
        if(isset($this->lastRequest['requestParams'])) {
            $this->setRequestParams($this->lastRequest['requestParams']);
        }
        if(isset($this->lastRequest['requestType'])) {
            $this->setRequestType($this->lastRequest['requestType']);
        }
        if(isset($this->lastRequest['baseUri'])) {
            $this->setBaseUri($this->lastRequest['baseUri']);
        }
        if(isset($this->lastRequest['uri'])) {
            $this->setUri($this->lastRequest['uri']);
        }
        return $this;
    }
    
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Send prepared request and return parsed response
     */
    public function getResult()
    {
        return $this->sendRequest()->getResponse();
    }

    private function parseRawResponse($rawResponseBody)
    {
        $decodedResponse = json_decode($rawResponseBody);
        if(json_last_error() !== JSON_ERROR_NONE) {
            throw new SpotifyWebAPIException('The response from Spotify is not valid json');
        }
        SpotifyPagination::parsePagination($decodedResponse);
        return $decodedResponse;
    }

    /**
     * Auto refresh expired token
     * 
     * @return string Access Token
     */
    public function refreshAccessToken()
    {
        $this->setRequestParam('auth', [$this->getClientId(), $this->getClientSecret()]);    
        try {
            return $this->account()->provider(SpotifyServices::token())->setQueryString([
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->getRefreshToken(),
            ])->getResult();
        } catch(SpotifyWebAPIException $e) {
            throw new SpotifyWebAPIException('Cant Refresh Access Token - ' . $e->getMessage());
        }
    }
}