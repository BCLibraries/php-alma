<?php

namespace BCLib\Alma\RTA;

class Factory
{
    public static function build($alma_host, $library)
    {
        return new Client( new \Guzzle\Http\Client(), new Parser(), $alma_host, $library);
    }
}