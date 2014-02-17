<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'FADGuide',

	// Override the default controller
	//'defaultController'=>'contentBlock',

	// preloading 'log' component
	// preloading 'yiibooster' component
	'preload'=>array('log', 'bootstrap'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.wirefly.jelly-current.*',
		'application.extensions.PHPMailer.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/**/
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'kimlo,',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
			'generatorPaths' => array(
				'bootstrap.gii'
			),
		),
		/**/
	),

	// application components
	'components'=>array(
		'bootstrap'=>array(
			'class' => 'ext.bootstrap.components.Bootstrap',
			'responsiveCss' => true,
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		/**/
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		/**/
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		// uncomment the following to use a MySQL database
		/**/
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=fadguide_com',
			'emulatePrepare' => true,
			'username' => 'fadguide.com',
			'password' => 'wi6fhw5m9',
			'charset' => 'utf8',
		),
		/**/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@wireflydesign.com',
		// These are used by the shopping cart checkout process
		'checkoutEmail'=>'jo@fadguide.com',
		'checkoutName'=>'FAD Guide',
		// Test gateway
		'checkoutGatewayUser'=>'WIREFL-5188100',
		'checkoutGatewayPassword'=>'391F3GWBZ0',
		// Live gateway
		//'checkoutGatewayUser'=>'LIVE_USER-1645722',
		//'checkoutGatewayPassword'=>'LIVE_PWORD',
		'jellyRoot' => '/scripts/jelly/',
		'sid' => '7la2ct0hmdlj74mdo98bfmoge2',
	),
);
