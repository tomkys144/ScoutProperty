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


$app->post('/items/{method}', function (Request $request, Response $response) {
    $method = $request->getAttribute('method');
    $SessionController = new SessionController();
    $ItemsController = new ItemsController();

    $params = json_decode($request->getBody(), true);

    if (!is_array($params['Args']) && $params['Args'] !== null) {
        $params['Args'] = array($params['Args']);
    } elseif ($params['Args'] === null) {
        $params['Args'] = array();
    }

    if (!isset($params['Token'])) {
        $SessionController->startSession();
    } else {
        $SessionController->startSession($params['Token']);
    }

    $result = call_user_func_array(array($ItemsController, $method), $params['Args']);

    $token = $SessionController->getSessionID();

    if ($token['CODE'] !== 200) {
        $data = json_encode(array("Data" => array_merge($result['DATA'], $token['DATA']), "Token" => null));

        $response->getBody()->write($data);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($token['CODE']);
    }

    $data = json_encode(array("Data" => $result['DATA'], "Token" => $token['DATA']));

    $response->getBody()->write($data);

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($result['CODE']);
});

$app->post('/user/{method}', function (Request $request, Response $response) {
    $method = $request->getAttribute('method');
    $SessionController = new SessionController();
    $UserController = new UserController();

    $params = json_decode($request->getBody(), true);

    if (gettype($params['Args']) !== 'array' && isset($params['Args'])) {
        $params['Args'] = array($params['Args']);
    } elseif (!isset($params['Args'])) {
        $params['Args'] = array();
    }

    if (!isset($params['Token'])) {
        $SessionController->startSession();
    } else {
        $SessionController->startSession($params['Token']);
    }

    $result = call_user_func_array(array($UserController, $method), $params['Args']);

    $token = $SessionController->getSessionID();

    if ($token['CODE'] !== 200) {
        $data = json_encode(array("Data" => array_merge($result['DATA'], $token['DATA']), "Token" => null));

        $response->getBody()->write($data);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($token['CODE']);
    }

    $data = json_encode(array("Data" => $result['DATA'], "Token" => $token['DATA']));

    $response->getBody()->write($data);

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($result['CODE']);
});

$app->run();