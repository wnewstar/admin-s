<?php

namespace App\Back\Controller\Admin;

use App\Back\Model\Admin\User as Bean;
use App\Back\Controller\BaseController as Base;

/**
 * 用户管理
 */
class UserController extends Base
{

    /**
     * @desc   详情
     * @name   detailAction
     * @date   2018-12-12
     * @author wnewstar
     * @return json
     */
    public function detailAction()
    {
        $request = $this->request;

        $id = $request->getPost('id');
        $bean = Bean::findFirstById((int)$id);

        $code = empty($bean) ? 201 : 0;
        empty($code) ?: $bean->password = null;
        $text = empty($code) ? '查询成功' : '查询失败';
        
        return print(json_encode(['code' => $code, 'text' => $text, 'data' => $bean]));
    }

    /**
     * @desc   新增
     * @name   insertAction
     * @date   2018-12-12
     * @author wnewstar
     * @return json
     */
    public function insertAction()
    {
        $request = $this->request;
        
        $name = $request->getPost('username');
        if (empty($name)) {
            $code = 101;
            $text = '参数错误';
        } elseif (Bean::findFirstByBeanname($name)) {
            $code = 102;
            $text = '数据存在';
        } else {
            $password = $request->getPost('password');
            $password = $this->security->hash($password);

            $bean = new Bean();
            $bean->username = $name;
            $bean->password = $password;
            $bean->time_create = time();
            $bean->user_create = intval($this->user->id);

            $code = empty($bean->save()) ? 202 : 0;
            $text = empty($code) ? '新增成功' : '新增失败';
        }

        return print(json_encode(['code' => $code, 'text' => $text, 'data' => $bean]));
    }

    /**
     * @desc   修改
     * @name   updateAction
     * @date   2018-12-12
     * @author wnewstar
     * @return json
     */
    public function updateAction()
    {
        $request = $this->request;

        $id = $request->getPost('id');

        $bean = Bean::findFirstById((int)$id);
        if (empty($bean)) {
            $code = 101;
            $text = '参数错误';
        } else {
            $name = $request->getPost('username');
            $temp = Acl::findFirstByName($name);
            if (!empty($temp) && $temp->id != $bean->id) {
                $code = 102;
                $text = '名称存在';
            } else {
                $password = $request->getPost('password');
                $password = $this->security->hash($password);

                $bean->username = $name;
                $bean->password = $password;
                $bean->time_update = time();
                $bean->user_update = intval($this->user->id);


                $code = empty($bean->update()) ? 203 : 0;
                $text = empty($code) ? '修改失败' : '修改成功';
            }
        }

        return print(json_encode(['code' => $code, 'text' => $text, 'data' => $bean]));
    }

	/**
     * @desc   删除
     * @name   deleteAction
     * @date   2018-12-12
     * @author wnewstar
     * @return json
     */
    public function deleteAction()
    {
        $request = $this->request;

        $id = $request->getPost('id');
        $bean = Bean::findFirstById((int)$id);
        if (empty($bean)) {
            $code = 101;
            $text = '参数错误';
        } else {
            $bean->status = 1;
            $bean->time_delete = time();
            $bean->user_delete = intval($this->user->id);

            $code = empty($bean->update()) ? 204 : 0;
            $text = empty($code) ? '删除成功' : '删除失败';
        }

        return print(json_encode(['code' => $code, 'text' => $text, 'data' => $bean]));
    }

    /**
     * @desc   恢复
     * @name   regainAction
     * @date   2018-12-12
     * @author wnewstar
     * @return json
     */
    public function regainAction()
    {
        $request = $this->request;

        $id = $request->getPost('id');
        $bean = Bean::findFirstById((int)$id);
        if (empty($bean)) {
            $code = 101;
            $text = '参数错误';
        } else {
            $bean->status = 0;
            $bean->time_delete = time();
            $bean->user_delete = intval($this->user->id);

            $code = empty($bean->update()) ? 205 : 0;
            $text = empty($code) ? '恢复成功' : '恢复失败';
        }

        return print(json_encode(['code' => $code, 'text' => $text, 'data' => $bean]));
    }

    /**
     * @desc   查询
     * @name   detailAction
     * @date   2018-12-12
     * @author wnewstar
     * @return json
     */
    public function searchAction()
    {
        $request = $this->request;
        $manager = $this->modelsManager;
        $builder = $manager->createBuilder()->from(Bean::class);

        if (!empty($name = $request->getPost('username'))) {
            $builder->where(
                'username LIKE :name:', 
                ['name' => "{$name}%"]
            );
        }

        $field = ['total' => 'COUNT(id)'];
        $total = $builder->columns($field)->getQuery()->getSingleResult()['total'];
        if ($total > 0) {
            $s = $request->getPost('size', 'int', 20);
            $p = $request->getPost('page', 'int', 1);
            $o = $s * ($p - 1);
            $field = ['*'];
            $result = $builder->limit($s, $o)->columns($field)->orderBy('id')->getQuery()->execute();
        }

        return print(json_encode(['code' => 0, 'text' => null, 'data' => ['total' => $total, 'items' => empty($total) ? [] : $result->toArray()]]));
    }
}
