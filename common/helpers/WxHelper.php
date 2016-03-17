<?php
namespace common\helpers;

/**
 * Created by PhpStorm.
 * User: robin
 * Date: 2016/1/7
 * Time: 9:08
 */

define("TOKEN", "btgerp");

class WxHelper
{

    public static function setLogin()
    {
        Yii::app()->session['login-type'] = 'wx';
        Yii::app()->session['wx-return-url'] = Yii::app()->request->url;
    }

    public static function setLogout()
    {
        Yii::app()->session['login-type'] = null;
        Yii::app()->session['wx-return-url'] = '/h5/wx';
    }

    public static function getReturnUrl()
    {
        return empty(Yii::app()->session['wx-return-url']) ? '/h5/wx' : Yii::app()->session['wx-return-url'];
    }

    public static function getDefaultUrl()
    {
        return  '/h5/wx';
    }

    public static function checkWxLogin()
    {
        if (empty(Yii::app()->session['login-type']))
            return false;
        return Yii::app()->session['login-type'] == 'wx';
    }


    public static function checkWxBrowser(){
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }

    public function valid($request)
    {
        $echoStr = $request["echostr"];

        //valid signature , option
        if($this->checkSignature($request)){
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg($request)
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
            if(!empty( $keyword ))
            {
                $msgType = "text";
                $contentStr = "Welcome to wechat world!";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }else{
                echo "Input something...";
            }

        }else {
            echo "";
            exit;
        }
    }

    private function checkSignature($request)
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $request["signature"];
        $timestamp = $request["timestamp"];
        $nonce = $request["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}