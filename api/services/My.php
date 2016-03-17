<?php
/**
 * Created by PhpStorm.
 * User: hltravel
 * Date: 16/3/14
 * Time: 下午4:05
 */

namespace api\services;


class My extends \Thread
{

    private $args = null;

    /**
     * My constructor.
     */
    public function __construct($args)
    {
        $this->args = $args;
    }


    function run(){
        for($i=1;$i<10;$i++){
            $rand = rand(1,5);
            echo Thread::getCurrentThreadId() . $this->args['name'].  "\n";
            sleep($rand);
        }
    }

}