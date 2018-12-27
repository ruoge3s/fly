<?php

namespace app\command;


use core\Command;

/**
 * Class Kill
 * @describe 关闭任务
 * @package app\command
 */
class Kill extends Command
{
    /**
     * 反相匹配
     * @var array
     */
    public $invertMatch = [
        'grep'
    ];

    /**
     * 正相匹配
     * @var array
     */
    public $match = [
        'bin/fly',
    ];

    /**
     * @describe 弄死消费者
     */
    public function consumer()
    {
        $this->match[] = 'consumerName';
        foreach ($this->pids() as $pid) {
            $this->do($pid);
        }
    }

    /**
     * @describe 弄死生产者
     */
    public function producer()
    {

    }


    protected function do(int $pid)
    {
        exec(sprintf('kill -9 %d', $pid));
    }

    /**
     * 抓取pid
     * @return array
     */
    protected function pids()
    {
        $buffer = [];

        $handle= popen($this->command(), 'r');
        while(!feof($handle)) {
            $pid = trim(fgets($handle));
            $pid && $buffer[] = $pid;
        }
        pclose($handle);
        return $buffer;
    }

    protected function command()
    {
        if (!$this->match) {
            exit("The match is Empty!!!\n");
        }

        $m = $this->grep($this->match, false);
        $im = $this->grep($this->invertMatch, true);

        return sprintf("ps -ef $m $im | awk '{print $2}'", $im);
    }

    /**
     * @param array $keys
     * @param bool $v
     * @return string
     */
    protected function grep(array $keys, bool $v)
    {
        $g = 'grep' . ($v ? ' -v' : '');
        $im = array_reduce($keys, function ($origin, $item) use ($g) {
            return sprintf('%s| %s %s ', $origin, $g, $item);
        });
        return trim($im);
    }
}
