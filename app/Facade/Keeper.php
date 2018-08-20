<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static set($user_id, $state_id, $key, $value): void
 * @method static get($user_id, $state_id, $key): mixed|null
 * @method static remove($user_id, $state_id, $key): void
 */
class Keeper extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'keeper';
    }
}