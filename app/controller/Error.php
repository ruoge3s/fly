<?php
namespace app\controller;

use core\Controller;
use core\traits\Message;

class Error extends Controller
{
    use Message;

    public function notFound()
    {
        return self::error(400, '找不到...');
    }
}