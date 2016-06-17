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

$router->add('/calendario', array(
    'controller' => 'GestionQa',
    'action' => 'vistaSemanal'
));

$router->add('/gestion/semana', array(
    'controller' => 'GestionQa',
    'action' => 'vistaSemanal'
));

$router->add('/gestion/dia', array(
    'controller' => 'GestionQa',
    'action' => 'vistaDiaria'
));

$router->add('/evento/nuevo', array(
    'controller' => 'Actividad',
    'action' => 'crearEvento'
));

$router->add('/calendar/changeWeekByDay', array(
    'controller' => 'GestionQa',
    'action' => 'changeCalendarDate'
));

$router->add('/calendar/changeWeekByUser', array(
    'controller' => 'GestionQa',
    'action' => 'changeCalendarUser'
));

$router->add('/calendar/changeDay', array(
    'controller' => 'GestionQa',
    'action' => 'changeDailyDate'
));

$router->add('/calendar/usuario', array(
    'controller' => 'GestionQa',
    'action' => 'changeCalendarDate'
));


$router->add('/actividad/editar/{$id}', array(
    'controller' => 'Actividad',
    'action' => 'editar'
));

$router->add('/actividad/perfil/:params', array(
    'controller' => 'Actividad',
    'action' => 'verPerfilEvento',
    'params' => 1
));

return $router;
