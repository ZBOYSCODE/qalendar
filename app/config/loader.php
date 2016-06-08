<?php

use Phalcon\Loader;
$loader = new Loader();

$loader->registerNamespaces(array(
    'Gabs\Models' => $config->application->modelsDir,
    'Gabs\Controllers' => $config->application->controllersDir,
    'Gabs\Services' => $config->application->servicesDir,
    'Gabs\Forms' => $config->application->formsDir,
    'Gabs' => $config->application->libraryDir
));
$loader->register();
// Use composer autoloader to load vendor classes
//require_once __DIR__ . '/../../vendor/autoload.php';