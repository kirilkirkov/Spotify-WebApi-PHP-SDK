# PHP SDK For Spotify Web Api

requires php >= 7.0

## Doesnt have token?

### Get access token with code authorization
Get redirect url for code:
$url = $this->spotifyWebApi->getUrlForCodeToken(
    'f6e1137695fb495994040a437d9d38a0',
    'http://apollo.localhost/callback'
);
header("Location: {$url}");
In the provided callback url (http://apollo.localhost/callback) will be returned $_GET['code'] parameter 
with the code that can get token with following command:
$accessToken = $this->spotifyWebApi->getAccessTokenWithCode(
    'f6e1137695fb495994040a437d9d38a0',
    '18d0bbec9ec4494eb5d4e6e6d97c4e0a',
    'AQCopNupLMEYhgGbt4pp1dsy5NoCSqoJ5Q3LZ9IBqUHAmBd7FVJ9e-u-ggSmtTRWiP94RGW-xxj7eLTqNCa6FE-MvlgykPpvuvmhzIKEPLW8fCKX-ff5WOn4PE7EtGf0E5LoXNLku1P1NjP5kckPFVolWlpIHpNtvPtFoROUDhp5XGvfiavPsnj-GZCNLYJpyTOf0evA',
    'http://apollo.localhost/callback'
);
And you will receive $accessToken.

### Get access token with client credentials
$token = $this->spotifyWebApi->getAccessTokenWithCredentials(
    'f6e1137695fb495994040a437d9d38a0',
    '18d0bbec9ec4494eb5d4e6e6d97c4e0a'
);
echo $token;

### Functions
After initialization with valid access token - new SpotifyWebApi($myToken);

Get several albums: ->api()->getAlbums(['41MnTivkwTO3UUJ8DrqEJJ','6JWc4iAiJ9FjyK0B59ABb4','6UXCm6bOO4gFlDQZV5yL37'])->getResult()
