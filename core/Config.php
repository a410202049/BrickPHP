<?php


namespace Core;

class Config {
    // All of the configuration items.
    private static $items = [];
    private static $isLoaded = false;

    /**
     * 获取配置
     * @param $key 如"database.dbname"
     * @param null $default
     * @return mixed
     */
    public static function get($key, $default = null) {
        self::loadConfigFiles();
        $params = array_filter(explode('.', $key));
        $dept = count($params);
        $config = self::$items;
        for ($i = 0; $i < $dept; $i++) {
            if(array_key_exists($params[$i], $config)) {
                $config = $config[$params[$i]];
            } else
                return $default;
        }
        return $config;
    }

    /**
     * 设置配置
     * @param $key
     * @param $value
     */
    public function set($key, $value) {
        $params = array_filter(explode('.', $key));
        $prefix = $params[0];
        $key = $params[1];
        self::$items[$prefix][$key] = $value;
    }

    /**
     * 加载所有配置文件
     */
    private static function loadConfigFiles() {
        if(!self::$isLoaded) {
            $pattern = __DIR__ . "/../config/*.php";
            $files = glob($pattern);
            foreach ($files as $file) {
                $prefix = basename($file, ".php");
                self::$items[$prefix] = require($file);
            }
            self::$isLoaded = true;
        }
    }


}