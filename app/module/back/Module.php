<?php

namespace App\Back;

use App\Back\Widget\Tree;
use App\Back\Model\Admin\User;
use App\Back\Plugin\Listener\DbListener;
use App\Back\Plugin\Listener\DispatchListener;
use Phalcon\Loader;
use Phalcon\DiInterface;
use Phalcon\Db\Profiler;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface as BaseModule;

class Module implements BaseModule
{

    private $di = null;
    
    public function initListener()
    {
        $manager = $this->di->get('eventsManager');

        $manager->attach('db', new DbListener());
        $manager->attach('dispatch', new DispatchListener());
    }

    public function initDatabase()
    {
        $di = $this->di;

        $di->setShared('db', function () use ($di) {
            $db = new Mysql($di['dbconf']['db']->toArray());
            $db->setEventsManager($di->get('eventsManager'));
            
            return $db;
        });

        $di->setShared('dblog', function () use ($di) {
            $db = new Mysql($di['dbconf']['log']->toArray());
            
            return $db;
        });

        $di->setShared('dbadmin', function () use ($di) {
            $db = new Mysql($di['dbconf']['admin']->toArray());
            $db->setEventsManager($di->get('eventsManager'));
            
            return $db;
        });

        $di->setShared('dbwrite', function () use ($di) {
            $db = new Mysql($di['dbconf']['write']->toArray());
            $db->setEventsManager($di->get('eventsManager'));
            
            return $db;
        });

        $di->setShared('dbquery', function () use ($di) {
            $db = new Mysql($di['dbconf']['query']->toArray());
            $db->setEventsManager($di->get('eventsManager'));
            
            return $db;
        });
    }

    public function registerServices(DiInterface $di = null)
    {
        $this->di = $di;

        $this->initListener();
        $this->initDatabase();

        $di->setShared('user', function () {

            return new User();
        });

        $di->setShared('tree', function () {

            return new Tree();
        });

        $di->setShared('profiler', function () use ($di) {

            return new Profiler();
        });

        $di->setShared('dispatcher', function () use ($di) {
            $dispatcher = new MvcDispatcher();
            $dispatcher->setEventsManager($di->get('eventsManager'));

            return $dispatcher;
        });
    }

    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();
        $loader->register();
        $loader->registerNamespaces(
            [
                'App\Back\Widget' => MODULE_PATH . '/back/widget',

                'App\Back\Plugin' => MODULE_PATH . '/back/plugin',
                'App\Back\Plugin\Access' => MODULE_PATH . '/back/plugin/access',
                'App\Back\Plugin\Listener' => MODULE_PATH . '/back/plugin/listener',
                
                'App\Back\Model' => MODULE_PATH . '/back/model',
                'App\Back\Model\Admin' => MODULE_PATH . '/back/model/admin',
                'App\Back\Model\Asset' => MODULE_PATH . '/back/model/asset',
                'App\Back\Model\Syslog' => MODULE_PATH . '/back/model/syslog',
                
                'App\Back\Controller' => MODULE_PATH . '/back/controller/',
                'App\Back\Controller\Admin' => MODULE_PATH . '/back/controller/admin',
                'App\Back\Controller\Asset' => MODULE_PATH . '/back/controller/asset',
                'App\Back\Controller\Syslog' => MODULE_PATH . '/back/controller/syslog'
            ]
        );
    }
}
