<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();

$response = new Response();

$dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $router) {
    include '../routes/web.php';
});

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        $response->setContent('404 - Page not found');
        $response->setStatusCode(404);
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response->setContent('405 - Method not allowed');
        $response->setStatusCode(405);
        break;
    case \FastRoute\Dispatcher::FOUND:
        $response->setStatusCode(200);
        if(gettype($routeInfo[1]) === 'string'){
            $rInfo = explode('@', $routeInfo[1]);

            $className = $rInfo[0];
            $method = $rInfo[1];
            $vars = $routeInfo[2];

            $class = new $className;
            $class->$method($vars);
        } else {
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            call_user_func($handler, $vars);
        }

        break;
}

$response->prepare($request);
$response->send();

