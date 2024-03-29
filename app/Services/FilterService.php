<?php

namespace App\Services;

use App\Models\Payment\Card;
use App\Models\Package;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

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

        Package::class => [
            "search" => "name, description",
            "orderBy" => [
                "name" => null,
                "created_at" => "desc"
            ],
            "validationRules" => [
                "orderby-name" => ["nullable", "string"]
            ]
        ],

        Subscription::class => [
            "orderBy" => [
                "created_at" => "desc"
            ],
            "validationRules" => [

            ]
        ],

        Card::class => [
            "orderBy" => [
                "created_at" => "desc"
            ],
            "validationRules" => [

            ]
        ]
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
     * Is Related
     *
     * @var bool
     */
    public $isRelated;

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

    /**
     * Constructor
     *
     * @param mixed $model
     * @param boolean $isRelated signals if it is filtering a relation(Example: filtering logged user credit cards)
     */
    public function __construct($model, bool $isRelated = false)
    {
        $this->model = $model;
        $this->isRelated = $isRelated;
    }

    /**
     * Get/filter
     *
     * @param Request $request
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function filter(Request $request, array $columns = [])
    {
        $this->modelClass = $this->isRelated ? $this->model->getRelated()::class : get_class($this->model);

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

        if (count($columns))
            return $this->model->paginate($this->limit ?? $this->defaultLimit, $columns);

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