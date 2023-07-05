<?php

namespace App\Models\Payment;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    public const PAY_METHOD_UNDEFINED = 'undefined';
    public const PAY_METHOD_CREDIT_CARD = 'credit_card';
    public const PAY_METHOD_DEBIT_CARD = 'debit_card';

    public const PAY_METHODS = [
        self::PAY_METHOD_UNDEFINED,
        self::PAY_METHOD_CREDIT_CARD,
        self::PAY_METHOD_DEBIT_CARD
    ];

    /**
     * User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cards
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    /**
     * Get the preferred card
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|null
     */
    public function preferredCard()
    {
        return $this->preferred_card_id ? $this->cards()->where("preferred_card_id", $this->preferred_card_id) : null;
    }

    /**
     * Booted
     *
     * @return void
     */
    protected static function booted()
    {
        static::retrieved(function ($paym) {
            $paym->cards = $paym->cards()->get();
        });

        static::created(function ($paym) {
            $paym->cards = $paym->cards()->get();
        });
    }
}