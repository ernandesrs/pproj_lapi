<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

trait TraitFilter
{
    /**
     * Filter
     *
     * @var int
     */
    protected $filters = 20;

    /**
     * Filterables classes
     *
     * @var array
     */
    protected $filterablesFields = [
        User::class => [
            "search" => "first_name,last_name,username,email",

            "orderBy" => [
                // orderField => default value
                "level" => "desc",
                "first_name" => null,
                "created_at" => "desc",
            ],

            // specific rules
            "rules" => [
                "orderby-level" => ["nullable", "string"],
                "orderby-first_name" => ["nullable", "string"]
            ]
        ],

        Permission::class => [
            "search" => "name",

            "orderBy" => [
                // orderField => default value
                "name" => null,
                "created_at" => "desc",
            ],

            // specific rules
            "rules" => [
                "orderby-name" => ["nullable", "string"]
            ]
        ],
    ];

    /**
     * Current filterable class
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Get/filter
     *
     * @param Request $request
     * @param [type] $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filter(Request $request, $model)
    {
        $this->modelClass = get_class($model);

        $this->validateFilters($request);

        if ($search = $this->search) {
            $fields = $this->filterablesFields[$this->modelClass]["search"] ?? null;

            if ($fields)
                $model = $model->whereRaw("MATCH(" . $fields . ") AGAINST('" . $search . "')");
        }

        $orderBy = $this->orderBy;
        if ($orderBy) {
            foreach ($orderBy as $field => $order) {
                if ($order)
                    $model =  $model->orderBy($field, $order);
            }
        }

        return $model;
    }

    /**
     * Filter inptus
     *
     * @param Request $request
     * @return void
     */
    private function validateFilters(Request $request)
    {
        $this->filters = $request->validate([
            "limit" => ["nullable", "numeric", "min:1", "max:20"],
            "search" => ["nullable", "string", "min:1", "max:25"],
            "orderby-created_at" => ["nullable", "string", Rule::in(["asc", "desc"])],
        ] + $this->filterablesFields[$this->modelClass]["rules"] ?? []);

        $this->filters["orderBy"] = $this->filterablesFields[$this->modelClass]["orderBy"] ?? [];

        foreach ($this->filters as $key => $filter) {
            $keyArr = explode("-", $key);
            if ($keyArr[0] === "orderby") {
                $this->filters["orderBy"][$keyArr[1]] = $filter;
            }
        }
    }

    /**
     * Get
     *
     * @param [type] $key
     * @return void
     */
    public function __get($key)
    {
        return $this->filters[$key] ?? null;
    }
}
