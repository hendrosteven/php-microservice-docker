<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Origin, Cache-Control, X-Requested-With, Content-Type, Access-Control-Allow-Origin');
header('Access-Control-Allow-Methods: *');
header('Content-type: application/json');

require 'vendor/autoload.php';

//f3 bootstrap
$f3 = Base::instance();
$f3->config('config/config.ini');
$f3->config('config/routes.ini');

//validator config
use Valitron\Validator as V;
V::langDir('vendor/vlucas/valitron/lang');
V::lang('id');

//timezone
date_default_timezone_set($f3->get('timezone'));

//globar error
$f3->set('ONERROR',function($f3){
    $logger = new Log("logs/". date("Y-m-d") . ".log"); 
    $logger->write('[ERRORS ' .  $f3->get('ERROR.code') .'] ' . $f3->get('ERROR.text'));
    $logger->write('[TRACE] '. $f3->get('ERROR.trace'));
    API::error($f3->get('ERROR.code'), $f3->get('ERROR.text'));
});

$f3->run();
