<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    protected Request $request;

    protected Builder $query;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query)
    {
        $this->query = $query;

        foreach ($this->fields() as $field => $value) {
            $method = Str::camel($field);
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], [$value]);
            }
        }
    }

    protected function fields(): array
    {
        return array_map(function ($item) {
            if (is_array($item)) {
                return $item;
            }

            if (is_bool($item)) {
                return $item;
            }

            return trim($item);
        }, $this->request->all());
    }
}
