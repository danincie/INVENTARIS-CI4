<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth::login');

$routes->get('auth/login', 'Auth::login');
$routes->post('auth/process', 'Auth::process');
$routes->get('auth/logout', 'Auth::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    
    $routes->get('items', 'Items::index');
    $routes->get('items/create', 'Items::create');
    $routes->post('items/store', 'Items::store');
    $routes->get('items/edit/(:num)', 'Items::edit/$1');
    $routes->put('items/update/(:num)', 'Items::update/$1');
    $routes->delete('items/delete/(:num)', 'Items::delete/$1');

    $routes->get('activities', 'Activities::index');
    $routes->delete('activities/delete/(:num)', 'Activities::delete/$1');

    $routes->get('workspaces', 'Workspace::index');
    $routes->post('workspaces/store', 'Workspace::store');
    $routes->post('workspaces/update/(:num)', 'Workspace::update/$1');
    $routes->get('workspaces/delete/(:num)', 'Workspace::delete/$1');
    $routes->get('workspaces/select/(:num)', 'Workspace::select/$1');
});

$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    $routes->get('items', 'Items::index');
    $routes->get('items/(:num)', 'Items::show/$1');
    $routes->post('items', 'Items::create');
    $routes->put('items/(:num)', 'Items::update/$1');
    $routes->delete('items/(:num)', 'Items::delete/$1');
});
