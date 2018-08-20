<?php

namespace App\Parsers;


use App\Facade\Arizona;

class ParserMap extends ParserBase
{

    public function parse($path): array
    {
        $arr = [];
        preg_match_all('/top:([^p]+)px;left:([^p]+)px;background:url\(([^\)]+)\)/s',
            Arizona::sendRequest($path)->getBody(), $arr, PREG_SET_ORDER);
        return $arr;
    }
}