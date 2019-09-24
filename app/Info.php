<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
   protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'first_name', 'last_name', 'gender', 'icon', 'orientation', 'age', 'interests', 'about'
    ];
}
