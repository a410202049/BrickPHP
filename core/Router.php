<?php
namespace Core;
class Router{
    // 路由表
    private $routers = array(
        '/' => array(
            'GET',
            'app\Controller\Home',
            'IndexController',
            'index'
        ),
        'admin/login' => array(
            'GET',
            'app\Controller\Home',
            'LoginController',
            'index'
        )
    );
}