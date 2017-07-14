<?php
namespace app\Controller\Home;
use Core\Controller;
use Core\Config;
use Core\libraries\Twig;
class BaseController extends Controller
{

	protected function _init(){
	
    }

    public function __construct()
    {
        parent::__construct();
        $twigConf = Config::get('twig.twig_home');
        $this->twig = new Twig($twigConf);
    }
}