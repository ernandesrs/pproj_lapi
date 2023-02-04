<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FilterService
{
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
            "validationRules" => [
                "orderby-level" => ["nullable", "string"],
                "orderby-first_name" => ["nullable", "string"]
            ]
        ],

        Role::class => [
            "search" => "display_name",

            "orderBy" => [
                // orderField => default value
                "display_name" => null,
                "created_at" => "desc",
            ],

            // specific rules
            "validationRules" => [
                "orderby-display_name" => ["nullable", "string"]
            ]
        ],
    ];

    /**
     * Limit
     *
     * @var int
     */
    protected $defaultLimit = 20;

    /**
     * Model
     * @var \Illuminate\Database\Eloquent\Builder
     */
    public $model;

    /**
     * Filters
     * @var array
     */
    private $filters;

    /**
     * Current filterable class
     *
     * @var string
     */
    protected $modelClass;

    public function __construct($model, ?int $limit = null)
    {
        $this->model = $model;
        $this->defaultLimit = $limit ?? $this->defaultLimit;
    }

    /**
     * Get/filter
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function filter(Request $request)
    {
        $this->modelClass = get_class($this->model);

        $this->validateFilters($request);

        if ($search = $this->search) {
            $fields = $this->filterablesFields[$this->modelClass]["search"] ?? null;

            if ($fields)
                $this->model = $this->model->whereRaw("MATCH(" . $fields . ") AGAINST('" . $search . "')");
        }

        $orderBy = $this->orderBy;
        if ($orderBy) {
            foreach ($orderBy as $field => $order) {
                if ($order)
                    $this->model = $this->model->orderBy($field, $order);
            }
        }

        return $this->model->paginate($this->limit ?? $this->defaultLimit);
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
            "search" => ["nullable", "string", "min:1", "max:25"]
        ] + $this->filterablesFields[$this->modelClass]["validationRules"] ?? []);

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