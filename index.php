<?php

//--------是否开启调试模式(默认关闭)--------//
define('DEBUG', true);

//--------定义目录分隔符--------//
define('DS', DIRECTORY_SEPARATOR);
define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
define("ROOT", getcwd());
define('APP', ROOT . DS . 'app');
define('CORE', dirname(APP).DS.'core');
define('REQUEST_ID', uniqid());
// 设置时区
define('VENDOR_PATH', CORE . DS . 'vendor'); //定义composer vendor目录
define('XSS_MODE', true);//开启XSS过滤
define('ADDSLASHES_MODE', false);//不使用addslashes
//--------日志目录(默认在app/logs目录下)--------//
define('LOG_PATH', APP . DS . 'logs');

define('AUTH_KEY','%pPp!!L2A*d*!ywYO$@koIqR*W5G@kK$f90f9d5b5a1bb1d6e2289490ecf74ebb');
//cookie name
define('AUTH_COOKIE_NAME','CI_AUTHCOOKIE_8xyneKHkLxwcSafAlw8E7olw70mYhLTy');
//define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
//define("IS_POST", strtolower($_SERVER['REQUEST_METHOD']) == 'post');

//--------引入composer机制--------//
if (is_dir(VENDOR_PATH) && is_file(VENDOR_PATH . DS . 'autoload.php')) {
    require VENDOR_PATH . DS . 'autoload.php';
}

require './core/Framework.php';
use Core\Framework;
Framework::init();