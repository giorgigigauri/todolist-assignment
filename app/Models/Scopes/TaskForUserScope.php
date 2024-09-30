<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TaskForUserScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereHas('project', function (Builder $builder) {
            $builder->whereHas('users', function (Builder $b) {
                $b->where('users.id', auth()->id());
            });
        });
    }
}
