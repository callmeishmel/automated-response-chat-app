<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CannedMessage extends Model
{
    /**
    * Fields that are mass assignable
    *
    * @var array
    */
    protected $fillable = ['message'];

}
