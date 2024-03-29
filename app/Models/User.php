<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    public const LEVEL_COMMON = 0;
    public const LEVEL_ADMIN = 8;
    public const LEVEL_SUPER = 9;
    public const LEVELS = [
        self::LEVEL_COMMON,
        self::LEVEL_ADMIN,
        self::LEVEL_SUPER
    ];

    public const STATUS_DELETED = 'deleted';
    public const STATUS = [
        self::STATUS_DELETED
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'gender',
        'photo',
        'verification_token',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
        'google_id',
        'facebook_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Where has admin access
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function whereHasAdminAccess()
    {
        return $this->where('level', self::LEVEL_ADMIN)->orWhere('level', self::LEVEL_SUPER);
    }

    /**
     * Delete
     *
     * @return bool|null
     */
    public function delete()
    {
        $this->roles()->detach();

        return parent::delete();
    }

    /**
     * Permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->roles();
    }

    /**
     * Roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * Addresses
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, "user_id", "id");
    }

    /**
     * Email update
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function emailUpdate()
    {
        return $this->hasOne(UserEmailUpdate::class, "user_id", "id");
    }

    /**
     * Is super admin
     *
     * @return boolean
     */
    public function isSuperadmin()
    {
        return $this->level === self::LEVEL_SUPER;
    }

    /**
     * Is super admin
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->level === self::LEVEL_ADMIN;
    }

    /**
     * Check if has permission for specified action
     *
     * @param string $action (view, viewAny, create, ...)
     * @param string $modelClass permissible class
     * @return boolean
     */
    public function hasPermission(string $action, string $modelClass)
    {
        $roles = $this->roles()->get();

        if (!$roles->count())
            return false;

        $filtered = $roles->first(function ($role) use ($action, $modelClass) {
            return $role->hasActionPermission($action, $modelClass);
        });

        return $filtered ? true : false;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}