<?php

namespace App;

class Balance implements \JsonSerializable
{
    public $commodities;

    public function __construct($splits = null) {
        $this->commodities = collect();

        $splits && $splits->each(function($split) {
            $this->add($split);
        });
    }

    public function add($split) {
        $this->get($split->commodity_guid)->add($split);
    }

    public function get($commodity_guid) {
        if ( ! $this->commodities->has($commodity_guid)) {
            if ( ! $commodity = Commodities::cache($commodity_guid)) {
                throw new \Exception('Invalid commodity.');
            }
            $this->commodities->put($commodity_guid, $commodity);
        }
        return $this->commodities->get($commodity_guid);
    }

    public function zero() {
        return $this->commodities->every(function($commodity) {
            return $commodity->total->zero();
        });
    }

    public function exchange($commodity_guid) {
        return $this->get($commodity_guid);
    }

    public function jsonSerialize() {
        return $this->commodities->mapWithKeys(function($c) {
            return [$c->mnemonic => number_format($c->total->quantity, 2)];
        });
    }
}
