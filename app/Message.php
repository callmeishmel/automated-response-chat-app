<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
  /**
  * Fields that are mass assignable
  *
  * @var array
  */
  protected $fillable = ['message','canned_message_id','parent_message_id','recipient_id'];

  /**
   * A message belong to a user
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

}
