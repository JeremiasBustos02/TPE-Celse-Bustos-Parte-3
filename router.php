<?php
    
    require_once 'libs/router.php';

    require_once 'app/controllers/movie.api.controller.php';

    $router = new Router();

    #                 endpoint            verbo      controller                    metodo
    $router->addRoute('movies'      ,     'GET',     'MovieApiController'     ,   'getAll');
    $router->addRoute('movies/:id'  ,     'GET',     'MovieApiController'     ,   'get'   );
    $router->addRoute('movies/:id'  ,     'DELETE',  'MovieApiController'     ,   'delete');
    $router->addRoute('movies'      ,     'POST',    'MovieApiController'     ,   'create');
    $router->addRoute('movies/:id'  ,     'PUT',     'MovieApiController'     ,   'update');

    $router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);