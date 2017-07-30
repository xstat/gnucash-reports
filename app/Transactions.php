<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $primaryKey = 'guid';
    public $incrementing = false;

    public function accounts() {
        return $this->belongsToMany(
            'App\Accounts', 'splits', 'tx_guid', 'account_guid'
        );
    }

    public function splits() {
        return $this->hasMany('App\Splits', 'tx_guid');
    }
}
