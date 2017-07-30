<?php

namespace App\Scopes\Splits;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CommodityScope implements Scope
{
    public function apply(Builder $builder, Model $model) {
        $builder->addSelect(['splits.*', 'commodities.guid as commodity_guid'])
            ->join('accounts', 'accounts.guid', '=', 'splits.account_guid')
            ->join('commodities', 'commodities.guid', '=', 'accounts.commodity_guid');
    }
}
