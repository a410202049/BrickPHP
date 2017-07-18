<?php
return [
    '/' => array(
        'GET',
        'app\Controller\Home',
        'IndexController',
        'index'
    ),
    'admin/login'=> array(
        'GET',
        'app\Controller\Home',
        'LoginController',
        'index'
    )
];


