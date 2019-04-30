<?php

use Phalcon\Config;

return new Config([
    'App\Back\Model\Admin\Acl' => 'admin_acl',
    'App\Back\Model\Admin\User' => 'admin_user',
    'App\Back\Model\Admin\Role' => 'admin_role',
    'App\Back\Model\Asset\Bookmark' => 'asset_bookmark',
    'App\Back\Model\Syslog\Api' => 'syslog_api',
    'App\Back\Model\Syslog\Mysql' => 'syslog_mysql',
    'App\Back\Model\Syslog\Mongo' => 'syslog_mongo',
    'App\Back\Model\Syslog\Postgresql' => 'syslog_postgresql'
]);
