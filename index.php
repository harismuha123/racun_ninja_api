<?php
/**
 * Entry point for the FlightPHP project, bundled with Swagger OpenAPI documentation generator.
 */

require_once __DIR__."/vendor/autoload.php";
require_once __DIR__."/docs/swagger.php";

/**
 * Required files, modules & libraries.
 */
require_once __DIR__."/config/Config.php";


foreach (glob(__DIR__."/app/utils/*.php") as $util) {
    require_once $util;
}
foreach (glob(__DIR__."/app/routes/*.php") as $route) {
    require_once $route;
}
foreach (glob(__DIR__."/app/models/*.php") as $model) {
    require_once $model;
}
foreach (glob(__DIR__."/app/dao/*.php") as $class) {
    require_once $class;
}
/**
 * Register the required classes
 */

Flight::register('rv', 'RequestValidator');
Flight::register('lv', 'LoginValidator');
Flight::register('lm', 'LoginManager');
Flight::register('tm', 'TokenManager');
Flight::register('rm', 'RegisterManager');


Flight::route('OPTIONS /auth/*', function() {
    Flight::json('Anyway, return something for OPTIONS requests');
});

Flight::before('json', function () {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
});
/**
 * Start the Flight framework.
 */
Flight::start();