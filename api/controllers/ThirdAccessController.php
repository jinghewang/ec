<?php
namespace api\controllers;

use api\services;
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2015/7/22
 * Time: 10:43
 */
class ThirdAccessController extends BaseController
{


    /**
     * logout
     */
    public function actionIndex()
    {
        $sessionid = session_id();
        $cacheToken = ThirdAccessService::getAllCacheValue();
        BDataHelper::print_r($cacheToken);
    }

    /**
     * logout
     */
    public function actionReadme()
    {
        $this->layout = "/layout/outerlayout";
        $sessionid = session_id();
        $this->render("../readme");
    }

    /**
     * logout
     */
    public function actionAuthorize()
    {
        $sessionid = session_id();
        $map = $this->getRestMap();
        /**
         * @var AccessToken $token
         */
        try {

            $token = AccessTokenService::authenticate(Yii::app()->request);
            $map["appkey"] = $token->appkey;
            $map["clientid"] = $token->clientid;
            $map["tokenid"] = $token->tokenid;
            $map[AjaxStatus::PROPERTY_MESSAGES] = "授权申请成功";
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_OK;
        } catch (IllegalArgumentException $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        } catch (Exception $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        }

        echo SnapshotHelper::encodeArray($map);
    }


    /**
     * logout
     */
    public function actionAuthorizeView($appkey, $clientid)
    {
        $sessionid = session_id();
        $map = $this->getRestMap();
        /**
         * @var AccessToken $token
         */
        try {
            Assert::hasText($appkey, "appkey传递参数为空！");
            Assert::hasText($clientid, "clientid传递参数为空！");
            $token = BCacheHelper::getToken($clientid, 'model');
            Assert::hasText($token, "token 查询结果为空！");
            //data
            $map["appkey"] = $token->appkey;
            $map["clientid"] = $token->clientid;
            $map["tokenid"] = $token->tokenid;
            $map[AjaxStatus::PROPERTY_MESSAGES] = "操作成功";
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_OK;
        } catch (IllegalArgumentException $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        } catch (Exception $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        }

        echo SnapshotHelper::encodeArray($map);
    }

    /**
     * logout
     */
    public function actionValidate($tokenid, $clientid)
    {
        $map = $this->getRestMap();
        /**
         * @var AccessToken $token
         */
        try {
            Assert::hasText($tokenid, "tokenid传递参数为空！");
            Assert::hasText($clientid, "clientid传递参数为空！");

            //验证token
            $token = AccessTokenService::validateToken(Yii::app()->request);
            Assert::hasText($token, "token 查询结果为空！");
            $map["clientid"] = $token->clientid;
            $map["tokenid"] = $token->tokenid;
            $map[AjaxStatus::PROPERTY_MESSAGES] = "验证通过";
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_OK;
        } catch (IllegalArgumentException $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        } catch (Exception $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        }

        echo SnapshotHelper::encodeArray($map);
    }

    /**
     * logout
     */
    public function actionClear($tokenid, $clientid)
    {
        $map = $this->getRestMap();
        /**
         * @var AccessToken $token
         */
        try {
            Assert::hasText($tokenid, "tokenid传递参数为空！");
            Assert::hasText($clientid, "clientid传递参数为空！");

            //验证token
            $token = AccessTokenService::validateToken(Yii::app()->request);
            Assert::hasText($token, "token 查询结果为空！");
            //清理
            AccessTokenService::clearToken(Yii::app()->request);
            $map["clientid"] = $token->clientid;
            $map["tokenid"] = $token->tokenid;
            $map[AjaxStatus::PROPERTY_MESSAGES] = "清理成功";
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_OK;
        } catch (IllegalArgumentException $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        } catch (Exception $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        }

        echo SnapshotHelper::encodeArray($map);
    }
}