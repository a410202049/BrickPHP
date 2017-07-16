<?php

namespace app\Model;
use Core\Model;
class User extends Model
{
    protected $db;
    function getUserInfo($parameter,$isId = false){
        $data = $this->db
            ->insert('bp_user',array("username"=>"999999999"));
//        echo $sql = $this->db->getLastSql();
        return $data;
//        if($isId){
//            $where = array('u.id'=>$parameter);
//        }else{
//            $where = array('u.username'=>$parameter);
//        }
//        $sideTable = array(
//            array(
//                'table'=>'user_group',
//                'alias'=>'g',
//                'condition'=>'u.group_id = g.id',
//                'type'=>'left'
//            )
//        );
//        $userData = $this->join("u.*,g.id as gid,g.name as group_name,g.status as group_status,g.rules as group_rules,g.create_time as group_createtime,g.description as group_description,","user as u",$sideTable,true ,$where);
//        return $userData;
    }
}