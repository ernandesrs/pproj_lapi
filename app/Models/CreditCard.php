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
}