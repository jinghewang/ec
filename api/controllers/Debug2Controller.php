<?php
namespace api\controllers;

use api\models\AccessApp;
use api\models\AccessAppSearch;
use api\models\ContractSign;
use api\models\Order;
use api\services\AjaxStatus;
use api\services\AsyncOperation;
use api\services\My;
use api\services\test_thread_run;
use api\services\WxService;
use common\helpers\BDataHelper;
use common\helpers\BDefind;
use common\helpers\DataHelper;
use common\models\CodeForm;
use common\models\SignLoginForm;
use PDO;
use Swift_Attachment;
use Yii;
use common\models\LoginForm;
use api\models\PasswordResetRequestForm;
use api\models\ResetPasswordForm;
use api\models\SignupForm;
use api\models\ContactForm;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\data\Pagination;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class Debug2Controller extends BaseController
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
        $searchModel = new AccessAppSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = new Pagination(['pageSize'=>2]);

        $model = new AccessApp();
        if ($model->load(Yii::$app->request->post()))
        {
            $result = $model->validate();
            if (!$result)
                var_dump_die($model->errors);

            if (!$model->save())
                var_dump_die($model->errors);
            else
                $model = new AccessApp(); //reset model
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex2()
    {
        $searchModel = new AccessAppSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = new Pagination(['pageSize'=>2]);

        $model = new AccessApp();
        if ($model->load(Yii::$app->request->post()))
        {
            if (!$model->save())
                var_dump_die($model->errors);
            //else
            //    $model = new AccessApp(); //reset model
        }

        return $this->render('index2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }



    public function actionMail()
    {

        $mail= Yii::$app->mailer->compose();
        $mail->setTo('94209358@qq.com');
        $mail->setSubject("邮件测试222");
        //$mail->setTextBody('zheshisha ');   //发布纯文字文本
        $mail->setHtmlBody("<br>问我我我我我");    //发布可以带html标签的文本

        //附件方式1:
        $file = '/Users/zz/1.txt';
        //$file = 'http://www.btgerp.com/files/LOGO%20%282%29.png';
        $mail->attach($file,array('fileName'=>'123.txt'));

        //附件方式2:
        $data = file_get_contents($file);
        //$mail->attachContent($data,array('fileName'=>'124.txt'));

        if($mail->send())
            echo "success";
        else
            echo "failse";
        die();

    }


    public function actionPhp()
    {



        $redis = Yii::$app->redis;

        //设置mset
        $array=['a'=>1,'b'=>2];
        $redis->mset($array);
        $redis->msetnx($array);    //key不存在时才写入，但一次要么全写，要么全不写。

        //读取mset
        $array_mget=['a','b'];
        $redis->mget($array_mget);

        die;

        phpinfo();
        Yii::$app->end();
        die;
    }


    public function actionTest2()
    {
        BDataHelper::print_r(self::$STATUS);
        var_dump(!1) ;
        die;
        //echo array_key_exists(0,self::$STATUS);
        echo BDefind::getValue(self::$STATUS,0);
        die;
    }

    public function actionIndexTime()
    {
        return $this->render('index-time',['response' => date('H:i:s')]);
    }

    public function actionTime()
    {
        return $this->render('time',['response' => date('H:i:s')]);
    }

    public function actionDate()
    {
        return $this->render('date', ['response' => date('Y-M-d')]);
    }

    public function actionIndexDate()
    {
        return $this->render('index-date',['response' => date('H:i:s')]);
    }

    public function actionTime2()
    {
        return $this->render('_time2',['response' => date('H:i:s')]);
    }

    public function actionDate2()
    {
        return $this->render('_date2', ['response' => date('Y-M-d H:i:s')]);
    }

    public function actionTimeDate()
    {
        return $this->render('time-date',['response' => date('H:i:s')]);
    }

    public function actionCreate()
    {
        $model = new AccessApp();
        if ($model->load(Yii::$app->request->post() && $model->save())) {
            return $this->renderPartial('create', [
                'model' => $model
            ]);
        } else {
            return $this->renderPartial('create', [
                'model' => $model,
            ]);
        }
    }


    public function actionCreate2222()
    {
        $model = new AccessApp();
        if ($model->load(Yii::$app->request->post())) {
            $map = $this->getRestMap();
            try{
                if (!$model->save())
                    throw new Exception(json_encode($model->errors));
                $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            }
            catch(Exception $e){
                $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
                $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
            }
            echo json_encode($map);
            Yii::$app->end();

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }





    public function actionPhpTest()
    {
        $a = [1,2,3];
        $b = [4,5,6];
        array_key_exists(2,$a);

    }


    const STATUS_SUCCESS = 1;
    const STATUS_FAIL = -1;
    const STATUS_DEFAULT = 0;

    static $STATUS = [
        self::STATUS_SUCCESS => '成功',
        self::STATUS_FAIL => '失败',
        self::STATUS_DEFAULT => '默认'
    ];



    const ENCODING = 'utf8';
    const name2 = 'lht';

    private $company = 'sas';

    public function actionTestPhp(){
        //$this->test_string();
        //BDataHelper::print_r($_SERVER);
        //$this->test_max();
        //$this->test_date();
        //$this->test_swap_params();
        //$this->test_reference();
        //$this->test_string_revert();
        //$this->test_cnchar_revert();
        //$this->test_str_revert2();
        //$this->test_array_sort();
        //$this->test_change_var($cd);
        //$this->test_mod();
        //$this->test_mutiline();
        //$this->test_file_expand();
        //$a = $this->test_print_char();
        //$b = $this->test_mult_refer($b);

        //$this->test_type_convert();
        //$this->test_str_ucfirst();
        //ucfirst()
        //$this->test_array_slice();
        //$this->test_str_sub();
        //$this->test_func_static();
        //$this->test_runtime_reffer();
        //
        //$arr = array('james', 'tom', 'symfony');
        //array_push($arr,'jack');
        //array_unshift($arr,'jack2');
        //print_r2($arr);
        //$this->test_type_convert);


        //echo str_repeat('a',6);

        //str_replace();
        //substr_replace();

        $arr = ['a'=>1,'b'=>2,'c'=>3,'d'=>4];
        $arr2 = [1,2,3,4];

        //var_dump(array_rand($arr2));
        //print_r2(array_rand($arr2,2));

        //var_dump(in_array(2,$arr)) ;

        //construct
        //destruct
        //serialize()
        //echo serialize($arr);

        $ip = '192.168.1.101';

        //$this->test_mysql_1();
        //$this->test_mysql_object();
        //$this->test_mysql_asso();
        //$this->test_mysql_prepare();

        //var_dump(__DIR__);
        //$this->create_dir(mkdir(__DIR__ .'/996'));
        //$this->test_file_flock();
        //$this->test_parse_url();
        //$this->test_file_dir();
        //$this->test_get_url_content();
        //CacheLock()
        //$this->test_date_time();

        //debug_print_backtrace();

        //var_dump(2323223);

        //$this->test_muti_thread($urls_array, $result_new);

    }


    public function actionTestPhp2()
    {


        $this->test_mysql_1();

        $this->test_mysql_2();

        $this->test_mysql_3();

        $this->test_mysql_4();

        die;
        $this->test_pthreads_1();


        die;

        for($i=0;$i<2;$i++){
            $pool[] = new My();
        }

        foreach($pool as $worker){
            $worker->start();
        }
        foreach($pool as $worker){
            $worker->join();
        }
    }


    function model_thread_result_get($urls_array)
    {
        foreach ($urls_array as $key => $value)
        {
            $thread_array[$key] = new test_thread_run($value["url"]);
            $thread_array[$key]->start();
        }

        foreach ($thread_array as $thread_array_key => $thread_array_value)
        {
            while($thread_array[$thread_array_key]->isRunning())
            {
                usleep(10);
            }
            if($thread_array[$thread_array_key]->join())
            {
                $variable_data[$thread_array_key] = $thread_array[$thread_array_key]->data;
            }
        }
        return $variable_data;
    }

    function model_http_curl_get($url,$userAgent="")
    {
        $userAgent = $userAgent ? $userAgent : 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2)';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }



    public function actionPhpInfo()
    {
        phpinfo();
    }


    public function check_datetime($date)
    {
        if (date('Y-m-d H:i:s',strtotime($date) == $date))
            return true;
        else
            return false;
    }

    public function create_dir($path, $mode = 0777)
    {
        if (is_dir($path)){
            echo('dir is had');
        }
        else{
            if (mkdir($path,$mode,true))
                echo 'sucessful';
            else
                echo 'error';
        }
    }

    public function get_arr($arr)
    {
        unset($arr[0]);
    }

    public function get_count()
    {
        static $count = 0;
        return $count++;
    }

    public function multiply($num)
    {
        $num = $num + 10;
    }

    public function multiply2(&$num)
    {
        $num = $num + 10;
    }

    public function test_string()
    {
        $str = 'q中22';
        var_dump(strlen($str));
        var_dump(mb_strlen($str));
        var_dump(mb_strlen($str, self::ENCODING));

        var_dump(mb_substr($str, 1, 1, self::ENCODING));
    }

    public function test_max()
    {
        $a = 3;
        $b = 7;
        $c = 9;
        echo ($a = $a > $b ? $a : $b) > ($b = $b > $c ? $b : $c) ? $a : $b;
        var_dump($a, $b);
    }

    public function test_date()
    {
        echo date('Y-m-d H:i:s', time());

        echo date('Y-m-d H:i:s', strtotime('+1 day 1 hours'));

        echo strtotime('now');
    }

    public function test_swap_params()
    {
        $a = 2;
        $b = 4;
        list($a, $b) = array($b, $a);
        BDataHelper::print_r($a, $b);
    }

    public function test_reference()
    {
        $num = 10;
        $this->multiply($num);
        var_dump($num);

        $this->multiply2($num);
        var_dump($num);
    }

    public function test_string_revert()
    {
        $str = '1234567890';
        $str = strrev($str);
        $str = chunk_split($str, 3, ',');
        $str = strrev($str);
        $str = ltrim($str, ',');
        echo $str;
    }

    public function test_cnchar_revert()
    {
        $str = 'ss中ww';

        $data = preg_split('//u', $str);
        $data = array_reverse($data);
        $data = join('', $data);

        print_r2($data);
    }

    public function test_str_revert2()
    {
        $str = 'www.baidu.com';

        $str = substr_replace($str, '', 0, 3);
        var_dump(strrev($str));
    }

    public function test_array_sort()
    {
        $a = [1, 3, 2, 6, 'a'];
        print_r2($a);
        sort($a, SORT_STRING);
        print_r2($a);

        //--
        $fruits = array("d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple");
        asort($fruits);
        print_r2($fruits);

        arsort($fruits);
        print_r2($fruits);

        ksort($fruits);
        print_r2($fruits);

        krsort($fruits);
        print_r2($fruits);
    }

    /**
     * @param $cd
     */
    public function test_change_var($cd)
    {
        error_reporting(E_ALL);

        $str = 'cd';
        $$str = 'hotdog';
        $$str .= 'ok';
        echo $cd;

        define('name', 'wjh');
        echo name;


        echo self::name2;
    }

    public function test_mod()
    {
        echo -8 % 3;
        echo 8 % -3;
        echo -8 % -3;
    }

    public function test_mutiline()
    {
        $str = <<<HELLO
DDDD
ZZZ
HELLO;

        var_dump($str);
    }

    public function test_file_expand()
    {
        echo count("abc");
        echo strlen("abc");

        $pth = pathinfo(__FILE__);
        print_r2($pth['extension']);
    }

    /**
     * @return string
     */
    public function test_print_char()
    {
        $arr = array('james', 'tom', 'symfony');

        var_dump($arr[0]);
        var_dump(implode(',', $arr));

        $a = 'abcdef';
        var_dump($a);
        var_dump($a[0]);
        var_dump($a{0});

        printf("%1.2f", 42);
        return $a;
    }

    /**
     * @param $b
     * @return string
     */
    public function test_mult_refer($b)
    {
        $a = "hello";
        $b = &$a;
        $a = '1222';
        var_dump($b);
        unset($b);
        //var_dump($b);
        $b = "world";
        echo $a;
        return $b;
    }

    public function test_type_convert()
    {
        var_dump(intval("09"));

        var_dump((int)"09");
    }

    public function test_str_ucfirst()
    {
        $str = "open_door";
        $data = explode('_', $str);
        $data = array_map("ucfirst", $data);
        print_r2(implode('', $data));
    }

    public function test_array_slice()
    {
        $arr1 = array(
            '0' => array('fid' => 1, 'tid' => 1, 'name' => 'name1'),
            '1' => array('fid' => 1, 'tid' => 2, 'name' => 'name2'),
            '2' => array('fid' => 1, 'tid' => 5, 'name' => 'name3'),
            '3' => array('fid' => 1, 'tid' => 7, 'name' => 'name4'),
            '4' => array('fid' => 3, 'tid' => 9, 'name' => 'name5'),
        );


        $data = array();
        foreach ($arr1 as $row) {
            $data[$row['fid']][] = array_slice($row, 1);
        }

        rsort($data);
        print_r2($data);
    }

    public function test_str_sub()
    {
        $text = 'gdfgfdgd59gmkblg';
        echo substr_count($text, 'g');

        $str1 = null;
        $str2 = false;

        var_dump('0' == 0);
    }

    public function test_func_static()
    {
        $count = 5;
        echo $count;
        ++$count;
        echo $this->get_count();
        echo $this->get_count();
    }

    public function test_runtime_reffer()
    {
        var_dump($this->company);


        $arr1 = $arr2 = [1, 2];
        //$this->get_arr(&$arr1);
        $this->get_arr($arr2);

        echo count($arr1);
        echo count($arr2);
    }

    public function test_type_convert2()
    {
        print('aa is \'aa\'' . "<br/>");

        $a = '123';
        var_dump((int)$a);
        var_dump(intval($a));
        settype($a, 'int');
        var_dump($a);
    }

    private $host = '127.0.0.1';
    private $user = 'root';
    private $password = 'root';
    private $db = 'ec';


    public function test_mysql_object()
    {
        $mysqli = mysqli_connect('127.0.0.1', 'root', 'root');
        $mysqli->select_db('ec');
        $mysqli->character_set_name();
        $result = $mysqli->query('select * from user');
        while ($row = $result->fetch_object()) {
            print_r2($row);
            var_dump($row->username);
        }

        echo $mysqli->affected_rows;
        $result->free();
        $mysqli->close();
    }

    public function test_mysql_asso()
    {
        $mysqli = mysqli_connect('127.0.0.1', 'root', 'root');
        if ($mysqli->connect_error)
            die('Error : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);

        $mysqli->select_db('ec');
        $mysqli->character_set_name();
        $result = $mysqli->query('select * from user');
        while ($row = $result->fetch_assoc()) {
            print_r2($row);

        }

        echo $mysqli->affected_rows;
        $result->free();
        $mysqli->close();
    }

    public function test_mysql_prepare()
    {
        $mysqli = mysqli_connect('127.0.0.1', 'root', 'root');
        if ($mysqli->connect_error)
            die('Error : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);

        $id = '';
        $name = 'admin';
        $mysqli->select_db('ec');
        $mysqli->character_set_name();
        $query = 'select id,username from user where username=?';
        $statement = $mysqli->prepare($query);
        $statement->bind_param('s', $name);
        $statement->execute();
        $statement->bind_result($id, $name);

        while ($row = $statement->fetch()) {
            print_r2($id, $name);
        }

        echo $mysqli->affected_rows;
        $mysqli->close();
    }

    public function test_file_flock()
    {
        $fp = fopen("", "w+");
        if (flock($fp, LOCK_EX)) {
            fwrite($fp, "write some thongs ");
            fflush($fp);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }

    public function test_parse_url()
    {
        $url = 'http://www.sina.com.cn/abc/de/fg.php?id=1';
        $path = parse_url($url);
        $path = $path['path'];
        $path = explode('.', $path);
        var_dump($path[1]);
    }

    public function test_file_dir()
    {
        $dir = '/Users/zz/PhpstormProjects/ec/trunk/src';
        if (is_dir($dir)) {
            $handle = opendir($dir);
            $file = readdir($handle);
            print_r2($file);
            closedir($handle);
        }


        //内容情况

        //心理情况

        //大家好

        $data = pathinfo(__FILE__);
        print_r2($data);
    }

    public function test_get_url_content()
    {
        $url = 'http://www.baidu.com';

        //method 1
        //$content = file_get_contents($url);

        //method2
        $handle = fopen($url, 'rb');
        $content = stream_get_contents($handle);
        fclose($handle);

        echo $content;
    }

    public function test_date_time()
    {
        var_dump($this->check_datetime('2013-8-9 20:12:56'));
        var_dump($this->check_datetime('2013-8-32 20:12:56'));

        date_default_timezone_set('RPC');
    }

    /**
     * @param $urls_array
     * @param $result_new
     */
    public function test_muti_thread($urls_array, $result_new)
    {
        for ($i = 0; $i < 10; $i++) {
            $urls_array[] = array("name" => "baidu", "url" => "http://www.baidu.com/s wd=" . mt_rand(10000, 20000));
        }

        $t = microtime(true);
        $result = $this->model_thread_result_get($urls_array);
        $e = microtime(true);
        echo "多线程：" . ($e - $t) . "\n";

        $t = microtime(true);
        foreach ($urls_array as $key => $value) {
            $result_new[$key] = $this->model_http_curl_get($value["url"]);
        }
        $e = microtime(true);
        echo "For循环：" . ($e - $t) . "\n";
    }

    public function test_pthreads_1()
    {
        $thread = new AsyncOperation("World");
        $status = $thread->start();
        if ($status)
            $status2 = $thread->join();

        var_dump($status,$status2);
    }

    public function test_mysql_1()
    {
        error_reporting(E_ALL & ~E_DEPRECATED);

        mysql_connect($this->host, $this->user, $this->password);  //请依次填写数据库服务器地址,用户名,密码参数  本地数据库一般是例子中的默认值
        mysql_select_db($this->db);    //选择数据库
        mysql_query("SET NAMES UTF8");  //执行编码utf8,一般情况

        $sql = "SELECT * FROM foo";  //假如存在此数据表，且存在数据
        $res = mysql_query($sql); //执行查询操作
        while ($line = mysql_fetch_array($res))  //取出结果集
        {
            print_r2($line);
            //var_dump($line);
        }

        mysql_close();
    }

    public function test_mysql_2()
    {
        $mysqli = mysqli_connect($this->host, $this->user, $this->password);
        $mysqli->select_db($this->db);
        $mysqli->character_set_name();
        $result = $mysqli->query('select * from foo');
        while ($row = $result->fetch_array()) {
            print_r2($row);
        }

        echo $mysqli->affected_rows;
        $result->free();
        $mysqli->close();
    }

    public function test_mysql_3()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->db}";
        $db = new PDO($dsn, $this->user, $this->password);
        $db->exec('set names utf8');
        //$count = $db->exec("INSERT INTO foo SET name ='heiyeluren',gender='男',time=NOW()");
        //echo $count;

        foreach ($db->query("SELECT * FROM foo") as $row) {
            print_r2($row);
        }

        $db = null;
    }

    public function test_mysql_4()
    {

        //error_reporting(E_ALL & ~E_DEPRECATED);

        //include('adodb/adodb.inc.php');
        $driver = 'mysqli';
        $db = ADONewConnection($driver); # eg. 'mysql' or 'oci8'
        $db->debug = true;
        $db->Connect($this->host, $this->user, $this->password, $this->db);
        $db->Execute('set names utf8');
        $rs = $db->Execute('select * from foo');
        print "<pre>";
        print_r($rs->GetRows());
        print "</pre>";

    }



}
