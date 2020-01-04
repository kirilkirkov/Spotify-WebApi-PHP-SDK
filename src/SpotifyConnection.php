<?php

namespace SpotifyWebAPI;

include 'SpotifyRequests.php';
/*
 * @author Kiril Kirkov
 * Spotify Service Api Connection
 */

class SpotifyConnection extends SpotifyRequests
{
    const ACCOUNT_URL = 'https://accounts.spotify.com';
    const API_URL = 'https://api.spotify.com';

    private static $instance = null;

    private $accessToken = null;
    private $clientId;
    private $clientSecret;
    private $redirectUrl;


    private $connectionUrl = null;
    private $action = null;
    private $response = null;
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

    private function __construct() 
    { 
        
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        
        return self::$instance;
    }

    protected function setCredentials($clientId, $clientSecret, $redirectUrl)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUrl = $redirectUrl;
    }

    public function setAccessToken($accessToken)
    {
        if(!$accessToken) {
            throw new \Exception('Not provided Api access token');
        }
        $this->accessToken = $accessToken;
    }

    public function setAction($url)
    {
        $this->action = '/'.ltrim($url, '/');
        return $this;
    }

    public function account()
    {
        $constant = constant('self::'.strtoupper(__FUNCTION__).'_URL');
        $this->connectionUrl = rtrim($constant, '/');
        return $this;
    }

    public function api()
    {
        $constant = constant('self::'.strtoupper(__FUNCTION__).'_URL');
        $this->connectionUrl = rtrim($constant, '/');
        return $this;
    }

    public function sendRequest($method = 'GET', $parameters = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->connectionUrl . $this->action);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if (is_array($parameters) || is_object($parameters)) {
            $parameters = http_build_query($parameters);
        }
        
        $method = strtoupper($method);
        switch($method) {
            case "POST":
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
            break;
            case "GET":
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
            break;
        }

        // $headers = array(
        //     'Accept: application/json',
        //     'Content-Type: application/json',
        //     'Authorization: Bearer BQApGwG1qAYwt6I-2BCBlj0M4Fudesdxor4EsHoamO-d82VaAI4gBorz-cbf1GEBdefPmWDWdJT-8_xI2DSd54CBmnLQp23dA2sg4vrrU8woOq1o2TaoF_ZXjqf-obBQ7I3jsAsPla_1XHF9y_0rzuCWpwEKkLykoMEVX9F2q6V4h4gU7IOI_1iwH_PqHU4', 
        // );
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        self::$response = curl_exec($ch);
        curl_close($ch);

        return new static;
    }

    public function getResponse()
    {
        echo 'asd';
    }

    /*
     * Generate token - Step 1/2
     * Generate authorization url that will return code which will be used
     * to generate access token with it
     */
    public function getAuthorizeUrl($options = [])
    {
        $options = (array) $options;
        $parameters = [
            'client_id' => 'f6e1137695fb495994040a437d9d38a0',
            'redirect_uri' => 'http://apollo.localhost/callback',
            'response_type' => 'code',
            'scope' => null,
            'show_dialog' => null,
            'state' => null,
        ];
        return 'https://accounts.spotify.com' . '/authorize?' . http_build_query($parameters);
    }

    public function requestAccessToken()
    {
        $parameters = [
            'client_id' => 'f6e1137695fb495994040a437d9d38a0',
            'client_secret' => '18d0bbec9ec4494eb5d4e6e6d97c4e0a',
            'code' => 'AQDdZpS90xIbtwBWdmZHPVLfR6T5jQls5aDXGk82PwN9xfCuMs67rRMEEK3I5KVYeosTK0AGrSj5N9O1FE5Lsv-bAMqy4LyvJI28YUwzfKA7BQPoRxNSc4et3cr5Ryp9hanu8aozZqYi-zpWXouFvVsjiIS30GWm-xckgeAgjfzq5tX_QEy7cBr8c8J3zYxdxdSIO0mz',
            'grant_type' => 'authorization_code',
            'redirect_uri' => 'http://apollo.localhost/callback',
        ];
        self::$action = 'https://accounts.spotify.com/api/token';
        $response = $this->sendRequest('POST', $parameters);
        return new static;
    }
}