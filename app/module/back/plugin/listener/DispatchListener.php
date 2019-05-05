<?php

namespace App\Back\Plugin\Listener;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

use App\Back\Model\Syslog\Api as Apilog;

class DispatchListener Extends Plugin
{

    public function beforeDispatchLoop(Event $event, Dispatcher $dispatcher)
    {
        $code = 0;
        $common = $this->common;
        $request = $this->request;

        if ($request->get('_url') != $common->backloginurl) {
            $token = $request->getHeader($common->headtoken);
            if (empty($token)) {
                $code = 101;
            } else {
                $data = $this->crypt->decryptBase64($token, $common->secrect);
                $data = explode('|', $data);
                $data[1] < time() ? $code = 102 : ($this->user->id = $data[0]);
            }
        }

        $jump = ['namespace' => 'App\Back\Controller', 'controller' => 'auth'];

        $data = [
            'url' => $request->get('_url'),
            'param' => json_encode($_GET),
            'time_create' => time(),
            'user_create' => (int)$this->user->id
        ];

        $code == 0 ?: $dispatcher->forward($jump + ['action' => 'error', 'params' => ['code' => $code]]);

        $this->getDI()->get('dblog')->insert((new Apilog)->getSource(), array_values($data), array_keys($data));
    }
}
