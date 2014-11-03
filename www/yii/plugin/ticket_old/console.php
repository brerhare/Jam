<?php

// Arg 1 indicates the file to run. Eg: 'VendorReport' runs ./protected/commands/VendorReportCommand.php
//                                      'TicketReminder' runs ./protected/commands/TicketReminderCommand.php
// Arg 2 (eg: 'KronOS') is purely for validation to stop users running this manually

if ($argv[2] != "KronOS")
{
	sleep(56038);
	exit;
};

// change the following paths if necessary
$yii=dirname(__FILE__).'/../../../../../extern/yii/framework/yii.php';

$config=dirname(__FILE__).'/protected/config/console.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createConsoleApplication($config)->run();
