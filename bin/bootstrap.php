<?php

define('BASE_DIR',  dirname(__DIR__, 1) . '/');

require_once BASE_DIR . 'vendor/autoload.php';
require_once BASE_DIR. 'config/error-code.php';

date_default_timezone_set("Asia/Shanghai");

\core\abstracts\App::$baseDir = BASE_DIR;

$repository = \Dotenv\Repository\RepositoryBuilder::createWithDefaultAdapters()->make();

Dotenv\Dotenv::create($repository, BASE_DIR)->load();

$info = require BASE_DIR . 'config/app.php';
\core\Config::instance()->load($info);

