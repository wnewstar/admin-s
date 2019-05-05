<?php

namespace App\Back\Controller;

use Phalcon\Crypt;
use App\Back\Model\Admin\User;
use App\Back\Controller\BaseController;

class AuthController extends BaseController
{

    /**
     * @desc   错误
     * @date   2018-12-12
     * @author wnewstar
     * @return json
     */
    public function errorAction()
    {
        $code = $this->dispatcher->getParam('code');

        return print(json_encode(['code' => $code, 'text' => '系统错误']));
    }

    /**
     * @desc   登录
     * @name   loginAction
     * @date   2018-12-12
     * @author wnewstar
     * @return json
     */
    public function loginAction()
    {
        $request = $this->request;
        $security = $this->security;

        $code = 0;
        $data = [];
        $user = User::findFirstByUsername($request->getPost('username'));
        if (empty($user)) {
            $code = 101;
            $text = '无效用户';
        } else {
            $password = $request->getPost('password');
            if (empty($security->checkHash($password, $user->password))) {
                $code = 102;
                $text = '密码错误';
            } else {
                $text = '验证通过';
                $data['user'] = [
                    'uname' => $user->username,
                    'ctime' => $user->time_create
                ];

                $etime = time() + 3600;
                $encrypt = "{$user->id}|{$etime}";
                $secrect = $this->common->secrect;
                $data['auth'] = [
                    'etime' => time() + 3600,
                    'token' => $this->crypt->encryptBase64($encrypt, $secrect)
                ];
            }
        }

        return print(json_encode(['code' => $code, 'text' => $text, 'data' => $data]));
    }

    /**
     * @desc   退出
     * @name   logoutAction
     * @date   2018-12-12
     * @author wnewstar
     * @return json
     */
    public function logoutAction()
    {
        $code = 0;
        $data = [];
        $text = '退出成功';

        return print(json_encode(['code' => $code, 'text' => $text, 'data' => $data]));
    }
}
