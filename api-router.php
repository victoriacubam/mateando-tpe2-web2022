<?php
require_once 'libs/Router.php';
require_once 'app/controllers/product-api.controller.php';

//Se instancia el router
$router = new Router();

//Se define la tabla de ruteo
$router->addRoute('products', 'GET', 'ProductApiController', 'getProducts');
$router->addRoute('products/:ID', 'GET', 'ProductApiController', 'getProduct');
$router->addRoute('products', 'POST', 'ProductApiController', 'insertProduct');
$router->addRoute('products/:ID', 'DELETE', 'ProductApiController', 'deleteProduct');
$router->addRoute('products/:ID', 'PUT', 'ProductApiController', 'editProduct');
$router->setDefaultRoute('ProductApiController', 'error');



//rutea
$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);