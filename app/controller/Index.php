<?php

namespace app\controller;

use core\Controller;

class Index extends Controller
{
    public function Index()
    {
        return [1, 2, 3];
    }

    public function hello()
    {
        return 'hello';
    }
}
