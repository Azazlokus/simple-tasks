<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class TaskFilters
{
    public static function apply(Builder $query, Request $request): Builder
    {
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [
                $request->input('date_from'),
                $request->input('date_to')
            ]);
        }

        $orderBy = $request->input('order_by', 'created_at');
        $orderDirection = $request->input('order_direction', 'desc');

        if (in_array($orderBy, ['id', 'title', 'status', 'created_at', 'updated_at']) &&
            in_array($orderDirection, ['asc', 'desc'])) {
            $query->orderBy($orderBy, $orderDirection);
        }

        return $query;
    }
}