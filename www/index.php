<?php
error_reporting(E_ALL);

header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Origin: *');
$headers = 'Content-Type,TOKEN-ACCESS';
header("Access-Control-Allow-Headers: {$headers}");

if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {

    return print(json_encode(['code' => 0, 'text' => 'success']));
} else {
    $base = realpath('..');

    define('APP_PATH', "{$base}/app");
    define('MODULE_PATH', "{$base}/app/module");
    define('CONFIG_PATH', "{$base}/app/config");

    require_once("{$base}/app/Application.php");
    
    return print((new Application())->init()->handle()->getContent());
}
