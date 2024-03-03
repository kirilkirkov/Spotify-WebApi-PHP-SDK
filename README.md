# PHP SDK For Spotify Web Api

<img src="https://raw.githubusercontent.com/kirilkirkov/Spotify-WebApi-PHP-SDK/master/.github/logo%402x.png" alt="Spotify PHP" width="380px" />

<p>requires php >= 7.2</p>

- Integrated Pagination
- Automated Token Refresh
- Separate Services Files For All Api References
- Guzzle Requests

## Installation
composer require kirilkirkov/spotify-webapi-sdk

<p>Example usage with code:</p>
https://github.com/kirilkirkov/Spotify-WebApi-PHP-SDK/wiki/Example-Usage-with-Code

## Doesnt have token?

### Option 1 - Get access token with client credentials

```
use SpotifyWebAPI\SpotifyWebApi;

try {
    $spotifyWebApi = new SpotifyWebApi();
    $token_obj = $spotifyWebApi->getAccessTokenWithCredentials(
        'CLIENT_ID',
        'CLIENT_SECRET'
    );
    echo $token_obj->access_token;
    // echo $token_obj->token_type;
    // echo $token_obj->expires_in;
} catch(\SpotifyWebAPI\SpotifyWebAPIException $e) {
    echo $e->getMessage();
}
```

### Option 2 - Get access token with code authorization (recommended)
Before make requests you must add yours Redirect URIs to https://developer.spotify.com/dashboard

Get redirect url for code:
```
use SpotifyWebAPI\SpotifyWebApi;

try {
    $spotifyWebApi = new SpotifyWebApi([
        'clientId' => 'CLIENT_ID',
        'clientSecret' => 'CLIENT_SECRET',
    ]);

    $callBackUrl = 'http://yoursite.com/callback';
    $url = $spotifyWebApi->getUrlForCodeToken($callBackUrl);
    header("Location: {$url}");
} catch(\SpotifyWebAPI\SpotifyWebAPIException $e) {
    echo $e->getMessage();
}
```

After signup in spotify you will be redirected back to provided above callback url (http://yoursite.com/callback) with parameter **$_GET['code']** with the code that can get token with following command:
```
use SpotifyWebAPI\SpotifyWebApi;

try {
    $spotifyWebApi = new SpotifyWebApi();
    $tokens = $spotifyWebApi->getAccessTokenWithCode(
        'YOUR_CODE',
        'http://yoursite.com/callback'
    );
} catch(\SpotifyWebAPI\SpotifyWebAPIException $e) {
    echo $e->getMessage();
}
```

And you will receive array with *accessToken* and *refreshToken* in the example above **$tokens**.

### Access/Refresh Tokens
Spotify tokens are valid 1 hour. If your token is expired and you make a call, the sdk auto renew access token with provided refresh token in every query (as there is no safe place to automatically save it).

If you set $spotifyWebApi->returnNewTokenIfIsExpired(true); before your request calls, if access token is expired will be returned from the query, object with the new access_token, then you can save it in database and recall request with a fresh Access token. 
You can also generate access token with refresh token manually with
```
use SpotifyWebAPI\SpotifyWebApi;

try {
    $spotifyWebApi = new SpotifyWebApi([
        'clientId' => 'CLIENT_ID',
        'clientSecret' => 'CLIENT_SECRET',
        'accessToken' => $oldAccessToken,
        'refreshToken' => 'REFRESH_TOKEN',
    ]);
    $result = $spotifyWebApi->refreshAccessToken();
} catch(\SpotifyWebAPI\SpotifyWebAPIException $e) {
    echo $e->getMessage();
}
```

and save final expire timestamp with  time() + $result->expires_in. You can manualy generate new access token every time when saved in your database expired time is end.

### Suggestions

It is good practise to add ip of the api that you call in the hosts file in yours server os because Guzzle sometime cannot resolve the dns.

Can increase your execution time of scripts 
ini_set('max_execution_time', XXX); and set_time_limit(XXX);

### Functions
In the wiki of this repository you can find all functions available in this sdk (all the ones supported by Spotify have been integrated so far)
- https://github.com/kirilkirkov/Spotify-WebApi-PHP-SDK/wiki/Functions-and-examples
- https://github.com/kirilkirkov/Spotify-WebApi-PHP-SDK/wiki/Pagination Integrated Pagination Example

## Donate
<p>If this project help you reduce time to develop, you can give me a cup of coffee to continue its development. Thank you! :)</p>

[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/donate/?hosted_button_id=7U9TVUV3URTK6)
