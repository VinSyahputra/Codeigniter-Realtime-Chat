<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Counter::index');
// $routes->post('/', 'Counter::increment');

$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::loginDashboard');
$routes->post('/logout', 'AuthController::logout');
$routes->get('/register', 'AuthController::register');
$routes->post('/register', 'AuthController::registerAdd');

$routes->post('/send', 'Counter::sendMessage');
$routes->post('/searchUser', 'Counter::searchUser');
$routes->post('/getMessages', 'Counter::getMessages');
