<?php
namespace api\services;

use api\models\AccessApp;
use api\models\AccessToken;
use api\services\ThirdAccessService;
use common\helpers\BaseDataHelper;
use common\helpers\DataHelper;
use common\models\LoginForm;
use common\models\User;
use Faker\Provider\cs_CZ\DateTime;
use yii\base\Exception;
use yii\web\Request;
use Yii;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2015/7/21
 * Time: 23:05
 */
class AccessTokenService
{
    const TOKEN_PARAM_KEY = "tokenid";
    const CLIENTID_PARAM_KEY = "clientid";
    const APPKEY_PARAM_KEY = "appkey";
    const CLIENTSECURITY_PARAM_KEY = "clientsecurity";
    const APPKEY_CONFIG = "APPKEY";
    const CLIENTID_CONFIG_PREFIX = "CLIENTID_";
    const CLIENTSECURITY_CONFIG_PREFIX = "CLIENTSECURITY_";
    const USERNAME_CONFIG_PREFIX = "USERNAME_";
    const ORGID_CONFIG_PREFIX = "ORGID_";

    static $_tokens = [];

//public static List<String> APPKEYS = new ArrayList<>();

    /**
     * @return AccessToken
     */
    public function create($token, $clientsecret)
    {
        return null;
    }

    public function validate($token)
    {
        /*AccessToken cacheToken = thirdAccessService.getCacheValue(token.getClientid(), AccessToken.class);
		if (cacheToken == null){
            return false;
        }
		if (!token.getAppkey().equals(cacheToken.getAppkey())){
            return false;
        }
		if (!token.getTokenid().equals(cacheToken.getTokenid())){
            return false;
        }*/
        //@todo 判断过期
        return true;
    }

    /**
     * @param $request
     * @return AccessToken
     * @throws IllegalArgumentException
     */
    public static function authenticate($request)
    {
        $appkey = $request->getParam(self::APPKEY_PARAM_KEY);
        $clientid = $request->getParam(self::CLIENTID_PARAM_KEY);
        $clientsecurity = $request->getParam(self::CLIENTSECURITY_PARAM_KEY);
        Assert::hasText($appkey, "appkey传递参数为空！");
        Assert::hasText($clientid, "clientid传递参数为空！");
        Assert::hasText($clientsecurity, "clientsecurity传递参数为空！");
        //判断权限
        $token = self::validateAPIAuth($appkey, $clientid, $clientsecurity);
        //生成tokenid之前最好判断一下头部信息；然后把头部信息放入到AccessToken中;token.setHeader();
        $tokenId = BDataHelper::getId();
        $token->tokenid = $tokenId;
        ThirdAccessService::setCacheValue($clientid, $token);
        return $token;
    }

    /**
     * @param \Yii\web\Request $request
     * @return mixed
     * @throws IllegalArgumentException
     * @throws ValidateException
     */
    public static function validateToken($request)
    {
        /**
         * @var AccessToken $cacheToke
         */
        $clientid = $request->queryParams[self::CLIENTID_PARAM_KEY];
        $tokenid = $request->queryParams[self::TOKEN_PARAM_KEY];
        Assert::hasText($clientid, "传入参数有误");

        $app = AccessApp::findOne(array('client_id' => $clientid));
        if (empty($app)) {
            throw new Exception("不存在此APP");
        }

        $cacheToken = self::getToken($tokenid, $clientid);
        if ($cacheToken == null)
            throw new Exception("权限已过期，请重新获取权限");

        if ($cacheToken->tokenid != $tokenid)
            throw new Exception("tokenid 验证失败");

        /*$model = User::model()->findByPk(BDataHelper::getHltConfig('btg_userid'));
        $_identity = new UserIdentity($model->loginname, $model->password);
        $_identity->authenticate();
        $user = BDataHelper::getCurrentUser();
        if (empty($user)) {
            throw new ValidateException("tokenid 验证失败2");
        }*/

        //@TODO 应该判断request header中的是否匹配cacheToken对象中的
        return $cacheToken;
    }

    public static function getToken($tokenid, $clientid)
    {
        $token = AccessToken::findOne(array('clientid' => $clientid, 'tokenid' => $tokenid));
        return $token;
    }

    public static function getCurrentUser($tokenid)
    {
        try {
            /**
             * @var AccessToken $model
             */
            $model = AccessToken::findOne($tokenid);
            $user = User::findOne($model->uid);
            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $tokenid
     * @return AccessApp
     * @throws Exception
     * @throws \Exception
     */
    public static function getCurrentApp($tokenid)
    {
        try {
            /**
             * @var AccessToken $model
             */
            $model = AccessToken::findOne($tokenid);
            $app = AccessApp::findOne($model->appkey);
            return $app;
        } catch (Exception $e) {
            throw $e;
        }
    }


    public static function validateAPIAuth($appkey, $clientid, $clientsecurity)
    {
        /**
         * @var AccessToken $accessToken
         * @var AccessApp $accessApp
         */

        //app
        $accessApp = AccessApp::find()->andWhere('appkey=:appkey', array(':appkey' => $appkey))->one();
        if (empty($accessApp))
            throw new Exception('传入appkey 错误');

        if ($accessApp->client_id != $clientid)
            throw new Exception('client_id 错误');

        if ($accessApp->client_secret != $clientsecurity)
            throw new Exception('client_secret 错误');

        $accessToken = AccessToken::findOne(array('clientid' => $clientid, 'appkey' => $appkey));
        $usable = true;
        if (!empty($accessToken)) {
            $date1 = date_create(BaseDataHelper::getCurrentTime());
            $date2 = date_create($accessToken->createtime);
            $diff = date_diff($date1, $date2);
            if (($diff->format('%y') > 0) || ($diff->format('%m') > 0) || ($diff->format('%d') > 0) || ($diff->format('%h') > 0) || ($diff->format('%i') > ($accessToken->validity / 60))) {
                $accessToken->delete();
                $usable = false;
            }
        } else {
            $usable = false;
        }

        //token
        if (!$usable) {
            $accessToken = new AccessToken();
            $accessToken->tokenid = DataHelper::random(10);
            $accessToken->appkey = $appkey;
            $accessToken->clientid = $clientid;
            $accessToken->validity = 600;//60秒
            $accessToken->uid = $accessApp->uid;
            $accessToken->orgid = $accessApp->user->orgid;
            if (!$accessToken->save()) {
                var_dump($accessToken->errors);
                die;
            }
        }
        return $accessToken;
    }

    protected function loadAppkeys()
    {
        /*String appkeys = ConfigUtil.getInstance().getProperty(APPKEY_CONFIG);
        Assert.hasText(appkeys, "系统没有配置相关的appkeys");
        String[] array = appkeys.split("([ ,，]+)");
        Assert.notEmpty(array, "系统没有配置相关的appkeys");
        APPKEYS.addAll(Arrays.asList(array));*/
    }
}