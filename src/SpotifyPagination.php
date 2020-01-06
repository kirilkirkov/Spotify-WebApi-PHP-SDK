<?php

namespace SpotifyWebAPI;

class SpotifyPagination
{

    private static $total;
    private static $limit;
    private static $offset;

    public static function setTotal($total)
    {
        self::$total = (int)$total;
    }

    public static function getTotal()
    {
        return self::$total;
    }

    public static function setLimit($limit)
    {
        self::$limit = (int)$limit;
    }

    public static function getLimit()
    {
        return self::$limit;
    }

    public static function setOffset($offset)
    {
        self::$offset = (int)$offset;
    }

    public static function getOffset()
    {
        return self::$offset;
    }

}