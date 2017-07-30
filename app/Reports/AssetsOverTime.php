<?php

namespace App\Reports;

class AssetsOverTime
{
    public function run($period) {
        $accounts = \App\Accounts::period($period)->whereIn(
            'account_type', ['ASSET', 'BANK', 'CASH']
        )->get();

        return $accounts->filter(function($a) {
            return ! $a->balance->zero();
        })->sort(function($a, $b) {
            return $a->name >= $b->name;
        })->mapWithKeys(function($a) {
            $guid = substr($a->guid, -4);
            return [sprintf('%s (%s - %s)', $a->name, $a->account_type, $guid) => $a->balance];
        });
    }
}
