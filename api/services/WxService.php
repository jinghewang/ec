<?php
namespace api\services;

use api\models\ContractSign;
use common\helpers\BDataHelper;
use common\helpers\BNetHelper;
use common\helpers\DataHelper;
use common\helpers\NetHelper;
use common\models\CodeForm;
use Yii;

class WxService extends WxBaseService
{
    public $mainPage = '/contract/index2';
    public $loginPage = '/site/login2';

    const DATA_KEY = '_data';

    public function getInitArray()
    {
        $url = $this->getSignUrl();
        $this->doSign();

        return array(
            'appid' => $this->appid,
            'token' => $this->getToken(),
            'ticket' => $this->getTicket(),
            'sign' => $this->getSign(),
            'nonce' => $this->nonce,
            'timestamp' => $this->getTimestamp(),
            'url' => $url
        );
    }


    /**
     * @param $data
     * @return ContractSign
     */
    public function getLoginUser()
    {
        $data = $this->getData('wx-login');
        if (!$data)
            return null;

        $cs = new ContractSign();
        $cs->setAttributes($data, false);
        return $cs;
    }



    public function setLogin($data)
    {
        $this->setData('wx-login',$data);
    }

    public function setLogout()
    {
        $this->setData('wx-login',null);
    }

    public function checkLogin()
    {
        $mobile = $this->getData('wx-login','mobile');
        return !empty($mobile);
    }

    /**
     * 团列表
     */
    public function  doIndex(){
        $properties = $this->getInitArray();
        BDataHelper::print_r($_REQUEST);
        BDataHelper::print_r($properties);
        $this->render('index', $properties);
    }

    public function doIndex3(){
        //暂时没用使用
        $jssdk = new JSSDK($this->appid,$this->secret);
        $signPackage = $jssdk->GetSignPackage();
    }

    public function doGetAll(){
        $this->clearData();
        $this->doToken();
        $this->doTicket();
        $this->doSign();
    }


    /**
     * 获取access_token
     */
    public function  doToken(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->secret}";
        $result = BNetHelper::curl_get($url,array(),'string');
        $data = json_decode($result,true);
        $data['debug-url'] = $url;
        $this->setData('token',$data);
        //BDataHelper::print_r($this->getData());
    }

    /**
     * ticket
     */
    public function  doTicket(){
        $token = $this->getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$token}&type=jsapi";
        $result = BNetHelper::curl_get($url,array(),'string');
        $data = json_decode($result,true);
        $data['debug-url'] = $url;
        $this->setData('ticket',$data);
        //BDataHelper::print_r($this->getData());
    }

    public function getSignUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        return $url;
    }

    public function doSign()
    {
        $url = $this->getSignUrl();
        $tmpArr = array(
            'jsapi_ticket' => $this->getTicket(),
            'noncestr' => $this->nonce,
            'timestamp' => $this->getData('timestamp'),
            'url' => $url);
        // use SORT_STRING rule

        //sort($tmpArr, SORT_STRING);
        $str = "jsapi_ticket={$this->getTicket()}&noncestr={$this->nonce}&timestamp={$this->getData('timestamp')}&url={$url}";
        $signature = sha1($str);
        $tmpArr['sign-str'] = $str;
        $tmpArr['sign'] = $signature;
        $this->setData('sign',$tmpArr);
        //BDataHelper::print_r($this->getData());
    }

    public function doCode(){
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
     * @param CodeForm $codeForm
     */
    public function  doCodeNext($codeForm){
        //BDataHelper::print_r($_REQUEST);
        $this->setData('access-token-request',$codeForm->attributes);

        $properties = array();
        $code = $codeForm->code;
        $state = $codeForm->state;
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->secret}&code={$code}&grant_type=authorization_code";
        $result = BNetHelper::curl_get($url,array(),'string');
        $data = json_decode($result,true);
        $data['debug-url'] = $url;
        $this->setData('access-token',$data);

        //BDataHelper::print_r($this->getData());
        //echo $result;
    }

    /**
     * 团列表
     */
    public function  doCodeRefresh(){
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
    public function doIp(){
        //初始化团数据
        $token = $this->getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token={$token}";
        $result = BNetHelper::curl_get($url,array(),'string');
        echo $result;
    }

    /**
     * 获取用户列表
     */
    public function doUser(){
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
    public function doUser2(){
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
    public function doUserInfo(){
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
    public function doUserRemark($openid=null){
        if (empty($openid))
            $openid = $this->openid;

        $token = $this->getToken();
        $data = array('openid' => $openid, 'remark' => 'wjh');
        $url = "https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token={$token}";
        $data = json_encode($data);
        $result = BNetHelper::curl_post($url,$data,'string');
        echo $result;
    }

}
