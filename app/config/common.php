<?php

use Phalcon\Config;

return new Config([
    'version' => '1.0',
    'dbschema' => 'admin',
    'secrect' => md5('star'),
    'headtoken' => 'TOKEN-ACCESS',
    'backloginurl' => '/back/auth/login'
]);
