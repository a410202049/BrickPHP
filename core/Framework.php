<?php

namespace Core;
use Maer\Router\Router;
class Framework {
    private static $_startTime = 0;
    private static $_memoryStart = 0;
    private static $_error;

    public static function init() {
        require_once (__DIR__ . '/functions.php');
        // 自动加载设置
        spl_autoload_register('self::loadClass');
        ini_set('date.timezone', Config::get('app.timezone'));
        $router = new Router();

        // 加载路由设置
        require_once (APP . '/routes.php');
        $router->notFound(function() {
            echo "Ops! The page was not found!";
        });

        $router->methodNotAllowed(function() {
            echo "Ops! Method not allowed!";
        });

        //--------设置错误级别, 记录程序开始时间及内存--------//
        if (DEBUG) {
            ini_set('display_errors', 'On');
            error_reporting(E_ALL ^ E_NOTICE);
            self::$_startTime = microtime(true);
            self::$_memoryStart = memory_get_usage(true);
        }

        //--------运行结束执行--------//
        register_shutdown_function(array(
            'Core\Framework',
            'end'
        ));

        //--------自定义错误处理--------//
        set_error_handler(array(
            'Core\Framework',
            'errorHandler'
        ));

        //--------处理未捕捉的异常--------//
        set_exception_handler(array(
            'Core\Framework',
            'exceptionHandler'
        ));

        //--------session设置--------//
        self::_initSession();


        if (!IS_CLI) {
        //--------处理请求数据--------//
            Request::deal();
        }

        $params = array();
        $log = array(
            'request_id' => REQUEST_ID,
            'uri' => $_SERVER['REQUEST_URI'],
            'method' => Request::method(),
            'params' => array_merge($params, Request::gets(), Request::posts()),
            'stream' => Request::stream(),
            'cookie' => Request::cookies(),
            'ip' => Request::ip(),
        );
        Log::info($log);

        $router->dispatch();
    }

    public static function loadClass($className) {
        // linux上路径
        $className = str_replace("\\", "/", $className);
        $filename = ROOT . "/" . $className . ".php";
        if(is_file($filename))
            require_once($filename);
        else
            throw new \Exception("$filename Is Not Found");
    }


    public static function errorHandler($errno, $errstr, $errfile, $errline) {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    public static function exceptionHandler($e) {
        self::$_error = $e;
    }

    public static function end() {
        if (self::$_error) {
            $e = self::$_error;
            self::$_error = null;
            self::_halt($e);
        }
        //输出日志
        $log = array(
            'request_id' => REQUEST_ID,
            'run_type' => IS_CLI ? 'cli' : 'web',
            'run_time' => number_format((microtime(true) - self::$_startTime) * 1000, 0) . 'ms',
            'run_memory' => number_format((memory_get_usage(true) - self::$_memoryStart) / (1024), 0, ",", ".") . 'kb'
        );
        if (!IS_CLI) {
            $log['response'] = Response::getResponseData();
            $log['response_type'] = Response::getResponseType();
        }
        Log::info($log);
    }



    private static function _halt($e) {
        if (DEBUG) {
            if (IS_CLI) {
                exit(iconv('UTF-8', 'gbk', $e->getMessage()) . PHP_EOL . 'FILE: ' . $e->getFile() . '(' . $e->getLine() . ')' . PHP_EOL . $e->getTraceAsString() . PHP_EOL);
            }
            include_once CORE . DS . 'tpl' . DS . 'excetion.html';
        } else {
            $logError['url'] = $_SERVER['REQUEST_URI'];
            $logError['errmsg'] = $e->getMessage();
            $logError['file'] = $e->getFile();
            $logError['line'] = $e->getLine();
            Log::error($logError);
            if (IS_CLI) {
                exit();
            }
            $url = Config::get('404_page');
            if ($url) {
                Response::redirect($url);
            }
            header('HTTP/1.1 404 Not Found');
            header('Status:404 Not Found');
            include_once CORE . DS . 'tpl' . DS . '404.html';
        }
    }

    // 初始化session
    private static function _initSession() {
        $sessionConf = Config::get('session');
        if (isset($sessionConf['auto_start']) && $sessionConf['auto_start']) {
            unset($sessionConf['auto_start']);
            if (isset($sessionConf['name']) && $sessionConf['name']) {
                session_name($sessionConf['name']);
                unset($sessionConf['name']);
            }
            if (isset($sessionConf['save_path']) && $sessionConf['save_path']) {
                session_save_path($sessionConf['save_path']);
                unset($sessionConf['save_path']);
            }
            if (isset($sessionConf['cache_limiter']) && $sessionConf['cache_limiter']) {
                session_cache_limiter($sessionConf['cache_limiter']);
                unset($sessionConf['cache_limiter']);
            }
            if (isset($sessionConf['cache_expire']) && $sessionConf['cache_expire']) {
                session_cache_expire($sessionConf['cache_expire']);
                unset($sessionConf['cache_expire']);
            }
            foreach ($sessionConf as $key => $val) {
                $sessionConf[$key] && ini_set('session.' . $key, $val);
            }
            session_start();
        }
    }
}
