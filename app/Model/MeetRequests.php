<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MeetRequests extends Model
{
  protected $table = 'meet_requests';
  protected $fillable = ['requester_dog_id','requested_dog_id'];
}
