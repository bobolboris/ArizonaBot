<?php

namespace App\Http\Controllers;

use App\Facade\Keeper;
use Kozz\Laravel\Facades\Guzzle;

class Arizona
{
    public function sendRequest($path, $params = [])
    {
        $cookie = Keeper::get(null, null, 'cookie');
        $params['headers'] = ['Cookie' => $cookie];
        $response = Guzzle::get("https://arizona-rp.com" . $path, $params);
        $cookies = [];
        $c = preg_match('/R3ACTLB=[^ ]+/', $response->getBody(), $cookies);
        if ($c > 0) {
            Keeper::set(null, null, 'cookie', $cookies[0]);
            return $this->sendRequest($path);
        }
        return $response;
    }
}