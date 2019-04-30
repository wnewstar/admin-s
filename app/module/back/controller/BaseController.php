<?php

namespace App\Back\Controller;

use Phalcon\Mvc\Controller;

class BaseController extends Controller
{

    public function beforeExecuteRoute()
    {
        if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {

            $_POST = array_merge($_POST, (array)$this->request->getJsonRawBody(true));
        }
    }
}
