<?php

use App\Controllers\ItemsController;
use App\Controllers\SessionController;
use App\Controllers\UserController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../bootstrap.php';

$app = AppFactory::create();

$app->addRoutingMiddleware();

$app->addErrorMiddleware(true, true, false);

$app->get('/', function (Request $request, Response $response, $parameters) {
    $response->getBody()->write('Works');

    return $response
        ->withStatus(200);
});

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

    $params = json_decode($request->getAttribute('Args'));

    if (!is_array($params) && $params !== null) {
        $params = array($params);
    } elseif ($params === null) {
        $params = array();
    }

    $result = call_user_func_array(array($ItemsController, $method), $params);

    $data = json_encode($result['DATA']);

    $response->getBody()->write($data);

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
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

    $params = json_decode($request->getAttribute('Args'));

    if (!is_array($params) && $params !== null) {
        $params = array($params);
    } elseif ($params === null) {
        $params = array();
    }

    $result = call_user_func_array(array($UserController, $method), $params);

    $data = json_encode($result['DATA']);

    $response->getBody()->write($data);

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
});

$app->run();