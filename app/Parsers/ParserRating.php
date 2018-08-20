<?php

namespace App\Parsers;


use App\Facade\Arizona;

class ParserRating extends ParserBase
{

    public function parse($path): array
    {
        $text = Arizona::sendRequest($path)->getBody();
        $arr = [];
        preg_match_all('/<tr>(.+?)<\/tr>/s', $text, $arr);
        foreach($arr[1] as $key => $value){
            $arr[1][$key] = explode("\n", trim($value));
            foreach($arr[1][$key] as $k => $v){
                $tmp = trim($v);
                $arr[1][$key][$k] = substr($tmp, 4, strlen($tmp) - 9);
            }
        }
        return $arr[1];
    }
}