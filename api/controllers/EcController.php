<?php
namespace api\controllers;

use api\models\AccessToken;
use api\models\Contract;
use api\models\Traveller;
use api\services\AjaxStatus;
use api\services\ContractService;
use api\services\EcService;
use common\helpers\BDataHelper;
use common\helpers\DataHelper;
use Yii;
use api\services\AccessTokenService;
use yii\base\Controller;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\filters\RateLimiter;
use yii\helpers\BaseJson;
use yii\web\BadRequestHttpException;
use yii\helpers\VarDumper;
use api\services\IllegalArgumentException;
use yii\web\Request;


/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2015/7/21
 * Time: 15:51
 */
class EcController extends BaseController
{

    public function behaviors()
    {
        return [
            'verbFilter' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'submit-ec' => ['post','get'],
                ],
            ],
            'rateLimiter' => [
                'class' => RateLimiter::className(),
            ],
        ];
    }

    /**
     * 登录
     */
    public function actionLogin($appkey='',$clientid='',$client_secret='')
    {
        $map = self::getRestMap();
        try {
            $appkey = $_REQUEST['appkey'];
            $clientid = $_REQUEST['clientid'];
            $client_secret = $_REQUEST['client_secret'];

            $token = AccessTokenService::validateAPIAuth($appkey,$clientid,$client_secret);

            $map[AjaxStatus::PROPERTY_MESSAGES] = "业务处理成功";
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_OK;
            $map[AjaxStatus::PROPERTY_DATA] = $token->attributes;
        } catch (IllegalArgumentException $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        } catch (Exception $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        }
        echo json_encode($map);
    }

    /**
     * 上传并提交合同[合同状态置为已提交]
     * @author  lvkui
     * @date 2016011
     */
    public function actionSubmitEc()
    {
        $map = self::getRestMap();
        try {

            //验证
            AccessTokenService::validateToken(Yii::$app->request);
            $token=Yii::$app->request->getQueryParam(AccessTokenService::TOKEN_PARAM_KEY);

            //验证合同数量
            $app=AccessTokenService::getCurrentApp($token);
            $limit=$app->eccount;
            if($limit<1)
                throw new Exception('合同数量不足,请联系管理员充值');

            $app->eccount = $app->eccount-1;
            if (!$app->save())
                throw new Exception('合同消减数量失败，原因：'.json_encode($app->errors));

            //ec
            $ec = new EcService();
            if($ec->isStrEmpty($_POST,'data')){
                throw new Exception('an empty string is not allowed for $data');
            }
            $data=$_POST['data'];

            $result=$ec->sys_submitContract($data,$token);
            $map[AjaxStatus::PROPERTY_MESSAGES] = "业务处理成功";
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_OK;
            $map[AjaxStatus::PROPERTY_DATA] = $result;

        } catch (Exception $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        }
        echo json_encode($map);
    }

    /**
     * 上传电子合同[未提交]
     * @author  lvkui
     * @date 2016011
     */
    public function actionUploadEc()
    {
        $map = self::getRestMap();
        try {

            //验证
            AccessTokenService::validateToken(Yii::$app->request);
            $token=Yii::$app->request->getParams(AccessTokenService::TOKEN_PARAM_KEY);
            $ec = new EcService();
            if($ec->isStrEmpty($_POST,'data')){
                throw new Exception('an empty string is not allowed for $data');
            }
            $data=$_POST['data'];
            $result=$ec->sys_submitContract($data,$token,false);


            $map[AjaxStatus::PROPERTY_MESSAGES] = "业务处理成功";
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_OK;
            $map[AjaxStatus::PROPERTY_DATA] = $result;

        } catch (IllegalArgumentException $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        } catch (Exception $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        }
        echo json_encode($map);
    }

    /**
     * @更改合同状态[未提交---已提交]
     * @author  lvkui
     * @date 2016011
     */
    public function actionUpdateStatus()
    {
        $map = self::getRestMap();
        try {

            //验证
            AccessTokenService::validateToken(Yii::$app->request);
            //验证合同数量
            $token=Yii::$app->request->getQueryParam(AccessTokenService::TOKEN_PARAM_KEY);
            $user=AccessTokenService::getCurrentUser($token);
            $limit=$user->org->eccount;
            if(!$limit){
                throw new Exception('合同数量不足,请充值');
            }

            //处理合同状态
            $ec = new EcService();
            if($ec->isStrEmpty($_POST,'data')){
                throw new Exception('an empty string is not allowed for $data');
            }
            $data=$_POST['data'];
            $ec->sys_submitStatus($data);
            $map[AjaxStatus::PROPERTY_MESSAGES] = "业务处理成功";
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_OK;
            $map[AjaxStatus::PROPERTY_DATA] = $data;

        } catch (IllegalArgumentException $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        } catch (Exception $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        }
        echo json_encode($map);
    }

    /**
     * 取消电子合同
     * @author  lvkui
     * @date 2016011
     */
    public function actionCancel()
    {
        $map = self::getRestMap();
        try {

            //验证
            AccessTokenService::validateToken(Yii::$app->request);

            //处理合同状态
            $ec = new EcService();
            if($ec->isStrEmpty($_POST,'data')){
                throw new Exception('an empty string is not allowed for $data');
            }
            $data=$_POST['data'];
            $ec->sys_cancelContract($data);

            $map[AjaxStatus::PROPERTY_MESSAGES] = "业务处理成功";
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_OK;
            $map[AjaxStatus::PROPERTY_DATA] = $data;

        } catch (IllegalArgumentException $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        } catch (Exception $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        }
        echo json_encode($map);
    }

    /**
     * 获取签名时间
     * @author  lvkui
     * @date 2016011
     */
    public function actionGetSign()
    {
        $map = self::getRestMap();
        try {

            //验证
            AccessTokenService::validateToken(Yii::$app->request);

            $ec = new EcService();
            if($ec->isStrEmpty($_POST,'data')){
                throw new Exception('an empty string is not allowed for $data');
            }
            $data=$_POST['data'];
            $resultData= $ec->sys_getSignCreate($data);

            $map[AjaxStatus::PROPERTY_MESSAGES] = "业务处理成功";
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_OK;
            $map[AjaxStatus::PROPERTY_DATA] = $resultData;

        } catch (IllegalArgumentException $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        } catch (Exception $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        }
        echo json_encode($map);
    }

    /**
     * 重发短信功能
     * @author  lvkui
     * @date 2016011
     */
    public function actionSendMes()
    {
        $map = self::getRestMap();
        try {

            //验证
            AccessTokenService::validateToken(Yii::$app->request);

            $ec = new EcService();
            if($ec->isStrEmpty($_POST,'data')){
                throw new Exception('an empty string is not allowed for $data');
            }
            $data=$_POST['data'];
            $ec->sys_getResendMsg($data);

            $map[AjaxStatus::PROPERTY_MESSAGES] = "业务处理成功";
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_OK;
            $map[AjaxStatus::PROPERTY_DATA] = [];

        } catch (IllegalArgumentException $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        } catch (Exception $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        }
        echo json_encode($map);
    }

    /**
     * 根据合同号获取合同ID
     * @author  lvkui
     * @date 2016011
     */
    public function actionGetEcid(){
        $map = self::getRestMap();
        try {

            //验证
            AccessTokenService::validateToken(Yii::$app->request);

            $ec = new EcService();
            if($ec->isStrEmpty($_POST,'data')){
                throw new Exception('an empty string is not allowed for $data');
            }
            $data=$_POST['data'];
            $resultData=$ec->sys_getContractUuid($data);

            $map[AjaxStatus::PROPERTY_MESSAGES] = "业务处理成功";
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_OK;
            $map[AjaxStatus::PROPERTY_DATA] = $resultData;

        } catch (IllegalArgumentException $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        } catch (Exception $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        }
        echo json_encode($map);
    }

    /**
     * 补充签名信息
     * @author  lvkui
     * @date 2016011
     */
    public function actionSubmitSign(){
        $map = self::getRestMap();
        try {

            //验证
            AccessTokenService::validateToken(Yii::$app->request);

            $ec = new EcService();
            if($ec->isStrEmpty($_POST,'data')){
                throw new Exception('an empty string is not allowed for $data');
            }
            $data=$_POST['data'];
            $ec->sys_submitSign($data);

            $map[AjaxStatus::PROPERTY_MESSAGES] = "业务处理成功";
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_OK;
            $map[AjaxStatus::PROPERTY_DATA] = [];

        } catch (IllegalArgumentException $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        } catch (Exception $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        }
        echo json_encode($map);
    }
}