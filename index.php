<?php
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use App\Controllers\UserController;

$app = AppFactory::create();

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/api/users', [UserController::class, 'getUsers']);

$app->get('/', function ($request, $response, $args) {
    $response->getBody()->write(file_get_contents('templates/home.php'));
    return $response;
});

$app->run();
?>