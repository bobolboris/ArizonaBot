<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * @property int id
 * @property string name
 * @property int code
 */
class State extends Model
{
    public $table = 'states';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'code'
    ];
}
