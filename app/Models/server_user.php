<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class server_user extends Model
{
    protected $table = 'server_user';

    public $timestamps = false;

    protected $fillable = [
        'server_id',
        'user_id',
    ];
}