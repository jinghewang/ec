<?php
namespace api\controllers;

use api\services\WxService;
use common\helpers\BDataHelper;
use common\helpers\BNetHelper;
use yii\web\Controller;
use Yii;

class WxBaseController extends Controller
{
    public $appid =  'wxc55da2bd6f62fb48';//'wx880e464c0c03dea1';
    public $secret = '27287081b77fb3779b1bec2fbdaa952d';//'d4624c36b6795d1d99dcf0547af5443d';
    public $openid = 'omra0t11AxxIAH4jHgZvyXTO37rY';//
    public $nonce = 'btgerp';

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='main_sign';
    const DATA_KEY = '_data';

    /**
     * @var WxService $wxService
     */
    private $wxService = null;

    public function getToken(){
        return $this->getData('token','access_token');
    }

    public function getTicket(){
        return  $this->getData('ticket','ticket');
    }

    public function getSign(){
        return $this->getData('sign');
    }

    public function getTimestamp(){
        return $this->getData('timestamp');
    }

    public function getRefreshToken(){
        return $this->getData('refresh-token');
    }

    public function setData($key,$value){
        $session = Yii::$app->session;
        $data = $session[self::DATA_KEY];
        $data[$key] = $value;
        $session[self::DATA_KEY] = $data;
    }

    public function getData($name = null, $key = null)
    {
        $session = Yii::$app->session;
        if (empty($name))
            return $session[self::DATA_KEY];

        if ($name == 'timestamp' && empty($session[self::DATA_KEY]['timestamp'])){
            $this->setData('timestamp',time());
        }

        if (!empty($key)) {
            if (isset($session[self::DATA_KEY][$name]) && isset($session[self::DATA_KEY][$name][$key]))
                return $session[self::DATA_KEY][$name][$key];
            else
                return null;
        } else {
            if (isset($session[self::DATA_KEY][$name]))
                return $session[self::DATA_KEY][$name];
            else
                return null;
        }
    }

    public function clearData(){
        Yii::$app->session[self::DATA_KEY] = null;
    }



}
