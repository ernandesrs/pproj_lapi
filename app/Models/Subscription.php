<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public const TYPE_NEW = 'new';
    public const TYPE_UPDATE = 'update';
    public const TYPES = [
        self::TYPE_NEW,
        self::TYPE_UPDATE
    ];

    public const STATUS_CANCELED = 'canceled';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_PENDING = 'pending';
    public const STATUS_ENDED = 'ended';
    public const STATUS = [
        self::STATUS_CANCELED,
        self::STATUS_ACTIVE,
        self::STATUS_PENDING
    ];

    /**
     * Fillable
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'package_id',
        'package_metadata',
        'gateway',
        'starts_in',
        'ends_in',
        'type',
        'status'
    ];

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
        static::retrieved(function ($subscription) {
            $subscription->package_metadata = json_decode($subscription->package_metadata);
        });
    }

    /**
     * Set status canceled
     *
     * @return bool
     */
    public function cancel()
    {
        $this->status = self::STATUS_CANCELED;
        $this->package_metadata = json_encode($this->package_metadata);
        return $this->save();
    }
}