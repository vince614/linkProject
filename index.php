<?php

// Start session
session_start();

// Use namespaces
use General\General;
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


// Run router
try {
    $router->run();
} catch (RouterException $e) {
    echo $e->getMessage();
}


//Variable
$isLogin = false;

// includes
include './includes/config.php';

//Si l'utilisateur est connecté
if(isset($_SESSION['username'])) {
    if (!empty($_SESSION['username'])) {

      //Var connection
      $username = $_SESSION['username'];
      $isLogin = true;

    }
}