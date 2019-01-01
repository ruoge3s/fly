<?php

define('BASE_DIR',  dirname(__DIR__, 1) . '/');

require_once BASE_DIR . 'vendor/autoload.php';

date_default_timezone_set("Asia/Shanghai");

\core\abstracts\App::$baseDir = BASE_DIR;

$info = require BASE_DIR . 'config/info.php';

\core\Config::instance()->load($info);

(new Dotenv\Dotenv(BASE_DIR))->load();

