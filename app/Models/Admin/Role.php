<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * Default permissions list
     */
    const DEFAULT_PERMISSIONS = [
        'viewAny' => true,
        'view' => true,
        'create' => false,
        'update' => false,
        'delete' => false,
        'forceDelete' => false,
        'restore' => false
    ];

    /**
     * Permissibles list
     */
    const PERMISSIBLES = [
        Role::class => self::DEFAULT_PERMISSIONS,
        User::class => self::DEFAULT_PERMISSIONS + [
            'promote' => false,
            'demote' => false
        ]
    ];

    /**
     * Fillables
     *
     * @var array
     */
    protected $fillable = ['name', 'permissibles'];
}
