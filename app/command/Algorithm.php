<?php

declare(strict_types=1);

namespace app\command;

use core\Command;
use lestore\algorithm\Answer;
use lestore\algorithm\Calculate;
use lestore\algorithm\data\Initialize;
use lestore\algorithm\Lang;
use lestore\algorithm\Question;
use lestore\algorithm\storage\Filesystem;

class Algorithm extends Command
{

    public function init()
    {
        $driver = new Filesystem(BASE_DIR . '/runtime/seer');
        Initialize::start($driver);
    }

    public function run()
    {
        $driver = new Filesystem(BASE_DIR . '/runtime/seer');
        $lang = Lang::zh();

        // 获取问题列表
        $Q = new Question($driver);
        $res = $Q->lists($lang);

        print_r($res);

        $no = (new Calculate())->getNoByThreeHandPoint("330-217", "387-188", "458-231");

        $A = new Answer($driver);
        $slug = Answer::slugSingle($lang, 14, $no);


        $res = $A->find($slug);

        print_r($res);
    }
}