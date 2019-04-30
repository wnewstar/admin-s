<?php

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Group as RouterGroup;

$router = new Router();
$router->removeExtraSlashes(true);

$router->setDefaults([
    'module' => 'fore',
    'action' => 'index',
    'controller' => 'index'
]);

$bRouter = new RouterGroup();
$assetRouter = new RouterGroup();
$adminRouter = new RouterGroup();
$syslogRouter = new RouterGroup();

$map = [
    'back' => 'App\Back\Controller',
    'asset' => 'App\Back\Controller\Asset',
    'admin' => 'App\Back\Controller\Admin',
    'syslog' => 'App\Back\Controller\Syslog'
];

$bRouter->setPrefix('/back');
$bRouter->setPaths(['module' => 'back', 'namespace' => $map['back']]);
$bRouter->add('/:controller', ['controller' => 1, 'action' => 'index']);
$bRouter->add('/:controller/:action', ['controller' => 1, 'action' => 2]);
$bRouter->add('/:controller/:action/:params', ['controller' => 1, 'action' => 2, 'params' => 3]);

$adminRouter->setPrefix('/back/admin');
$adminRouter->setPaths(['module' => 'back', 'namespace' => $map['admin']]);
$adminRouter->add('/:controller/:action', ['controller' => 1, 'action' => 2]);
$adminRouter->add('/:controller/:action/:params', ['controller' => 1, 'action' => 2, 'params' => 3]);

$assetRouter->setPrefix('/back/asset');
$assetRouter->setPaths(['module' => 'back', 'namespace' => $map['asset']]);
$assetRouter->add('/:controller/:action', ['controller' => 1, 'action' => 2]);
$assetRouter->add('/:controller/:action/:params', ['controller' => 1, 'action' => 2, 'params' => 3]);

$syslogRouter->setPrefix('/back/syslog');
$syslogRouter->setPaths(['module' => 'back', 'namespace' => $map['syslog']]);
$syslogRouter->add('/:controller/:action', ['controller' => 1, 'action' => 2]);
$syslogRouter->add('/:controller/:action/:params', ['controller' => 1, 'action' => 2, 'params' => 3]);

return $router->mount($bRouter)->mount($adminRouter)->mount($assetRouter)->mount($syslogRouter);
