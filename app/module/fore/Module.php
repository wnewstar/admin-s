<?php

namespace App\Fore;

use Phalcon\Loader;
use Phalcon\DiInterface;
use Phalcon\Http\Response;
use Phalcon\Events\Manager;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    
    public function registerServices(DiInterface $di)
    {
        $di->set('view', function () {
            $view = new View();
            $view->setViewsDir(MODULE_PATH . '/fore/view');

            return $view;
        });

        $di->set('response', function () {
            $response = new Response();

            return $response;
        });

        $di->set('dispatcher', function () {
            $dispatcher = new Dispatcher();

            $eventManager = new Manager();

            $dispatcher->setEventsManager($eventManager);
            $dispatcher->setDefaultNamespace('App\Fore\Controller');

            return $dispatcher;
        });
    }

    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();
        $loader->register();
        $loader->registerNamespaces(
            [
                'App\Fore\Model' => MODULE_PATH . '/fore/model',
                'App\Fore\Controller' => MODULE_PATH . '/fore/controller'
            ]
        );
    }
}
