<?php

// Start session
session_start();

// Use namespaces
use Controllers\DashboardController;
use General\General;
use Model\Code;
use Router\Router;
use Router\RouterException;

// Require
require_once "General.php";
require_once "Router/Router.php";
require_once "Router/Route.php";
require_once "Router/RouterException.php";
require_once "Controllers/Core/Controller.php";
require_once "vendor/autoload.php";

// General class
$general = new General();

// Set router URL
$router = new Router($general->getUrl());

/**
 * Index route
 * @GET route
 */
$router->get('/', function () {});


/**
 * Login routes
 * @GET route
 */
$router->get('/login', function () {});
$router->post('/login', function () {});
$router->get('/loginDiscord', function () {});
$router->get('/loginGoogle', function () {});
$router->get('/loginFacebook', function () {});

/**
 * Register route
 * @GET route
 */
$router->get('/register', function () {});
$router->post('/register', function () {});

/**
 * Dashboard route
 * @GET & @POST route
 */
$router->get('/dashboard', function () {});
$router->get('/dashboard/:code', function ($code) {
    require_once 'Controllers/DashboardController.php';
    new DashboardController('dashboard', $code);
});
$router->post('/dashboard', function () {});

/**
 * Charts route
 * @POST route
 */
$router->post('/charts', function () {});

/**
 * Logout route
 * @GET route
 */
$router->get('/logout', function () {
    if (isset($_SESSION)) {
        // Destroy session
        unset($_SESSION['user']);
        // Delete cookies
        setcookie('login', null);
        setcookie('pass_hache', null);
        setcookie('remember_key', null, null, '/');
    }
    // Redirect
    header('Location: ./');
});

/**
 * Code route
 * @GET route
 */
$router->get('/:code', function ($code) {
    require_once 'Models/Code.php';
    new Code($code);
});


// Run router
try {
    $router->run();
} catch (RouterException $e) {
    echo $e->getMessage();
}