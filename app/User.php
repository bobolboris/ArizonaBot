<?php

namespace App;

use App\Exceptions\UserLogicException;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property mixed state
 * @property mixed substate
 * @property mixed chat_id
 * @property int state_id
 * @property int substate_id
 */
class User extends Model
{
    public $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'chat_id',
        'state_id',
        'substate_id'
    ];

    public function state()
    {
        return $this->hasOne('App\State', 'id', 'state_id');
    }

    public function setState($code)
    {
        $state = State::where('code', $code)->get();
        if (count($state) > 0) {
            $this->state_id = $state[0]->id;
            $this->substate_id = null;
            return $this;
        }
        throw new UserLogicException('Неверный код состояния');
    }

    public function setSubstate($code)
    {
        $this->substate_id = $code;
        return $this;
    }
}
