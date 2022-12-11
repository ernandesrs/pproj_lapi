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
    public const RULABLES_ACTIONS = ['show', 'create', 'update', 'delete', 'force_delete', 'recovery'];

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
            if (key_exists($name, $validated['list'])) {
                $rulables[$name] = $validated['list'][$name];
            } else {
                $rulables[$name] = [];
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
}
