<?php

use Chendujin\Mobile\Mobile;

include './src/Mobile.php';

$mobile = new Mobile();
$info = $mobile->search('18621281566');
$infos = $mobile->search('19200891410');
print_r($info);
print_r($infos);
// Array
// (
//     [mobile] => 18621281566
//     [province] => 上海
//     [city] => 上海
//     [zip_code] => 200000
//     [area_code] => 021
//     [operator_type] => 联通
// )
// Array
// (
//     [mobile] => 19200891410
//     [province] => 广东
//     [city] => 广州
//     [zip_code] => 510000
//     [area_code] => 020
//     [operator_type] => 广电
// )

var_dump($mobile->isTelPhone('5957126')); // true
var_dump($mobile->isMobilePhone('13299257009')); // true
