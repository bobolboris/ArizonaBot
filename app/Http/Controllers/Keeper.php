<?php

namespace App\Http\Controllers;

use App\ValueEntity;

class Keeper
{
    public function set($user_id, $state_id, $key, $value)
    {
        $attributes = ['user_id' => $user_id, 'state_id' => $state_id, 'key' => $key];
        $values = ['user_id' => $user_id, 'state_id' => $state_id, 'key' => $key, 'value' => $value];
        ValueEntity::updateOrCreate($attributes, $values)->save();
    }

    public function get($user_id, $state_id, $key)
    {
        $ve = ValueEntity::where('user_id', $user_id)->where('state_id', $state_id)->where('key', $key)->first();
        return ($ve == null) ? $ve : $ve->value;
    }

    public function remove($user_id, $state_id, $key)
    {
        $ve = $this->get($user_id, $state_id, $key);
        if ($ve != null) {
            $ve->delete();
        }
    }
}