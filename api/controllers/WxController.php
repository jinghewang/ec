<?php
namespace api\controllers;

use api\services\WxService;
use common\helpers\BDataHelper;
use common\helpers\BNetHelper;
use yii\web\Controller;
use Yii;

class WxController extends WxBaseController
{
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

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        $this->wxService = new WxService();
        parent::init();
    }


    /**
     * 团列表
     */
    public function  actionIndex(){
        $this->layout = 'main_sign';
        $this->wxService->doGetAll();
        //---
        $properties = $this->wxService->getInitArray();
        return $this->render('index', $properties);
    }

    /**
     * 团列表
     */
    public function  actionLoc(){
        $this->layout = 'main_sign';
        $properties = $this->wxService->getInitArray();
        return $this->render('location', $properties);
    }


    public function actionGetAll(){
        $this->wxService->doGetAll();
    }


    public function actionCode(){
        $redirect_url = urlencode("http://www.btgerp.com/wx/codeNext");
        $scope = "snsapi_userinfo";
        $state = '123';
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&redirect_uri={$redirect_url}&response_type=code&scope={$scope}&state=123#wechat_redirect";
        echo $url;die;
        $this->setData('code',array('redirect_url' => $redirect_url,
            'debug-url' => $url,
            'debug-scope' => $scope,
            'debug-state' => $state));
        Yii::$app->request->redirect($url);
    }


    /**
     * 团列表
     */
    public function  actionCodeNext(){
        //BDataHelper::print_r($_REQUEST);
        $this->setData('access-token-request',$_REQUEST);

        $properties = array();
        $code = $_REQUEST['code'];
        $state = $_REQUEST['state'];
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->secret}&code={$code}&grant_type=authorization_code";
        $result = BNetHelper::curl_post($url,array(),'string');
        $data = json_decode($result,true);
        $data['debug-url'] = $url;
        $this->setData('access-token',$data);

        BDataHelper::print_r($this->getData());
        //echo $result;
    }

    /**
     * 团列表
     */
    public function  actionCodeRefresh(){
        BDataHelper::print_r($_REQUEST);

        $properties = array();
        $code = $_REQUEST['code'];
        $state = $_REQUEST['state'];
        $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid={$this->appid}&grant_type=refresh_token&refresh_token={$this->getData('access-token','refresh_token')}";
        $result = BNetHelper::curl_post($url,array(),'string');
        Yii::$app->session['access_token'] = json_decode($result,true);
        echo $result;

        //$this->render('index', $properties);
    }


    /**
     * 获取微信服务器IP地址
     */
    public function actionIp(){
        //初始化团数据
        $token = $this->getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token={$token}";
        $result = BNetHelper::curl_get($url,array(),'string');
        echo $result;
    }

    /**
     * 获取用户列表
     */
    public function actionUser(){
        //初始化团数据
        $opentid = '';
        $token = $this->getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token={$token}&next_openid={$opentid}";
        $result = BNetHelper::curl_get($url,array(),'string');
        echo $result;
    }

    /**
     * 获取用户列表2(包含用户信息)
     */
    public function actionUser2(){
        //初始化团数据
        $opentid = '';
        $token = $this->getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token={$token}&next_openid={$opentid}";
        $result = BNetHelper::curl_get($url,array(),'string');
        $result = json_decode($result);

        //batch user info
        $user_list = array();
        if (isset($result) && isset($result->data)){
            foreach ($result->data->openid as $opentid) {
                $user_list["user_list"][] = array("openid" => $opentid, "lang" => "zh-CN");
            }
        }
        $user_list = json_encode($user_list);
        $url = "https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token={$token}";
        $result = BNetHelper::curl_post($url, $user_list, 'string');
        echo $result;
    }

    /**
     * 获取用户基本信息
     */
    public function actionUserInfo(){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$this->getData('access-token','access_token')}&openid={$this->getData('access-token','openid')}&lang=zh_CN";
        $result = BNetHelper::curl_get($url, array(), 'string');
        $data = json_decode($result, true);
        $data['debug-url'] = $url;
        Yii::$app->session['user-info'] = $data;
        BDataHelper::print_r($this->getData());
        //echo $result;
    }

    /**
     * 获取用户基本信息
     */
    public function actionUserRemark($openid=null){
        if (empty($openid))
            $openid = $this->openid;

        $token = $this->getToken();
        $data = array('openid' => $openid, 'remark' => 'wjh');
        $url = "https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token={$token}";
        $data = json_encode($data);
        $result = BNetHelper::curl_post($url,$data,'string');
        echo $result;
    }


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
