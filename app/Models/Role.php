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
    const DEFAULT_ACTIONS_PERMISSIONS = [
        'viewAny' => true,
        'view' => true,
        'create' => false,
        'update' => false,
        'delete' => false,
        'forceDelete' => false,
        'restore' => false
    ];

    /**
     * PERMISSIBLES LIST
     * Manageable models class
     */
    const PERMISSIBLES = [
        Role::class => self::DEFAULT_ACTIONS_PERMISSIONS,
        User::class => self::DEFAULT_ACTIONS_PERMISSIONS + [
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
        $attributes['name'] = \Illuminate\Support\Str::slug($attributes['display_name'], '_');
        $attributes['permissibles'] = json_encode(self::allowedPermissibles());

        $new = new Role($attributes);
        $new->save();

        return $new;
    }

    /**
     * Update
     *
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        // merges existing permissibles with new permissibles and permissions
        $attributes['permissibles'] = array_merge((array) $this->permissibles, $attributes['permissibles']);

        // name
        $attributes['name'] = \Illuminate\Support\Str::slug($attributes['display_name'], '_');

        // permissibles
        $attributes['permissibles'] = json_encode($attributes['permissibles']);

        return parent::update($attributes, $options);
    }

    /**
     * Booted
     *
     * @return void
     */
    protected static function booted()
    {
        static::retrieved(function ($role) {
            $permissibles = (array) json_decode($role->permissibles);

            foreach (self::allowedPermissibles() as $key => $perm) {
                if (!key_exists($key, $permissibles)) {
                    $permissibles[$key] = $perm;
                }
            }

            $role->permissibles = (object) $permissibles;
        });
    }

    /**
     * Allowed permissibles
     *
     * @return array
     */
    public static function allowedPermissibles()
    {
        $permissibleNames = [];

        foreach (self::PERMISSIBLES as $permissibleKey => $permissibleActions) {
            $permissibleKey = str_replace('\\', '_', $permissibleKey);
            $permissibleNames[$permissibleKey] = $permissibleActions;
        }

        return $permissibleNames;
    }

    /**
     * Users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }

    /**
     * Has action permission
     *
     * @param string $action the action(viewAny, view, edit...).
     * @param string $modelClass
     * @return boolean
     */
    public function hasActionPermission(string $action, string $modelClass)
    {
        $permissibleName = str_replace('\\', '_', $modelClass);

        $permissible = $this->permissibles->$permissibleName ?? null;
        if (!$permissible || ($permissible->$action ?? null) === null)
            return false;

        return $permissible->$action;
    }
}