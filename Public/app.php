<?php

use App\Controllers\ItemsController;
use App\Controllers\SessionController;
use App\Controllers\UserController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../bootstrap.php';

$app = AppFactory::create();

$app->post('/items/{method}', function (Request $request, Response $response) {
    $method = $request->getAttribute('method');
    $sessionID = $request->getAttribute('sessionID');
    $SessionController = new SessionController();
    $ItemsController = new ItemsController();

    if ($sessionID === false) {
        $SessionController->startSession();
    } else {
        $SessionController->startSession($sessionID);
    }

    $result = call_user_func_array(array($ItemsController, $method), $request->getAttribute('Args'));

    $data = json_encode($result['DATA']);

    $response->getBody()->write($data);

    return $response->withStatus($result['CODE']);
});

$app->post('/user/{method}', function (Request $request, Response $response) {
    $method = $request->getAttribute('method');
    $sessionID = $request->getAttribute('sessionID');
    $SessionController = new SessionController();
    $UserController = new UserController();

    if ($sessionID === false) {
        $SessionController->startSession();
    } else {
        $SessionController->startSession($sessionID);
    }

    $result = call_user_func_array(array($UserController, $method), json_decode($request->getAttribute('Args')));

    $data = json_encode($result['DATA']);

    $response->getBody()->write($data);

    return $response->withStatus($result['CODE']);
});