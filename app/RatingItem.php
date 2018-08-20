<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * @property int id
 * @property string name
 * @property string shortcut
 */
class RatingItem extends Model
{
    public $table = 'rating_items';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'shortcut'
    ];
}
