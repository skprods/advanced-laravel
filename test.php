<?php

use SKprods\AdvancedLaravel\Facades\Console;
use SKprods\AdvancedLaravel\Handlers\ConsoleOutput;
use SKprods\AdvancedLaravel\Path;

require_once "vendor/autoload.php";

//use SKprods\AdvancedLaravel\Eloquent\MultUpdater;
//
//$query = MultUpdater::table('goods_reviews')
//    ->setWhere('promotion_status', 'id', '=', '1', 'approvedOk,allOk')
//    ->setWhereIn('status', 'id', [1,2,3,4], 'active')
//    ->setWhereNull('another', 'status', null)
//    ->toSql();
//
//dd($query);


dd(Path::prepareFile('/fdsgf///fdsgfsd/gfsd/resg.jpd'));