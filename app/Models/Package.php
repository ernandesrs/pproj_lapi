<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    /**
     * Fillable
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'expiration_month',
        'show'
    ];

    /**
     * Subscriptions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}