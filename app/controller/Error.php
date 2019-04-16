<?php
namespace app\controller;

use core\Controller;
use core\traits\Message;

/**
 * Class Error
 * @package app\controller
 */
class Error extends Controller
{
    use Message;

    /**
     * 找不到
     * @return array
     */
    public function notFound()
    {
        return self::error(400, 'Can not found...');
    }
}