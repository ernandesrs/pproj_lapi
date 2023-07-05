<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
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
    protected $hidden = ['hash', 'cvv'];

    /**
     * Update
     *
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        unset($this->secure_number);
        unset($this->secure_cvv);
        return parent::update($attributes, $options);
    }

    /**
     * User
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Booted
     *
     * @return void
     */
    protected static function booted()
    {
        static::retrieved(function ($card) {
            $card->secure_number = "**** **** **** " . $card->last_digits;
            $card->secure_cvv = "***" . $card->cvv;
        });
    }
}