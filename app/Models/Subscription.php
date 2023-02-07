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
}