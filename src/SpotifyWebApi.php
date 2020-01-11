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
    private $responseHeaders;

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
        $this->requestParams['query'] = $params;
        return $this;
    }

    /**
     * @param string $params Set params for auth header
     */
    private function setAuthParams($params)
    {
        $this->requestParams['auth'] = $params;
        return $this;
    }

    private function getHeaders()
    {
        return $this->headers;
    }

    private function getBaseUri()
    {
        return $this->baseUri;
    }

    private function setBaseUri(String $base_uri)
    {
        $this->baseUri = $base_uri;
    }

    /**
     * @param string $value Value of guzzle query full array
     */
    private function setQueryParams(Array $value)
    {
        $this->requestParams['query'] = $value;
    }

    /**
     * @return array All params for the guzzle query
     */
    private function getRequestParams()
    {
        return $this->requestParams;
        return $this;
    }

    /**
     * @param array $arrays Set full array
     */
    private function setRequestParams(Array $arrays)
    {
        $this->requestParams = $arrays;
        return $this;
    }

    /**
     * @param string $key Name of guzzle form_params parameter
     * @param string $value Value of guzzle form_params parameter
     */
    private function setFormParam(String $key, $value)
    {
        $this->requestParams['form_params'][$key] = $value;
        return $this;
    }
    
    /**
     * @param string $value Value of guzzle form_params full array
     */
    private function setFormParams(Array $params)
    {
        $this->requestParams['form_params'] = $params;
        return $this;
    }

    private function setResponseHeaders($headers)
    {
        $this->responseHeaders = $headers;
        return $this;
    }

    private function getResponseHeaders()
    {
        return $this->responseHeaders;
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
            if(method_exists($this, $key)) {
                $this->{$key}($value);
            } else if(property_exists($this, $key)) {
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
        return $this->account()->authorize()->setFormParams([
            'client_id' => $clientId ?? $this->getClientId(),
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $options['code'] ?? null,
            'show_dialog' => $options['show_dialog'] ?? null,
            'state' => $options['state'] ?? null,
        ])->getPreparedUrl();
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
        $this->setAuthParams([$this->getClientId(), $this->getClientSecret()]);
        return $this->account()->provider($this->service()->token())->setFormParams([
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
        $this->setAuthParams([$this->getClientId(), $this->getClientSecret()]);
        return $this->account()->provider($this->service()->token())->setFormParams([
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
        if(is_array($headers)) {
            foreach($headers as $key=>$value) {
                $this->headers[$key] = $value;
            }
        }
        return $this;
    }

    private function clearAccessToken()
    {
        $this->accessToken = null;
        unset($this->headers['Authorization']);
    }

    public function sendRequest()
    {
        $this->paginationCheck();
        try {
            $client = new GuzzleClient(['base_uri' => $this->getBaseUri(), 'headers' => $this->getHeaders()]);
            $response = $client->request($this->getRequestType(), $this->getUri(), $this->getRequestParams());
            $body = $response->getBody();
            $this->setResponseHeaders($response->getHeaders());
            $this->response = $this->parseRawResponse((string)$body);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody()->getContents());
            if(isset($responseBody->error)) {
                $error = $responseBody->error->message ?? $responseBody->error;
            } else {
                $error = $e->getMessage();
            }
            $this->setResponseHeaders($e->getResponse()->getHeaders());
            $this->errorHandler(new SpotifyWebAPIException($error, $e->getCode()));
        } catch (SpotifyWebAPIException $e) {
            throw new SpotifyWebAPIException($e->getMessage());
        }
        
        return $this;
    }

    /**
     * Set pagination if has
     */
    private function paginationCheck()
    {
        if(SpotifyPagination::getHasPagination()) {
            $this->setQueryString(['limit' => SpotifyPagination::getLimit(), 'offset' => SpotifyPagination::getOffset()]);
        }
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
        } elseif($e->isRateLimited()) {
            $retryRequestTime = (int)$this->getResponseHeaders()['Retry-After'];
            sleep($retryRequestTime);
            $this->getResult();
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
        $this->setAuthParams([$this->getClientId(), $this->getClientSecret()]);    
        try {
            return $this->account()->provider(SpotifyServices::token())->setFormParams([
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->getRefreshToken(),
            ])->getResult();
        } catch(SpotifyWebAPIException $e) {
            dd($this->requestParams);
            throw new SpotifyWebAPIException('Cant Refresh Access Token - ' . $e->getMessage());
        }
    }
}