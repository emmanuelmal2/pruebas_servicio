<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('login', 'Login::index');
$routes->post('login/autenticar', 'Login::autenticar');
$routes->get('salir', 'Login::salir');

// Ruta protegida
$routes->get('panel', 'Panel::index');

