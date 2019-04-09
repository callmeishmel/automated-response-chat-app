<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactNotification extends Model
{
  /**
  * Fields that are mass assignable
  *
  * @var array
  */
  protected $fillable = ['customer_id', 'contact_id'];
}
