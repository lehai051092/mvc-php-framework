<?php declare (strict_types = 1);

/**
 * @var FastRoute\RouteCollector $router
 */

$router->addRoute('GET', '/con-heo-con', function(){
    echo 'đi bằng hai chân';
});

$router->addRoute('GET', '/con-chim-non', 'App\Controllers\TestController@bird');