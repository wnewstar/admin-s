<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Mvc\Application as BaseApplication;

class Application extends BaseApplication
{

    public function init()
    {
        $this->initConfig();
        $this->initModule();

        return $this->useImplicitView(0);
    }

    protected function initConfig()
    {
        $di = new FactoryDefault();

        $this->setDI($di);

        $di->setShared('common', require_once(CONFIG_PATH . '/common.php'));
        $di->setShared('ormset', require_once(CONFIG_PATH . '/ormset.php'));
        $di->setShared('dbconf', require_once(CONFIG_PATH . '/dbconf.php'));
        $di->setShared('router', require_once(CONFIG_PATH . '/router.php'));
    }

    protected function initModule()
    {
        $this->registerModules([
            'back' => ['className' => 'App\Back\Module', 'path' => MODULE_PATH . '/back/Module.php'],
            'fore' => ['className' => 'App\Fore\Module', 'path' => MODULE_PATH . '/fore/Module.php']
        ]);
    }
}