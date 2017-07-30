<?php

namespace App;

use App\Traits\CacheTrait;
use Illuminate\Database\Eloquent\Model;

class Commodities extends Model
{
    use CacheTrait;

    protected $primaryKey = 'guid';
    protected $appends = ['total'];
    public $incrementing = false;

    protected $_total;

    public function accounts() {
        return $this->hasMany('App\Accounts', 'commodity_guid');
    }

    public function getTotalAttribute() {
        return $this->_total ?: $this->_total = new Splits([
            'commodity_guid' => $this->guid
        ]);
    }

    public function add($split) {
        $this->total->sum($split);
    }
}
