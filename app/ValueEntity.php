<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property int state_id
 * @property string key
 * @property string value
 */
class ValueEntity extends Model
{
    public $table = 'repository_values';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'user_id',
        'state_id',
        'key',
        'value'
    ];
}
