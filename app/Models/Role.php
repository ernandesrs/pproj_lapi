<?php

namespace App\Models;

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
    protected $fillable = ['name', 'display_name', 'permissibles'];

    /**
     * Create
     *
     * @param array $attributes
     * @return Role
     */
    public static function create(array $attributes)
    {
        $attributes['name'] = \Illuminate\Support\Str::slug($attributes['display_name']);
        $attributes['permissibles'] = json_encode($attributes['permissibles']);

        $new = new Role($attributes);
        $new->save();

        return $new;
    }

    /**
     * Booted
     *
     * @return void
     */
    protected static function booted()
    {
        static::retrieved(function ($role) {
            $role->permissibles = json_decode($role->permissibles);
        });
    }

    public static function allowedPermissibles()
    {
        $p = [];

        foreach (self::PERMISSIBLES as $k => $v) {
            $k = str_replace('\\', '_', $k);
            $p[$k] = $v;
        }

        return $p;
    }
}
