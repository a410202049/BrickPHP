<?php
/**
 * User: 火蜥蜴制作
 * Date: 2016/8/27
 * Time: 8:19
 */

namespace Core;

// 模板类
class Template
{
    private $data = [];
    private $path = ''; // 模板路径

    public function __construct() {
        $this->path = Config::get('app.template_path');
    }

    /**
     * 模板赋值
     * @param $key
     * @param $value
     */
    public function assign($key, $value) {
        if(is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
    }

    /**
     * 获取路径
     * @param $file
     */
    private function getFilePath($file) {
        $params = explode('.', $file);
        // 模板路径已经加了分隔符
        $path = ROOT . DIRECTORY_SEPARATOR . $this->path;
        foreach ($params as $key => $param) {
            if($key == count($params) - 1) {
                $path .= $param;
            } else {
                $path .= $param . DIRECTORY_SEPARATOR;
            }
        }
        return $path . '.html';
    }


    public function display($file) {
        if(empty($file)) {
            throw new \Exception("Template Can Not Be Empty");
        }
        $realPath = $this->getFilePath($file);
        if(is_file($realPath)) {
            extract($this->data);
            require($realPath);
        } else {
            throw new \Exception("Template:<code>{$realPath}</code> Not Found");
        }
    }
}