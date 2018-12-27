<?php

namespace core;

use Swoole\Http\Request;

class Http extends Handler implements Init
{
    protected $classes = [];

    protected $classMethods = [];

    /**
     * @var \Swoole\Http\Request
     */
    protected $request = null;

    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    public function init()
    {
        $controller = new Controller();
        $this->classes = $controller->classes();

        foreach ($this->classes as $cn => $classInfo) {
            $className = $cn;
            $this->classMethods[$className] = $this->publicMethodsParse(new $className);
        }
    }

    public function publicMethodsParse(Controller $class)
    {
        return array_keys($class->publicMethods());
    }

    public function run()
    {
        list($cn, $m) = $this->cm();

        $className = 'app\\controller\\' . $cn;

        if (isset($this->classes[$className])) {
            $class = new $className;
            if ($class instanceof Controller) {
                return $class->execute($m);
            }
        } else {
            return 'class not exist.';
        }
    }

    public function parse(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function cm()
    {
        if (isset($this->request->server['path_info'])) {
            $res = explode("/", trim($this->request->server['path_info'], '/'));

            if (count($res) >=2) {
                return [$res[0], $res[1]];
            }
        }

        return ['Index', 'index'];
    }
}