<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'id',
        'price',
        'interval',
        'paypal_plan_id'
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}