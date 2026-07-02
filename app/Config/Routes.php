<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth::login');

// Auth Routes
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/process', 'Auth::process');
$routes->get('auth/logout', 'Auth::logout');

// Web Routes (Protected)
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    
    // Items Web CRUD
    $routes->get('items', 'Items::index');
    $routes->get('items/create', 'Items::create');
    $routes->post('items/store', 'Items::store');
    $routes->get('items/edit/(:num)', 'Items::edit/$1');
    $routes->put('items/update/(:num)', 'Items::update/$1');
    $routes->delete('items/delete/(:num)', 'Items::delete/$1');

    // Activities Route
    $routes->get('activities', 'Activities::index');
    $routes->delete('activities/delete/(:num)', 'Activities::delete/$1');

    // Workspace Routes
    $routes->get('workspaces', 'Workspace::index');
    $routes->post('workspaces/store', 'Workspace::store');
    $routes->post('workspaces/update/(:num)', 'Workspace::update/$1');
    $routes->get('workspaces/delete/(:num)', 'Workspace::delete/$1');
    $routes->get('workspaces/select/(:num)', 'Workspace::select/$1');
});

// REST API Routes
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    // route untuk api barang
    $routes->get('items', 'Items::index');
    $routes->get('items/(:num)', 'Items::show/$1');
    $routes->post('items', 'Items::create');
    $routes->put('items/(:num)', 'Items::update/$1');
    $routes->delete('items/(:num)', 'Items::delete/$1');
});
