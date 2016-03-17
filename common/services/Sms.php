<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 2015/12/31
 * Time: 15:52
 */

namespace common\services;


use common\helpers\DataHelper;
use yii\base\Exception;

class Sms
{

    public static $ResultCode = [
        ' 0' => '提交成功',
        '-1' => '账号未注册',
        '-2' => '其他错误',
        '-3' => '帐号或密码错误',
        '-5' => '余额不足，请充值',
        '-7' => '提交信息末尾未加签名，请添加中文的企业签名【 】',
        '-6' => '定时发送时间不是有效的时间格式',
        '-8' => '发送内容需在1到300字之间',
        '-9' => '发送号码为空',
        '-10' => '定时时间不能小于系统当前时间',
        '-101' => '调用接口速度太快',
    ];

    /**
     * 签字短信模板
     */
    const MES_TEMP_SIGN='sign';

    public static $MesTemp=array(
        self::MES_TEMP_SIGN=>'旅游局告知：您与北京神舟国际旅行社集团有限公司签订的出团日期为{date}的合同已经上传至第三方备案 【神舟旅游】',
    );

    private  function getInitParams(){
        return ['CorpID' => DataHelper::getSmsConfig('username'), 'Pwd' => DataHelper::getSmsConfig('password')];
    }

    private $arr = [0 => '00', '1' => '11', '3' => '33'];
    private $arr2 = [0 => '00', 1 => '11', 3 => 33];
    public function test(){
        print_r($this->arr);
        print_r($this->arr2);
    }

    public function hello()
    {
        $client = $this->getSoapClient();
        $param = array();
        $res = $client->__Call('HelloWorld', array('paramters' => $param));
        //$usr=json_decode($result); //$usr->token;
        return $res;
    }

    /**
     * @param $mobiel 手机号码
     * @param $content 短信内容
     * @param $cell 扩展号(必须是数字或为空)
     * @param $sendTime 定时发送时间(可为空) 固定14位长度字符串，比如：20060912152435代表2006年9月12日15时24分35秒，为空表示立即发送
     * @return mixed
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function batchSend($mobiel,$content,$cell='',$sendTime='')
    {
        try{

            if(empty($mobiel)){
                throw new Exception('mobile is required');
            }

            if(empty($content)){
                throw new Exception('content is required');
            }

            if(!is_array($mobiel)){
                $mobiel=preg_split("/\,|\-|\s+/",$mobiel,-1,PREG_SPLIT_NO_EMPTY);
            }

            $mobiel=array_unique($mobiel);
            $mobiel=implode(',',$mobiel);
            $client = $this->getSoapClient();
            $param = ['Mobile' => $mobiel, 'Content' => $content, 'Cell' => $cell, 'SendTime' => $sendTime];
            $param = array_merge($this->getInitParams(), $param);
            $result = $client->__Call('BatchSend', array('paramters' => $param));
            if (is_soap_fault($result))
                trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);

            return $result;
        }catch (Exception $e){
            throw $e;
        }
    }

    /**
     * 查询余额[剩余短信条数]
     * @return mixed
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function selSum(){
        try{
            $client = $this->getSoapClient();
            $result = $client->__Call('SelSum', array('paramters' => $this->getInitParams()));
            if (is_soap_fault($result))
                trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);

            return $result;
        }catch (Exception $e){
            throw $e;
        }
    }


    /**
     * 接收短信
     * 每次最多取50条，超过50条下次取，不足50条一次就返回完，同一条信息只能取一次，取走后系统自动更改短信标志为【已取】
     *
     * /
     * @return mixed
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function receiveMes(){
        try{
            $client = $this->getSoapClient();
            $result = $client->__Call('Get', array('paramters' => $this->getInitParams()));
            if (is_soap_fault($result))
                trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);

            return $result;
        }catch (Exception $e){
            throw $e;
        }
    }

    /**
     * 获取最近1小时内屏蔽的手机号码
     * @return mixed
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function getShieldMobile(){
        try{
            $client = $this->getSoapClient();
            $result = $client->__Call('NotSend', array('paramters' => $this->getInitParams()));
            if (is_soap_fault($result))
                trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);

            return $result;
        }catch (Exception $e){
            throw $e;
        }
    }

    /**
     * getSoapClient
     * @return SoapClient
     */
    private function  getSoapClient(){
        try{
            //$client = new SoapClient($this->api_url, array('proxy_host' => "127.0.0.1",'proxy_port' => '8888','encoding'=>'utf8'));
            $client = @new \SoapClient(DataHelper::getSmsConfig('api'), array('encoding' => DataHelper::getSmsConfig('charset')));
            return $client;
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

}