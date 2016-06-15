<?php
/*
 * Define custom routes. File gets included in the router service definition.
 */
$router = new Phalcon\Mvc\Router();

$router->add('/{us}', array(
    'controller' => 'Usuarios',
    'action' => 'profile'
));


return $router;
