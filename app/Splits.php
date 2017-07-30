<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Splits extends Model
{
    protected $primaryKey = 'guid';
    public $incrementing = false;

    protected $attributes = [
        'quantity_num' => 0,
        'quantity_denom' => 1,
    ];

    protected $fillable = ['commodity_guid'];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new \App\Scopes\Splits\CommodityScope());
    }

    public function account() {
        return $this->belongsTo('App\Accounts', 'account_guid');
    }

    public function transaction() {
        return $this->belongsTo('App\Transactions', 'tx_guid');
    }

    public function getQuantityAttribute() {
        return $this->quantity_num / $this->quantity_denom ?: 1;
    }

    public function zero() {
        return $this->quantity_num === 0;
    }

    public function sum(Splits $split) {
        $this->normalize($split);
        $split->normalize($this);

        $this->quantity_num += $split->quantity_num;
    }

    public function normalize(Splits $split) {
        if ($split->quantity_denom > $this->quantity_denom) {
            $this->quantity_num *= $split->quantity_denom;
            $this->quantity_num /= $this->quantity_denom ?: 1;
            $this->quantity_denom = $split->quantity_denom;
        }
    }
}
