<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string name
 * @property int number
 */
class Server extends Model
{
    public $table = 'servers';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'number'
    ];
}
