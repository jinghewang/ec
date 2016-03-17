<?php



require_once 'vendor/autoload.php';

// 默认的 xunsearch 应用配置文件目录为 vendor/hightman/xunsearch/app
// 如有必要，请通过常量 XS_APP_ROOT 定义
define ('XS_APP_ROOT', '/vendor/hightman/xunsearch/app');

// 创建 XS 对象，关于项目配置文件请参见官网
$xs = new \XS('demo');





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
        $rand = rand(1, 10);
        sleep($rand);
        printf("thread id:%s \tname:%s \trand:%s \n",Thread::getCurrentThreadId(),$this->args['name'],$rand);
    }

}



