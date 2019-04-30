<?php

namespace App\Back\Controller;

use App\Back\Model\Admin\User;
use App\Back\Model\Asset\Bookmark;
use App\Back\Model\Syslog\Api;
use App\Back\Model\Syslog\Mysql;
use App\Back\Controller\BaseController;

class IndexController extends BaseController
{

    public function indexAction()
    {
        return print('Hello World');
    }

    public function getDataAction()
    {

        $data = [];
        $field = ['total' => 'COUNT(id)'];
        $queryBuilder = $this->modelsManager->createBuilder();

        $data['api'] = $queryBuilder
            ->from(Api::class)
            ->columns($field)->getQuery()->getSingleResult()['total'];
        $data['user'] = $queryBuilder
            ->from(User::class)
            ->columns($field)->getQuery()->getSingleResult()['total'];
        $data['mysql'] = $queryBuilder
            ->from(User::class)
            ->columns($field)->getQuery()->getSingleResult()['total'];
        $data['bookmark'] = $queryBuilder
            ->from(Bookmark::class)
            ->columns($field)->getQuery()->getSingleResult()['total'];

        return print(json_encode(['code' => 0, 'text' => '查询成功', 'data' => $data]));
    }
}
