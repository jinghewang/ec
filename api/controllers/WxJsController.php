<?php
namespace api\controllers;

use api\services\WxJsService;
use api\services\WxService;
use common\helpers\BDataHelper;
use common\helpers\BNetHelper;
use yii\web\Controller;
use Yii;

class WxJsController extends WxBaseController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='main_wx_js';
    const DATA_KEY = '_data';

    private $wxService = null;

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        $this->wxService = new WxJsService();
        parent::init();
    }


    /**
     * Index
     */
    public function  actionIndex(){
        $this->wxService->doGetAll();
        //---
        $properties = $this->wxService->getInitArray();
        return $this->render('index', $properties);
    }

    /**
     * 定位
     */
    public function  actionLoc(){
        $this->wxService->doGetAll();
        $properties = $this->wxService->getInitArray();
        return $this->render('location', $properties);
    }

    /**
     * 百度地图
     */
    public function  actionBaidu(){
        $this->wxService->doGetAll();
        $properties = $this->wxService->getInitArray();
        return $this->render('baidu', $properties);
    }

    /**
     * 扫描
     */
    public function  actionScan(){
        $this->wxService->doGetAll();
        $properties = $this->wxService->getInitArray();
        return $this->render('scan', $properties);
    }

    /**
     * Test
     */
    public function  actionTest(){
        $this->wxService->doGetAll();
        $properties = $this->wxService->getInitArray();
        return $this->render('test', $properties);
    }

    /**
     * Debug
     */
    public function  actionDebug(){
        $this->wxService->doGetAll();
        $properties = $this->wxService->getInitArray();
        return $this->render('debug', $properties);
    }


    public function actionGetAll(){
        $this->wxService->doGetAll();
    }


    public function actionCode(){
        $redirect_url = urlencode("http://a.btgerp.com/wx-js/index?id=2&name=wjh");
        $scope = 'snsapi_base';//"snsapi_userinfo";
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

}
