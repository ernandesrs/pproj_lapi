<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    public const RULABLES = [
        'permission' => Permission::class,
        'user' => User::class
    ];

    public const RULES = ['show', 'create', 'update', 'delete', 'force_delete', 'recovery'];

    protected static function booted()
    {
        static::retrieved(function ($permission) {
            $permission->list = json_decode($permission->list);
        });
    }
}
