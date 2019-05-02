<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresenceEvent extends Model
{
  /**
  * Fields that are mass assignable
  *
  * @var array
  */
  protected $fillable = ['user_id','type'];
}
