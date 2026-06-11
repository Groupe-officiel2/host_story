<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $table = 'servers';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'slots',
    ];


    public function users()
    {
        return $this->belongsToMany(
            \App\Models\User::class,
            'server_user',
            'server_id',
            'user_id'
        );
    }
}