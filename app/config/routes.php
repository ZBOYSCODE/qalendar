<?php
/*
 * Define custom routes. File gets included in the router service definition.
 */
$router = new Phalcon\Mvc\Router();

$router->add('/login', array(
    'controller' => 'session',
    'action' => 'login'
));

$router->add('/', array(
    'controller' => 'gestionQa',
    'action' => 'index'
));

$router->add('/logout', array(
    'controller' => 'session',
    'action' => 'logout'
));

$router->add('/reset-password/{code}/{email}', array(
    'controller' => 'user_control',
    'action' => 'resetPassword'
));

$router->add('/calendario', array(
    'controller' => 'GestionQa',
    'action' => 'index'
));

$router->add('/gestion/dia', array(
    'controller' => 'GestionQa',
    'action' => 'vistaDiaria'
));

$router->add('/evento/nuevo', array(
    'controller' => 'GestionQa',
    'action' => 'crearEvento'
));

$router->add('/calendar/changeWeek', array(
    'controller' => 'GestionQa',
    'action' => 'changeCalendarDate'
));

$router->add('/calendar/usuario', array(
    'controller' => 'GestionQa',
    'action' => 'changeCalendarDate'
));

return $router;
