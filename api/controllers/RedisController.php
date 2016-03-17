<?php
namespace api\controllers;

use api\models\ContractSign;
use api\services\WxService;
use common\helpers\BDataHelper;
use common\helpers\DataHelper;
use common\models\CodeForm;
use common\models\SignLoginForm;
use Yii;
use common\models\LoginForm;
use api\models\PasswordResetRequestForm;
use api\models\ResetPasswordForm;
use api\models\SignupForm;
use api\models\ContactForm;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class RedisController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionPhp()
    {
        phpinfo();
        die;
    }


    public function actionGet()
    {
        $redis = $this->getRedis();
        $data = $redis->get('name');
        var_dump($data);
        die;
    }

    public function actionSet()
    {
        $redis = $this->getRedis();
        $data = $redis->get('name');
        $data = $redis->set('name',$data.'12323');
        var_dump($data);
        die;
    }

    public function actionList()
    {
        $redis = $this->getRedis();
        $redis->lpush("tutorial-list", "Redis");
        $redis->lpush("tutorial-list", "Mongodb");
        $redis->lpush("tutorial-list", "Mysql");
        // Get the stored data and print it
        $arList = $redis->lrange("tutorial-list", 0 ,5);
        echo "Stored string in redis:: ";
        BDataHelper::print_r($arList);
        die;
    }


    public function actionHash()
    {
        $redis = $this->getRedis();
        $redis->hmset("tutorial-hash",'name','wjh', 'addr','bj');
        $arList = $redis->hgetall("tutorial-hash");
        //print_r($arList);
        BDataHelper::print_r($arList);
        die;
    }

    public function actionHash2()
    {
        $redis = $this->getRedis();

        $r = range(1, 10);
        foreach ($r as $item) {
            //$redis->hmset("tutorial-hash2:" . $item, ['name' => 'name' . $item, 'addr' => 'bj' . $item]);
            $redis->hmset("tutorial-hash2:".$item,'name','wjh'. $item, 'addr','bj'. $item);
        }


        $keys = $redis->keys("tutorial-hash2:*");

        $arList = [];
        foreach ($keys as $key) {
            $arList[] = $redis->hgetall($key);
        }
        BDataHelper::print_r($arList);
        die;


        BDataHelper::print_r($arList);
        die;
    }


    public function actionSort()
    {
        $key = 'today_cost';
        $redis = $this->getRedis();
        $redis->del($key);

        # 将数据一一加入到列表中
        $redis->LPUSH($key, 30);
        $redis->LPUSH($key, 1.5);
        $redis->LPUSH($key, 10);
        $redis->LPUSH($key, 8);

        # 排序
        $arList = $redis->SORT($key,'asc');
        BDataHelper::print_r($arList);

        $arList = $redis->SORT($key,'desc');
        BDataHelper::print_r($arList);
        die;
    }


    public function actionSort2()
    {
        $key = 'website';
        $redis = $this->getRedis();
        $redis->del($key);

        # 将数据一一加入到列表中
        $redis->LPUSH($key, "www.reddit.com");
        $redis->LPUSH($key, "www.slashdot.com");
        $redis->LPUSH($key, "www.infoq.com");

        # 排序
        $arList = $redis->SORT($key,'ALPHA','ASC','limit',0,10);
        BDataHelper::print_r($arList);

        # 排序
        $arList = $redis->SORT($key,'ALPHA','DESC','limit',0,10);
        BDataHelper::print_r($arList);
        die;
    }


    public function actionSort3()
    {
        $key = 'user_id';
        $redis = $this->getRedis();
        $redis->del($key);

        # 先将要使用的数据加入到数据库中

        # admin
        $redis->LPUSH($key, 1);//(integer) 1
        $redis->SET('user_name_1', 'admin');
        $redis->SET('user_level_1',9999);

        # huangz
        $redis->LPUSH($key, 2);//(integer) 2
        $redis->SET('user_name_2', 'huangz');
        $redis->SET('user_level_2', 10);

        # jack
        $redis->LPUSH($key, 59230);//(integer) 3
        $redis->SET('user_name_59230','jack');
        $redis->SET('user_level_59230', 3);

        # hacker
        $redis->LPUSH($key, 222);  //(integer) 4
        $redis->SET('user_name_222', 'hacker');
        $redis->SET('user_level_222', 9999);



        # 排序
        $arList = $redis->SORT($key,'ASC','by','user_level_*','get','#','get','user_name_*','get','user_level_*');
        BDataHelper::print_r($arList);

        $arList = $redis->SORT($key,'ASC','by','user_level_*','get','#','get','user_name_*','get','user_level_*','store','sort-result');
        BDataHelper::print_r($arList);


        //hash(未测试成功)
        BDataHelper::print_r('----------hash--------');
        $redis->del('serial');
        $redis->HMSET('serial', 1, '2313', 2, '2381', 222, '5023', 59230, '2435');
        $arList = $redis->SORT($key,'by','*->serial','get','#','get','user_name_*','get','user_level_*','get','*->serial');
        BDataHelper::print_r($arList);


        //hash2
        BDataHelper::print_r('----------hash2--------');
        $redis->hmset('user_info_1','serial','2313','addr','bj');
        $redis->hmset('user_info_2','serial','2381','addr','bj');
        $redis->hmset('user_info_222','serial','5023','addr','bj');
        $redis->hmset('user_info_59230','serial','2435','addr','bj');
        $arList = $redis->SORT($key,'by','user_info_*->serial','get','#','get','user_name_*','get','user_level_*','get','user_info_*->serial');
        BDataHelper::print_r($arList);



        die;
    }

    public function actionKeys()
    {
        $redis = $this->getRedis();
        $arList = $redis->keys("*");
        BDataHelper::print_r($arList);
        die;
    }




    public function getRedis()
    {
        return Yii::$app->redis;
    }
}
