<?php

namespace Core;

abstract class Model extends Base{
    protected $tableName = "";
    public function __construct($tableName ='') {
        $this->tableName = $tableName;
        //此方法可初始化控制器
        if (method_exists($this, '_init')) {
            $this->_init();
        }

        if(property_exists($this, 'db')) {
            $this->db = DB::singleton();
        }
    }
}