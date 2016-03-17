<?php
/**
 * Created by PhpStorm.
 * User: hltravel
 * Date: 16/3/14
 * Time: 下午3:48
 */

namespace api\services;


class test_thread_run extends  \Thread
{

    public $url;
    public $data;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function run()
    {
        if(($url = $this->url))
        {
            $this->data = model_http_curl_get($url);
        }
    }

}

