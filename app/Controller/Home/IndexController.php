<?php

namespace app\Controller\Home;
use app\Controller;
class IndexController extends BaseController
{
    public function index() {
        echo '123';exit;
//    	dumper(array('data'=> array('a' =>123)));
    	// $this->twig->render('index',array('data'=> array('a' =>123)));
    	// $this->json(self::CODE_SUCCESS, 'ok', array('test'=>'1'));
    	
    }

    public function showUser(){
        print_r('999');
        print_r($_REQUEST);
    }



}