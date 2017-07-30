<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Accounts extends Model
{
    protected $primaryKey = 'guid';
    protected $appends = ['balance'];
    public $incrementing = false;

    protected $_balance;

    public function scopePeriod($query, $period) {
        $query->with(['summary' => function($query) use ($period) {
            $query->whereHas('transaction', function($query) use ($period) {
                $query->where('post_date', '<', $period->toDateTimeString());
            });
        }]);
    }

    public function summary() {
        return $this->splits()->withGlobalScope(
            'summary', new Scopes\Splits\SummaryScope()
        );
    }

    public function splits() {
        return $this->hasMany('App\Splits', 'account_guid');
    }

    public function transactions() {
        return $this->belongsToMany(
            'App\Transactions', 'splits', 'account_guid', 'tx_guid'
        );
    }

    public function commodity() {
        return $this->belongsTo('App\Commodities', 'commodity_guid');
    }

    public function debits() {
        return $this->splits()->where('quantity_num', '<', 0);
    }

    public function credits() {
        return $this->splits()->where('quantity_num', '>', 0);
    }

    public function getBalanceAttribute() {
        if ( ! $this->_balance) {
            $splits = isset($this->relations['splits'])
                ? $this->splits : $this->summary;

            $this->_balance = new Balance($splits);
        }
        return $this->_balance;
    }
}
