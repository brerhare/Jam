<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

    //@@EG Had to add the next 'import' to load the db
    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
    ),

	// application components
	'components'=>array(
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		// uncomment the following to use a MySQL database
		/**/
		'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=plugin',
            'emulatePrepare' => true,
            'username' => 'plugin',
            'password' => 'plugin,',
            'charset' => 'utf8',
        ),
		/**/
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);
