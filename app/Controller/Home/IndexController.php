<?php

namespace app\Controller\Home;
use app\Controller;
class IndexController extends BaseController
{
    public function index() {
    	dumper(array('data'=> array('a' =>123)));
    	// $this->twig->render('index',array('data'=> array('a' =>123)));
    	// $this->json(self::CODE_SUCCESS, 'ok', array('test'=>'1'));
    	
    }



}