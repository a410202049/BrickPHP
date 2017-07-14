        
<?php
/**
 * 该文件记录所有路由设置
 */
// https://github.com/magnus-eriksson/router
$router->get('/', ['app\Controller\Home\IndexController', 'index']);
$router->post('/show', ['app\Controller\Home\IndexController', 'showUser']);

$router->notFound(function() {
    echo "Ops! The page was not found!";
});

$router->methodNotAllowed(function() {
    echo "Ops! Method not allowed!";
});

// // Class method
// $r->get('/', ['Namespace\ClassName', 'methodName']);
// // or
// $r->get('/', 'Namespace\ClassName@methodName');

// // Static class method
// $r->get('/', 'Namespace\ClassName::methodName');