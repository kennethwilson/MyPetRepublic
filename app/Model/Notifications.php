<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['type','notifiable_id','notifiable_type','data','read_at'];
}
