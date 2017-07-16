<?php

namespace app\Controller\Home;
use app\Model\User;
class LoginController extends BaseController
{
    public function index() {

        echo "ss";
        $user =User::instance();
        $data = $user->getUserInfo("admin");
        print_r($data);
//        if($this->loginauth->isLogin()){
//            redirect(base_url('admin/index'));
//        }

//        $this->twig->render(
//            'Login/index',
//            array('error_flashdata'=>get_flashdata('error'))
//        );
    }


//    /**
//     * 登陆
//     */
//    public function do_login(){
//        $postData = $this->input->post();
//        $username = $postData['username'];
//        $password = $postData['password'];
//        $user = $this->user->getUserInfo($username);
//
//        if($user['password']!=do_hash($password)){
//            $this->session->set_flashdata('error','用户名或密码错误');//用户名或密码错误
//        }
//
//        if($user && !$user['status']){
//            $this->session->set_flashdata('error','该用户已经被禁用，请联系管理员');//该用户已经被禁用，请联系管理员
//        }else if($user && !$user['group_status']){
//            $this->session->set_flashdata('error','该用户组已经被禁用，请联系管理员');//该用户组已经被禁用，请联系管理员
//        }
//
//        if($this->session->flashdata('error')){
//            redirect(base_url('admin/Login'));
//        }
//
//        $this->loginauth->setAuthCookie($username,true);//当前用户设置cookie
//        $this->session->set_userdata('uid',$user['id']);
//        $this->db->set('logincount', 'logincount+1', FALSE);
//        $this->db->update('user',array('lastip'=>$this->input->ip_address(),'lasttime'=>date('Y-m-d H:i:s', time())),array('id'=>$user['id']));
//        $this->loginauth->genToken();//登录成功生成token
//        redirect(base_url('admin/Index'));
//
//    }
//
//    /**
//     * 退出登陆
//     */
//    public function logout(){
//        $this->session->sess_destroy();
//        setcookie(AUTH_COOKIE_NAME, ' ', time() - 31536000, '/');
//        redirect(base_url('admin/Login'));
//    }

}