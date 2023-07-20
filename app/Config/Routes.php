<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// View Routes
$routes->get('/', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// public api routes with authentication filter
$routes->group('', ['namespace' => 'App\Controllers'], function($routes) {
    // Wizard Language routes
    $routes->get('wizard', 'Wizard::index');
    $routes->get('wizard-language', 'Wizard::wizard_language');
    $routes->post('save-language', 'Wizard::language_insert');
    $routes->get('check-data', 'Wizard::check_data');
    $routes->get('update-server-time/(:any)', 'Wizard::update_server_time/$1');

    // License routes
    $routes->get('license', 'License::index');

    // Card Format Route
    $routes->get('cardformat', 'CardFormat::index');
    $routes->get('get-card-format', 'CardFormat::select');
    $routes->post('add-card-format', 'CardFormat::insert');
    $routes->post('update-card-format', 'CardFormat::update');
    $routes->get('card-format-update-default/(:num)', 'CardFormat::update_default/$1');
    
});

$routes->group('api', ['namespace' => 'App\Controllers\api'], function($routes) {
    $routes->post('login', 'Auth::login');
});

// private api routes
$routes->group('api', ['namespace' => 'App\Controllers\api', 'filter' => ['authFilter']],  function($routes) {
    
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
