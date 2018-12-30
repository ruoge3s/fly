<?php

namespace app\controller;

use core\Controller;

class Index extends Controller
{
    public function Index()
    {
        return '欢迎使用Fly框架';
    }

    public function hello()
    {
        return 'hello';
    }
}
