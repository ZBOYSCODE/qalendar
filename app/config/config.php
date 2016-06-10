<?php
return new \Phalcon\Config([
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => ($_SERVER['SERVER_ADDR']=='::1'?'':'z3nta'),
        'dbname' => 'qalendar'
    ],
    'application' => [
        'controllersDir' => APP_DIR . '/controllers/',
        'servicesDir' => APP_DIR . '/services/',
        'dtoDir' => APP_DIR . '/dto/',
        'modelsDir' => APP_DIR . '/models/',
        'formsDir' => APP_DIR . '/forms/',
        'viewsDir' => APP_DIR . '/views/',
        'libraryDir' => APP_DIR . '/library/',
        'pluginsDir' => APP_DIR . '/plugins/',
        'cacheDir' => APP_DIR . '/cache/',
        'baseUri' => '/qalendar/',
        'publicUrl' => '/qalendar',
        'cryptSalt' => 'eEAfR|_&G&f,+vU]:jFr!!A&+71w1Ms9~8_4L!<@[N@DyaIP_2My|:+.u>/6m,$D'
    ],
    'mail' => [
        'fromName' => 'Vokuro',
        'fromEmail' => 'phosphorum@phalconphp.com',
        'smtp' => array(
            'server' => 'smtp.gmail.com',
            'port' => 587,
            'security' => 'tls',
            'username' => '',
            'password' => ''
        )
    ],
    'amazon' => [
        'AWSAccessKeyId' => '',
        'AWSSecretKey' => ''
    ],
	'noAuth' => //noAuth -> configuracion de controller y acciones que no tienen que pasar por la autentificacion
	array('session'=>array('login'=>true,'logout'=>true)),
	'appTitle'=>'QA Calendar',
	'appName'=>"<strong>QA</strong>Calendar",
	'appAutor'=>'Zenta',
	'appAutorLink'=>'http://www.zentagroup.com/',
]);