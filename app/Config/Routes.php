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
$routes->post('forgot-password', 'Auth::forgot_password');
$routes->get('logout', 'Auth::logout');

// public api routes with authentication filter
$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    // Wizard Language routes
    $routes->get('wizard', 'Wizard::index');
    $routes->get('wizard-language', 'Wizard::wizard_language');
    $routes->post('save-language', 'Wizard::language_insert');
    $routes->get('restore-holiday', 'Wizard::restore_holiday');
    $routes->get('check-data', 'Wizard::check_data');
    $routes->get('update-server-time/(:any)', 'Wizard::update_server_time/$1');

    // License routes
    $routes->get('license', 'License::index');
    $routes->get('license-edit-option', 'License::edit_option');
    $routes->get('license-send-order', 'License::sendorder');
    $routes->get('license-send-register', 'License::sendregister');
    $routes->get('license-valid-send-order', 'License::valid_sendorder');

    // Card Format Route
    $routes->get('cardformat', 'CardFormat::index');
    $routes->get('cardformat-get', 'CardFormat::select');
    $routes->post('cardformat-add', 'CardFormat::insert');
    $routes->post('cardformat-update', 'CardFormat::update');
    $routes->get('cardformat-update-default/(:num)', 'CardFormat::update_default/$1');
    $routes->get('cardformat-check-dependency', 'CardFormat::check_dependency');
    $routes->get('cardformat-delete', 'CardFormat::delete');
    $routes->get('cardformat-calculate', 'CardFormat::calculate');

    // Holiday Group Route
    $routes->get('holiday', 'Holiday::index');
    $routes->get('holiday-get', 'Holiday::select');
    $routes->post('holiday-add', 'Holiday::insert');
    $routes->post('holiday-update', 'Holiday::update');

    // Schedule Route
    $routes->get('schedule', 'Schedule::index');
    $routes->get('schedule-get', 'Schedule::select');
    $routes->post('schedule-add', 'Schedule::insert');
    $routes->post('schedule-update', 'Schedule::update');
    $routes->get('schedule-delete', 'Schedule::delete');

    // Door Route
    $routes->get('door', 'Door::index');
    $routes->get('door-get', 'Door::select');
    $routes->post('door-add', 'Door::insert');
    $routes->post('door-update', 'Door::update');
    $routes->get('door-delete', 'Door::delete');
    $routes->post('door-control', 'Door::door_control');

    // Access Level Route
    $routes->get('access-level', 'AccessLevel::index');
    $routes->get('access-level-get', 'AccessLevel::select');
    $routes->post('access-level-add', 'AccessLevel::insert');
    $routes->post('access-level-update', 'AccessLevel::update');
    $routes->get('access-level-delete', 'AccessLevel::delete');

    // Help routes
    $routes->get('help/(:segment)/(:segment)', 'Help::index');

    // Ipset routes
    $routes->get('ipset', 'Ipset::index');
    $routes->get('ipset-get', 'Ipset::select');
    $routes->post('ipset-update', 'Ipset::update1');
    $routes->post('ipset-save_cert', 'Ipset::save_cert');

    // Ipset cert routes
    $routes->get('ipset-cert', 'Ipset::cert');

    // Ftp routes
    $routes->get('ftp', 'Ftp::index');
    $routes->get('ftp-get', 'Ftp::select');
    $routes->post('ftp-update', 'Ftp::update3');

    // Smtp routes
    $routes->get('smtp', 'Smtp::index');
    $routes->get('smtp-get', 'Smtp::select');
    $routes->post('/smtp-update', 'Smtp::update4');

    // Time server routes
    $routes->get('timesvr', 'Timesvr::index');
    $routes->get('timesvr-select', 'Timesvr::select');
    $routes->post('timesvr-update', 'Timesvr::update4');

    // Rmc routes
    $routes->get('rmc', 'Rmc::index');
    $routes->get('rmc-edit', 'Rmc::edit');
    $routes->post('rmc-save', 'Rmc::save');

    // Api routes
    $routes->get('api', 'Api::index');
    
});

$routes->group('api', ['namespace' => 'App\Controllers\api'], function ($routes) {
    $routes->post('login', 'Auth::login');
});

// private api routes
$routes->group('api', ['namespace' => 'App\Controllers\api', 'filter' => ['authFilter']], function ($routes) {

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
