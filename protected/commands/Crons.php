<?php
$yii = '/opt/lampp/htdocs/yii/framework/yii.php';
require_once($yii);
$configFile = dirname(__FILE__).'/../config/console.php';
date_default_timezone_set('PRC');
Yii::createConsoleApplication($configFile)->run();