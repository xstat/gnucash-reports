<?php

namespace App\Scopes\Splits;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SummaryScope implements Scope
{
    public function apply(Builder $builder, Model $model) {
        $builder->addSelect(DB::raw(
            'SUM(quantity_num) AS quantity_num'
        ))->groupBy('account_guid', 'commodity_guid', 'quantity_denom');
    }
}
