<?php

namespace App\Back\Plugin\Listener;

use App\Back\Model\Syslog\Mysql as Mysqllog;

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Db\Adapter\Pdo\Mysql as Connection;

class DbListener Extends Plugin
{

    private $dbconfig;

    public function beforeQuery($event, $connection)
    {
        $dbconfig = (array)$connection->getDescriptor();
        $bind = $connection->getSQLVariables();
        $text = $connection->getSQLStatement();
        $data = [
            'host' => $dbconfig['host'],
            'port' => $dbconfig['port'],
            'text' => $text,
            'bind' => json_encode($bind),
            'time_create' => time(),
            'user_create' => (int)$this->user->id
        ];

        $this->getDI()->get('dblog')->insert((new Mysqllog)->getSource(), array_values($data), array_keys($data));
    }
}
