<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\DB;

use App\Gnucash\Model\Period;
use App\Gnucash\Reports\CashFlow;
use App\Gnucash\Reports\AssetsOverTime;

Route::get('/', function () {

    $acc = 'd46bb2e22a68f74aebd0770c08c3be47';
    $tx_gda = 'bdb5f299f85dc25e3a6709b26bec85f9';
    $ss_gda = [
        '5529f23cf30ba56532a7556c17d833be',
        'ad27db6a9b33080b678ccc91fb3008c9',
    ];
    $usd = 'c7e767c471c47b3cac6b4494df7dcbce';
    $ars = '1c9c24cb4b2a4cc51d15499065e7dc0c';

    $start = new \Carbon\Carbon('2017-01-01');

    $periods = collect([
        $start->copy()->startOfDay()->addDay()->addHours(3),
        $start->copy()->addMonths(1)->startOfDay()->addDay()->addHours(3),
        $start->copy()->addMonths(2)->startOfDay()->addDay()->addHours(3),
        $start->copy()->addMonths(3)->startOfDay()->addDay()->addHours(3),
        $start->copy()->addMonths(4)->startOfDay()->addDay()->addHours(3),
        $start->copy()->addMonths(5)->startOfDay()->addDay()->addHours(3),
        $start->copy()->addMonths(6)->startOfDay()->addDay()->addHours(3),
    ]);

    $report = new \App\Reports\AssetsOverTime();

    return $periods->mapWithKeys(function($period) use ($report) {
        return [$period->toDateTimeString() => $report->run($period)];
    });
});
