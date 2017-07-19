<?php
use \NoahBuscher\Macaw\Macaw;
/**
 * 该文件记录所有路由设置
 */
//Macaw::get('/', function() {
//    echo 'Hello world!';
//});
Macaw::get('/', 'app\Controller\Home\IndexController@index');
Macaw::get('/show', 'app\Controller\Home\IndexController@showUser');
Macaw::get('show/aa', 'app\Controller\Home\IndexController@showUser');
//$app->get('/', 'Home\IndexController:index');
////$app->respond('GET', '/posts', 'Home\IndexController:showUser');
////$app->respond('GET', '/posts/[:id]', 'Home\IndexController:showUser');
//$app->get('/posts/[:id]', 'Home\IndexController:showUser');
