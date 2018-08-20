<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static sendRequest($path, $params = []): mixed
 */

class Arizona extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'arizona';
    }
}