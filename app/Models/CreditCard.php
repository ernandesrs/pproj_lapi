<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    use HasFactory;

    /**
     * Fillable
     * @var array
     */
    protected $fillable = [
        'name',
        'hash',
        'holder_name',
        'cvv',
        'brand',
        'expiration_date',
        'last_digits'
    ];

    /**
     * Hidden fiedls
     * @var array
     */
    protected $hidden = ['hash'];

    /**
     * User
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::retrieved(function ($creditCard) {
            $creditCard->number = "**** **** **** " . $creditCard->last_digits;
        });
    }
}