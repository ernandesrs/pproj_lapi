<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

trait TraitFilter
{
    /**
     * Filter
     *
     * @var int
     */
    protected $filters = 20;

    /**
     * Get/filter
     *
     * @param Request $request
     * @param [type] $model
     * @return [type]
     */
    protected function filter(Request $request, $model)
    {
        $this->validateFilters($request);

        $model = $model->whereNotNull("id");

        return $model->paginate($this->limit);
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
            "limit" => ["numeric"]
        ]);
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
