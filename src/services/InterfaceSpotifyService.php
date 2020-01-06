<?php

namespace SpotifyWebAPI\Services;

interface InterfaceSpotifyService
{
    public function getConnectionMethod();
    public function getConnectionParams();
    public function getAction();
}