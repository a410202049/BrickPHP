<?php
namespace Core\libraries;
/**
 * 登录验证
 */

class LoginAuth{
    protected $db;
    /**
     * 验证用户是否处于登录状态 返回用户id
     */
    public function isLogin() {
        $auth_cookie = '';
        if(isset($_COOKIE[AUTH_COOKIE_NAME])) {
            $auth_cookie = $_COOKIE[AUTH_COOKIE_NAME];
            $user = $this->validateAuthCookie($auth_cookie);
            if($user){
                return $user['id'];
            }
        }
        return false;
    }

    /**
     * 写用于登录验证cookie
     *
     * @param int $user_id User ID
     * @param bool $remember Whether to remember the user or not
     */
    public function setAuthCookie($user_login, $ispersis = false) {
        if ($ispersis) {
            $expiration  = time() + 3600 * 24 * 30 * 12;
        } else {
            $expiration = null;
        }
        $auth_cookie_name = AUTH_COOKIE_NAME;
        $auth_cookie = $this->generateAuthCookie($user_login, $expiration);
        setcookie($auth_cookie_name, $auth_cookie, $expiration,'/');
    }

    /**
     * 生成登录验证cookie
     *
     * @param int $user_id user login
     * @param int $expiration Cookie expiration in seconds
     * @return string Authentication cookie contents
     */
    private function generateAuthCookie($user_login, $expiration) {
        $key = $this->emHash($user_login . '|' . $expiration);
        $hash = hash_hmac('md5', $user_login . '|' . $expiration, $key);

        $cookie = $user_login . '|' . $expiration . '|' . $hash;

        return $cookie;
    }

    /**
     * Get hash of given string.
     *
     * @param string $data Plain text to hash
     * @return string Hash of $data
     */
    private function emHash($data) {
        $key = AUTH_KEY;
        return hash_hmac('md5', $data, $key);
    }

    /**
     * 验证cookie
     * Validates authentication cookie.
     *
     * @param string $cookie Optional. If used, will validate contents instead of cookie's
     * @return bool|int False if invalid cookie, User ID if valid.
     */
    public function validateAuthCookie($cookie = '') {
        if (empty($cookie)) {
            return false;
        }

        $cookie_elements = explode('|', $cookie);
        if (count($cookie_elements) != 3) {
            return false;
        }

        list($username, $expiration, $hmac) = $cookie_elements;

        if (!empty($expiration) && $expiration < time()) {
            return false;
        }

        $key = $this->emHash($username . '|' . $expiration);
        $hash = hash_hmac('md5', $username . '|' . $expiration, $key);

        if ($hmac != $hash) {
            return false;
        }
        $this->CI->load->model('user_model','user');
        $user = $this->CI->user->getUserInfo($username);
        if (!$user) {
            return false;
        }
        return $user;
    }

    /**
     * 生成token，防御CSRF攻击
     */
    public function genToken() {
        $uid = $this->isLogin();
        $token_cookie_name = 'CI_TOKENCOOKIE_' . md5(substr(AUTH_KEY, 16, 32) . $uid);
        if (isset($_COOKIE[$token_cookie_name])) {
            return $_COOKIE[$token_cookie_name];
        } else {
            $token = md5(getRandStr(16));
            setcookie($token_cookie_name, $token, 0, '/');
            return $token;
        }
    }

    /**
     * 检查token，防御CSRF攻击
     */
    public function checkToken(){
        $token = isset($_REQUEST['token']) ? addslashes($_REQUEST['token']) : '';
        if ($token != $this->genToken()) {
            return false;
        }
        return true;
    }
}
