# PHP SDK For Spotify Web Api

requires php >= 7.0

## Installation
composer require kirilkirkov/spotify-webapi-sdk

## Doesnt have token?

### Get access token with client credentials
$token = $spotifyWebApi->getAccessTokenWithCredentials(
    'f6e1137695fb495994040a437d9d38a0',
    '18d0bbec9ec4494eb5d4e6e6d97c4e0a'
);
echo $token;

### Get access token with code authorization
Get redirect url for code:
$spotifyWebApi = new SpotifyWebApi([
    'clientId' => 'f6e1137695fb495994040a437d9d38a0',
    'clientSecret' => '18d0bbec9ec4494eb5d4e6e6d97c4e0a',
]);

$callBackUrl = 'http://apollo.localhost/callback';
$url = $spotifyWebApi->getUrlForCodeToken($callBackUrl);
header("Location: {$url}");

In the provided callback url (http://apollo.localhost/callback) will be returned $_GET['code'] parameter 
with the code that can get token with following command:
$tokens = $spotifyWebApi->getAccessTokenWithCode(
    'AQCT-KP6JcHiz5RieCMLvyGeKPlvMQQMbSWU5nsDfNzo77vbmWqG8dUDhJhX17f_nPMhXQ0V4bJ_yPdCyxjCyRkWS7A8omyVteFw-KLngL-NDdLvm4Lv2BOqWd-tvS7sj5dRtaJkP4FrPJpbEH78N8FP0D_H_G0iyODQmDHvw5Y9KgQEJ59ObINCEXD8ktSAoY8bmNYv',
    'http://apollo.localhost/callback'
);
And you will receive array with $accessToken and $refreshToken.

### Functions
After initialization with valid access token (new SpotifyWebApi($myToken))

Get several albums: ->api()->getAlbums(['41MnTivkwTO3UUJ8DrqEJJ','6JWc4iAiJ9FjyK0B59ABb4','6UXCm6bOO4gFlDQZV5yL37'])->getResult()
