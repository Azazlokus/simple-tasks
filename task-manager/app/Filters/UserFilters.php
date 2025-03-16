<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class UserFilters
{
    private Request $request;
    private Builder $query;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        $this->query = $query;

        return $this->filterByStatus()
                    ->filterByName()
                    ->filterByEmail()
                    ->applySorting()
                    ->query;
    }

    private function filterByStatus(): self
    {
        if ($this->request->filled('status')) {
            $this->query->where('status', $this->request->input('status'));
        }
        return $this;
    }

    private function filterByName(): self
    {
        if ($this->request->filled('name')) {
            $this->query->where('name', 'like', '%' . $this->request->input('name') . '%');
        }
        return $this;
    }

    private function filterByEmail(): self
    {
        if ($this->request->filled('email')) {
            $this->query->where('email', 'like', '%' . $this->request->input('email') . '%');
        }
        return $this;
    }

    private function applySorting(): self
    {
        $orderBy = $this->request->input('order_by', 'created_at');
        $orderDirection = $this->request->input('order_direction', 'desc');

        if (in_array($orderBy, ['id', 'name', 'email', 'status', 'created_at', 'updated_at']) &&
            in_array($orderDirection, ['asc', 'desc'])) {
            $this->query->orderBy($orderBy, $orderDirection);
        }

        return $this;
    }
}