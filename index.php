<?php
/**
 * Entry point for the FlightPHP project, bundled with Swagger OpenAPI documentation generator.
 */

require_once __DIR__."/vendor/autoload.php";
require_once __DIR__."/docs/swagger.php";

use \Firebase\JWT\JWT;

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
foreach (glob(__DIR__."/app/adapters/*.php") as $adapter) {
    require_once $adapter;
}
/**
 * Register the required classes
 */

Flight::before("start", function(&$params, &$output) {
    /* authorize for all routes containing the word 'private' */
    if (strpos(Flight::request()->url, "private") !== false) {
        $jwt = getallheaders()["Authorization"];
        try {
            $decoded_token = (array)JWT::decode($jwt, AUTH_SECRET, array("HS256"));
            $decoded_token["data"] = (array)$decoded_token["data"];
            Flight::set("id", $decoded_token["data"]["user_id"]);
        } catch (Exception $e) {
            Flight::clear("id");
            Flight::halt(401, Flight::json(array("status" => "Authentication token missing!")));
            die;
        }
    }
});

Flight::register('rv', 'RequestValidator');
Flight::register('lv', 'LoginValidator');
Flight::register('lm', 'LoginManager');
Flight::register('tm', 'TokenManager');
Flight::register('rm', 'RegisterManager');
Flight::register('pm', 'ProviderManager');
Flight::register('rum', 'ResidentialUnitManager');
Flight::register('ta', 'TelemachAdapter');



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