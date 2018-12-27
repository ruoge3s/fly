<?php

namespace core;



class Command
{
    public $home = 'app/command';

    public function __construct(array $config)
    {
        App::configure($this, $config);
    }

    /**
     * 执行方法
     * @param $m
     */
    public function execute($m)
    {
        if (method_exists($this, $m)) {
            if ($m == 'help') {
                $this->help($this->publicProperties(), "Property:\n");
                $this->help($this->publicMethods(), "Method:\n");
            } else {
                $this->$m();
            }
        } else {
            $this->help($this->publicMethods(), "Method:\n");
        }
    }

    public function load(array $attributes = [])
    {
        $properties = $this->publicProperties();
        foreach ($attributes as $attribute => $value) {
            if (isset($properties[$attribute])) {
                $this->$attribute = $value;
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * 命令提示
     * @time 2018-12-22 12:23:57
     */
    public function cmdTips()
    {
        $this->help($this->apps());
    }

    /**
     * @describe 获取帮助
     * @param array $helps
     * @param string $title
     */
    public function help(array $helps, $title="helps:\n")
    {
        $info = $title;
        foreach ($helps as $key => $data) {
            $describe = isset($data['describe']) ? $data['describe'] : [''];
            $start = str_repeat(' ', 4) . $key . " ";
            $info .= $start . $describe[0] . "\n";

            array_shift($describe);
            foreach ($describe as $text) {
                $info .= str_repeat(" ", strlen($start)) . $text . "\n";
            }

        }

        echo $info;
    }

    /**
     * 解析注释
     * @param $text
     * @return array
     * @time 2018-12-22 11:11:50
     */
    protected function parseComment($text)
    {
        $res = preg_replace(['/^\/\*{2}/', '/\*{1}/', '/\/$/'], '', $text);
        $lines = explode("\n", $res);

        $key = 'default';
        $comments = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }
            if ($line[0] == '@') {
                $lineKV = explode(' ', $line);
                $key = substr($lineKV[0], 1);
                $line = ltrim($line, $lineKV[0] . " ");
            }
            $comments[$key] = isset($comments[$key]) ? array_merge($comments[$key], [$line]): [$line];
        }

        return $comments;
    }

    /**
     * 获取App类
     * @return array
     * @time 2018-12-22 12:18:56
     */
    public function apps()
    {
        $apps = [];
        $list = scandir(BASE_DIR . $this->home);
        foreach ($list as $filename) {
            if (preg_match('/(^[A-Z]\w+)(\.php)$/', $filename, $matches)) {
                $className = dir2namespace($this->home) . '\\' . $matches[1];
                if (class_exists($className)) {
                    try {
                        $r = new \ReflectionClass($className);
                        if ($r->getParentClass() and $r->getParentClass()->name == self::class) {
                            $apps[$matches[1]] = $this->parseComment($r->getDocComment());
                        } else {
                            echo sprintf("Class $className need to extend from %s\n", self::class);
                            exit();
                        }
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                        exit();
                    }
                }
            }
        }
        return $apps;
    }

    /**
     * 获取public方法
     * @return array
     * @time 2018-12-22 10:20:53
     */
    public function publicMethods()
    {
        $staticClassMethods =  array_diff(
            get_class_methods(static::class),
            get_class_methods(self::class)
        );

        $staticClassMethods[] = 'help';

        $publicMethods = [];

        foreach ($staticClassMethods as $method) {
            try {
                $methodObj = new \ReflectionMethod(static::class, $method);
            } catch (\Exception $e) {
                echo $e->getMessage() . "\n";
                exit();
            }

            if ($methodObj->isPublic()) {
                $publicMethods[$method] = array_merge(
                    $this->parseComment($methodObj->getDocComment()),
                    ['']
                );
            }
        }

        return $publicMethods;
    }

    /**
     * 获取public属性
     * @return array
     */
    public function publicProperties()
    {
        $publicProperties = [];
        try {
            $rc = new \ReflectionClass(static::class);
            $properties = $rc->getProperties();
            foreach ($properties as $property) {
                if ($property->class != self::class) {
                    $publicProperties[$property->name] = $this->parseComment($property->getDocComment());
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            exit();
        }
        return $publicProperties;
    }
}