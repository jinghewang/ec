<?php
/**
 * Created by PhpStorm.
 * User: hltravel
 * Date: 16/3/14
 * Time: 下午3:54
 */

namespace api\services;


class AsyncOperation extends \Thread
{

    public function __construct($arg){
        $this->arg = $arg;
    }

    public function run(){
        if($this->arg){
            printf("Hello %s\n", $this->arg);

            $fp = fopen(__DIR__ . '/t.txt','+rw');
            fwrite($fp,$this->arg);
            fclose($fp);
        }
    }

}