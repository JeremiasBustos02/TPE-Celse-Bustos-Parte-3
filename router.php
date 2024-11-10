<?php
    
    require_once 'libs/router.php';

    require_once 'app/controllers/movie.api.controller.php';
    require_once 'app/controllers/user.api.controller.php';
    require_once 'app/middlewares/jwt.auth.middleware.php';
    
    $router = new Router();
    $router->addMiddleware(new JWTAuthMiddleware());

    #                 endpoint            verbo      controller                    metodo
    $router->addRoute('movies'      ,     'GET',     'MovieApiController'     ,   'getAll');
    $router->addRoute('movies/:id'  ,     'GET',     'MovieApiController'     ,   'get'   );
    $router->addRoute('movies/:id'  ,     'DELETE',  'MovieApiController'     ,   'delete');
    $router->addRoute('movies'      ,     'POST',    'MovieApiController'     ,   'create');
    $router->addRoute('movies/:id'  ,     'PUT',     'MovieApiController'     ,   'update');

    $router->addRoute('user/token'  ,     'GET',     'UserApiController'      ,   'getToken');

    $router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);