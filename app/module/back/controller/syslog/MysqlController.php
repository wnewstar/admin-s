<?php

namespace App\Back\Controller\Syslog;

use App\Back\Model\Syslog\Mysql;
use App\Back\Controller\BaseController;

/**
 * Mysql日志管理
 */
class MysqlController extends BaseController
{

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
        $builder = $manager->createBuilder()->from(Mysql::class);

        if (!empty($text = $request->getPost('text'))) {
            $builder->where(
                'text LIKE :text:', 
                ['text' => "%{$text}%"]
            );
        }

        $field = ['total' => 'COUNT(id)'];
        $total = $builder->columns($field)->getQuery()->getSingleResult()['total'];
        if ($total > 0) {
            $s = $request->getPost('size', 'int', 20);
            $p = $request->getPost('page', 'int', 1);
            $o = $s * ($p - 1);
            $field = ['*'];
            $result = $builder->limit($s, $o)->columns($field)->orderBy('id DESC')->getQuery()->execute();
        }

        return print(json_encode(['code' => 0, 'text' => null, 'data' => ['total' => $total, 'items' => empty($total) ? [] : $result->toArray()]]));
    }
}
