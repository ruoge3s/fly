<?php

declare(strict_types=1);

namespace app\command;

use core\Command;
use core\Config;

/**
 * Class Mail
 * @package app\command
 */
class Mail extends Command
{
    public function exec()
    {
        $config = Config::instance()->get('mail');

        $transport = new \Swift_SmtpTransport($config['host'], $config['port'], $config['encry']);

        $transport->setUsername($config['username'])->setPassword($config['password']);

        $mailer = new \Swift_Mailer($transport);

        $message = (new \Swift_Message('test2'))
            ->setFrom([$config['username'] => 'TTT'])
            ->setTo(['soonio@qq.com' => '清流'])
            ->setBody(date('Y-m-d H:i:s') . '恭喜你正常收到邮件，邮件测试通过。')
        ;

        $result = $mailer->send($message);
        var_dump($result);
    }

    public function config()
    {
       print_r(Config::instance()->get('mail'));
    }
}
