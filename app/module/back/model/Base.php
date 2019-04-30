<?php

namespace App\Back\Model;

use Phalcon\Mvc\Model;

class Base extends Model
{

    public $id;
    public $status;
    public $time_delete;
    public $time_create;
    public $time_update;
    public $user_delete;
    public $user_create;
    public $user_update;

    public function getSource()
    {
        
        return $this->getDI()['ormset'][static::class];
    }

    public function initialize()
    {

        return $this->setSchema($this->getDI()['common']['dbschema']);
    }
}
