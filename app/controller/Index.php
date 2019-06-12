<?php

namespace app\controller;

use core\Controller;
use core\traits\Message;

class Index extends Controller
{
    use Message;

    /**
     * @route /index
     * @return array
     */
    public function index()
    {
        return self::success('欢迎使用Fly.');
    }

    /**
     * @route /hello
     * @return array
     */
    public function hello()
    {
        return self::success('hello! Fly.');
    }

    /**
     * @route /tk
     * @return array
     */
    public function thankYou()
    {
        return self::success('Thank you! Fly.');
    }
}
