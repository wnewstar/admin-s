<?php

namespace App\Back\Controller\Admin;

use App\Back\Model\Admin\Acl as Bean;
use App\Back\Controller\BaseController as Base;

/**
 * 资源管理
 */
class AclController extends Base
{

    /**
     * @desc   树数据
     * @name   treeAction
     * @date   2018-12-12
     * @author wnewstar
     * @return json
     */
    public function treeAction()
    {
        $tree = $this->tree;
        $tree->filter = function ($item) {
            return empty($item['leaf']);
        };

        $manager = $this->modelsManager;
        $builder = $manager->createBuilder()->from(Bean::class);

        $column = '*';
        $result = $builder->where('id > 0')
            ->columns($column)
            ->orderBy('sort')
            ->getQuery()
            ->execute();

        return print(json_encode(['code' => 0, 'text' => null, 'data' => $this->tree->getTree($result->toArray())]));
    }

    /**
     * @desc   菜单数据
     * @name   menuAction
     * @date   2018-12-12
     * @author wnewstar
     * @return json
     */
    public function menuAction()
    {
        $tree = $this->tree;
        $tree->filter = function ($item) {
            return empty($item['leaf']) && empty($item['pid']);
        };

        $manager = $this->modelsManager;
        $builder = $manager->createBuilder()->from(Bean::class);

        $column = '*';
        $result = $builder->where('menu = 1 AND status = 0')
            ->columns($column)
            ->orderBy('sort')
            ->getQuery()
            ->execute();

        return print(json_encode(['code' => 0, 'text' => null, 'data' => $this->tree->getTree($result->toArray())]));
    }

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
        $bean = Bean::findFirstById($id);

        $code = empty($bean) ? 201 : 0;
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

        $name = $request->getPost('name');
        if (empty($name)) {
            $code = 101;
            $text = '参数错误';
        } elseif (Bean::findFirstByName($name)) {
            $code = 102;
            $text = '数据存在';
        } else {
            $url = $request->getPost('url');
            $mca = $request->getPost('mca');
            $pid = $request->getPost('pid');
            $path = $request->getPost('path');
            $sort = $request->getPost('sort');
            $icon = $request->getPost('icon');
            $menu = $request->getPost('menu');

            $bean = new Bean();
            $bean->url = $url;
            $bean->mca = $mca;
            $bean->pid = $pid;
            $bean->path = $path;
            $bean->name = $name;
            $bean->sort = $sort;
            $bean->icon = $icon;
            $bean->menu = $menu;
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
            $name = $request->getPost('name');
            $temp = Bean::findFirstByName($name);
            if (!empty($temp) && $temp->id != $bean->id) {
                $code = 102;
                $text = '数据存在';
            } else {
                $url = $request->getPost('url');
                $mca = $request->getPost('mca');
                $pid = $request->getPost('pid');
                $path = $request->getPost('path');
                $sort = $request->getPost('sort');
                $icon = $request->getPost('icon');
                $menu = $request->getPost('menu');

                $bean->url = $url;
                $bean->mca = $mca;
                $bean->pid = $pid;
                $bean->path = $path;
                $bean->name = $name;
                $bean->sort = $sort;
                $bean->icon = $icon;
                $bean->menu = $menu;
                $bean->time_update = time();
                $bean->user_update = intval($this->user->id);

                $code = empty($bean->update()) ? 203 : 0;
                $text = empty($code) ? '修改成功' : '修改失败';
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
     * @name   searchAction
     * @date   2018-12-12
     * @author wnewstar
     * @return json
     */
    public function searchAction()
    {
        $request = $this->request;
        $manager = $this->modelsManager;
        $builder = $manager->createBuilder()->from(Bean::class);

        if (!empty($name = $request->getPost('name'))) {
            $builder->where(
                'name LIKE :name:', 
                ['name' => "%{$name}%"]
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
