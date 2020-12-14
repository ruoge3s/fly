<?php

declare(strict_types=1);

namespace app\command;

use core\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Lunar extends Command
{
    /**
     * 下拉韩国农历
     * @throws GuzzleException
     */
    public function pull()
    {
        $client = new Client();


        $years = range(1900, 2037, 1);
        $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

        foreach ($years as $year) {
            echo '[开始]' . date('Y-m-d H:i:s') . "\n";
            foreach ($months as $month) {
                $start = time();
                echo "{$year}{$month} ";
                $date = "{$year}{$month}";
                $response = $client->get("https://m.search.naver.com/p/csearch/content/qapirender.nhn?where=nexearch&key=CalendarAnniversary&pkid=134&q={$date}%EC%9B%94");
                $res = $response->getBody()->getContents();

                $res = json_decode($res, true);
                $res = json_encode($res, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

                file_put_contents("calendar/{$date}.json", $res);
                echo '消耗 ' . (time() - $start) . "s\n";
//                sleep(1);
            }
            echo '[结束]' . date('Y-m-d H:i:s') . "\n";
        }
    }

    public function combine()
    {
        $years = range(1900, 2037, 1);
        $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

        $file = fopen("calendar.txt", "w+");

        if (is_dir('calendar') || mkdir('calendar', 0655))

        foreach ($years as $year) {
            foreach ($months as $month) {
                fwrite($file, "#{$year}{$month}\n");
                $filename = "calendar/{$year}{$month}.json";
                if (is_file($filename)) {
                    $content = file_get_contents($filename);

                    $mCalendars = json_decode($content, true)['openCalendar']['daysList'];
                    foreach ($mCalendars as $mCalendar) {
                        $data = [
                            $mCalendar['solarDate'],
                            $mCalendar['lunarDate'],
                            $mCalendar['dayOfWeek'],
                            $mCalendar['leapMonth'],
                            $mCalendar['solarWeek'],
                            $mCalendar['dayOff'],
                            $mCalendar['thisMonth'],
                            $mCalendar['anniversaryList'],
                        ];
                        fwrite($file, json_encode($data, JSON_UNESCAPED_UNICODE) . "\n");
                    }
                }
                fwrite($file, "#end\n");
            }
        }
    }
}


