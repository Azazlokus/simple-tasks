<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class UserFilters
{
    public static function apply(Builder $query, Request $request): Builder
    {
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        $orderBy = $request->input('order_by', 'created_at');
        $orderDirection = $request->input('order_direction', 'desc');

        if (in_array($orderBy, ['id', 'name', 'email', 'status', 'created_at', 'updated_at']) &&
            in_array($orderDirection, ['asc', 'desc'])) {
            $query->orderBy($orderBy, $orderDirection);
        }

        return $query;
    }
}