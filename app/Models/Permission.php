<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * Rullables
     * 
     * @var array
     */
    public const RULABLES = [
        'permission' => Permission::class,
        'user' => User::class
    ];

    /**
     * Rullables actions
     * 
     * @var array
     */
    public const RULABLES_ACTIONS = ['view', 'viewAny', 'create', 'update', 'delete', 'forceDelete', 'restory'];

    /**
     * Fillable
     *
     * @var array
     */
    protected $fillable = ['name', 'list'];

    /**
     * Create
     *
     * @param array $validated
     * @return Permission
     */
    public static function create(array $validated)
    {
        $new = new Permission();
        $new->name = $validated['name'];
        $new->list = json_encode($new->makeList($validated));
        $new->save();

        $new->list = json_decode($new->list);

        return $new;
    }

    /**
     * Make list
     *
     * @param array $validated
     * @return array
     */
    public function makeList(array $validated)
    {
        $rulables = self::RULABLES;

        foreach ($rulables as $name => $rulable) {
            $rulables[$name] = array_fill_keys(self::RULABLES_ACTIONS, false);

            if ($validated['list'][$name] ?? null) {
                foreach ($validated['list'][$name] as $actionName => $actionActive) {
                    $rulables[$name][$actionName] = $actionActive;
                }
            }
        }

        return $rulables;
    }

    /**
     * Booted
     *
     * @return void
     */
    protected static function booted()
    {
        static::retrieved(function ($permission) {
            $permission->list = json_decode($permission->list);
        });
    }

    /**
     * Users using this permission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
