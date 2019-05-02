<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PusherWebhook extends Model
{
  /**
  * Fields that are mass assignable
  *
  * @var array
  */
  protected $fillable = ['type','remote_address','post'];

}
