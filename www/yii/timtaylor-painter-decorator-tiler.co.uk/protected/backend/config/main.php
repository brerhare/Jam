<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// Set backend and frontend paths
$backend=dirname(dirname(__FILE__));
$frontend=dirname($backend);
Yii::setPathOfAlias('backend', $backend);

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    // Set base of both to frontend (backend reuses frontend code), and backend c, v and r
    'basePath'=> $frontend,
    'controllerPath' => $backend.'/controllers',
    'viewPath' => $backend.'/views',
    'runtimePath' => $backend.'/runtime',

	'name'=>'Tim Taylor Decorator Backend',

	// preloading 'log' component
	// preloading 'bootstrap' component
	'preload'=>array('log', 'bootstrap'),

	// autoloading model and component classes
	// Note that the order is important - we want backend to overwrite frontend when theres a clash
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'backend.models.*',
		'backend.components.*',
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
			'connectionString' => 'mysql:host=localhost;dbname=timtaylor_painter_decorator_tiler_co_uk',
			'emulatePrepare' => true,
			'username' => 'timtaylor-painte',
			'password' => 'gy48rg5td',
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
		// CKEditor size for page content editing (910 max)
		'editorpagewidth'=>'800',
		'editorpageheight'=>'500',
	),
);

