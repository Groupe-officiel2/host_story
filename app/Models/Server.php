<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\User;
class Server extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'slots'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'server_user')
            ->withPivot('role')
            ->withTimestamps();
    }
}
