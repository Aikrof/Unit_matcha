<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
   /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = null;

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = null;

    protected $primaryKey = 'room_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'room_id', 'user_id', 'msg', 'time_sending'
    ];
}
