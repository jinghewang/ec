<?php

namespace api\controllers;

use api\services\DebugService;
use common\helpers\BDataHelper;
use Yii;
use api\models\AccessToken;
use api\models\AccessTokenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccessTokenController implements the CRUD actions for AccessToken model.
 */
class PayBaseController extends BaseController
{

    /**
     * 功能：设置帐户有关信息及返回路径（基础配置页面）
     * 版本：联拓金融网关接口 1.0
     * 日期：2015-03-31
     * 说明：
     * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
     * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
     */

    /**
     * 请求参数
     */
    protected $VERSION           = "1.0";									//版本号
    protected $AGREEMENT_NO      = "10001089242150203";					    //签约协议号
    //protected $PARTNER_ID        = "10012387293150713";					//核心合作商户编号(测试)
    protected $PARTNER_ID         = "10041436162151028";                    //核心合作商户编号(正式)
    protected $SIGN_TYPE         = "MD5";									//签名方式
    protected $INPUT_CHARSET	   = "UTF8";								//参数编码字符集


    //protected $CA_ACCOUNT = "CA21000000006002";       //测试数据（测试）
    //protected $CA_ACCOUNT = "CA21000000012236";       //转到钱包账户（正式）
    //protected $CA_ACCOUNT = "CA21000000013069";         //转到平台对外账户（正式）
    protected $CA_ACCOUNT = "CA21000000041699";         //转到电子合同帐户（正式）

    //protected $MERCHANT_NO = "";                 //测试数据
    protected $MERCHANT_NO = "EW_B4091594220";

    /**
     * 业务参数
     */
    protected $BUSINESS_TYPE     = "4001";								//业务类别编码

    //protected $FRONT_URL         = "http://testfront.51ebill.com:65527/front/base/gateway.in";	//请求地址(测试)
    protected $FRONT_URL          = "http://front.51ebill.com/front/base/gateway.in";               //请求地址(正式)
    protected $NOTIFY_URL        = "http://a.btgerp.com/pay/callback";										//异步通知地址
    protected $RETURN_URL        = "http://a.btgerp.com/pay/result";										//返回地址

    /**
     * 用户参数
     */
    //protected $KEY               = "574C955AFF58125A86E256D332CA275F"; 	//安全校验码(测试)
    protected $KEY                  = "2ED1524E34CF6E1A6CBE34E5AE6FF190";  //安全校验码(正式)
    protected $HTTP_TIME_OUT     = 20;									//请求超时时间（单位:s）



    /**
     * 有密支付
     * $paras 公用交易参数
     * $http_arg http请求参数
     */
    public function pay_pwd($paras,$http_arg,$out_trade_no,$subject_detail=''){
        $randCode = BDataHelper::randomNum(6);
        if(empty($subject_detail))
            $subject_detail = $out_trade_no;

        //协议参数
        $paras["service"]           = "cashier_pay_pwd";		   //接口名称  *1
        $paras["version"]           = $this->VERSION;			       //版本号 *2
        $paras["partner_id"]        = $this->PARTNER_ID;			   //核心合作商户编号 *3
        $paras["sign_type"]         = $this->SIGN_TYPE;			   //签名方式 *4
        $paras["input_charset"]     = $this->INPUT_CHARSET;		   //编码方式 *5
        //$paras["agreement_no"]      = $this->AGREEMENT_NO;		   //签约协议号

        //业务参数
        $paras["out_trade_no"]       = $out_trade_no;//"1111-8888-".$randCode;	//业务订单号，唯一    $1
        //$paras["payout"]             = "0.01";						//订单总金额   $2
        $paras["merchant_no"]        = $this->MERCHANT_NO;							//付款商户编号（文档中为“不可空”，实际测试“可空”，待定）   $3
        $paras["payer_account"]      = "";							//付款帐号    $4
        $paras["payer_name"]         = "";							//付款人名称   $5
        $paras["reception_account"]  = $this->CA_ACCOUNT;   //"CA21000000006002";                          //收款帐号，联拓金融余额付款指定收款账号，可以不填，不填的情况下为联拓金融系统默认配置的收款账号  $6
        $paras["reception_name"]     = "";							//收款人名称    $7
        $paras["pay_channel"]        = "311001";					    //支付渠道     $8
        $paras["trade_type"]        = "";                             //可空  微信支付方式：     NATIVE（默认）;JSAPI（公众号支付）;微信统一支付时需要传。支付宝扫码：QR_CODE    $9
        $paras["pay_date"]           = date("Y-m-d H:i:s");			//支付日期     $10
        $paras["subject_detail"]     = $subject_detail;//"An Apple ".$randCode;					//商品描述（文档中为“可空”，实际测试“不可空”，待定）   $11
        $paras["notify_url"]         = $this->NOTIFY_URL;			//异步通知地址   必填   $12
        $paras["return_url"]         = $this->RETURN_URL;			//返回地址 必填  $13
        $paras["private_msg"]        = "";							//商户私有域   可空   $14

        //$paras["royalty_parameters"] = "";							//分账串
        //$paras["business_type"]      = $this->BUSINESS_TYPE;			//业务类别编码

        //不可空，生成签名，此参数必须放$paras其他参数的后面
        $paras["sign"]               = $this->createSign($paras,$http_arg["key"]);  // *6

        //BDataHelper::print_r($paras);
        //BDataHelper::print_r($http_arg);
        //die;
        DebugService::Log('index-post-1',$paras);
        DebugService::Log('index-post-2',$http_arg);

        $res = $this->requestAsHttpPOST($paras,$http_arg);					//发送请求
        $res2 = $this->getResultXml2Array($res);
        //BDataHelper::print_r($res2);
        DebugService::Log('index-post-result',$res2);

        return $res2;
    }

    public function getResultXml2Array($xmlString){
        $xml = simplexml_load_string($xmlString);
        //$login = (string) $xml->service;//在做数据比较时，注意要先强制转换
        return [
            'service' => (string)$xml->service,
            'partner_id' => (string)$xml->partner_id,
            'sign_type' => (string)$xml->sign_type,
            'input_charset' => (string)$xml->input_charset,
            'sign' => (string)$xml->sign,
            'version' => (string)$xml->version,
            'is_success' => (string)$xml->is_success,
            'error' => (string)$xml->error,
            'message' => (string)$xml->message,
            'cashier_url' => (string)$xml->cashier_url,
            'out_trade_no' => (string)$xml->out_trade_no,
            'pay_channel' => (string)$xml->pay_channel,
            'pay_date' => (string)$xml->pay_date,
            'payout' => (string)$xml->payout,
        ];
    }

    public function getCallbackXml2Array($xmlString){
        $xml = simplexml_load_string($xmlString);
        //$login = (string) $xml->service;//在做数据比较时，注意要先强制转换
        return [
            'service' => (string)$xml->service,
            'partner_id' => (string)$xml->partner_id,
            'sign_type' => (string)$xml->sign_type,
            'input_charset' => (string)$xml->input_charset,
            'sign' => (string)$xml->sign,
            'version' => (string)$xml->version,
            'is_success' => (string)$xml->is_success,
            'error' => (string)$xml->error,
            'message' => (string)$xml->message,
            'ebill_trade_no' => (string)$xml->ebill_trade_no,
            'out_trade_no' => (string)$xml->out_trade_no,
            'pay_channel' => (string)$xml->pay_channel,
            'pay_date' => (string)$xml->pay_date,
            'payer_account' => (string)$xml->payer_account,
            'payout' => (string)$xml->payout,
            'receive_account' => (string)$xml->receive_account,
            'third_pay_no' => (string)$xml->third_pay_no,
        ];
    }

    /**
     * 分账
     * $paras 公用交易参数
     * $http_arg http请求参数
     */
    public function royalty($paras,$http_arg) {
        /*不可空*/
        $paras["service"]          = "royalty";
        $paras["out_trade_no"]     = "102015032014345745051";		//业务订单号，唯一
        $paras["ebill_trade_no"]   = "1231100100201503260150115";	//联拓金融交易号
        $paras["royalty_batch_no"] = "RF102015032014345745051";		//分账批次号，唯一，8-30位
        $paras["royalty_details"]  = "18600000001,311001^0.02^test分账^103|18600000001^18600000002,311001^0.01^test分账^103";	//分账明细串
        /*可空*/
        $paras["private_msg"]      = "";							//商户私有域
        $paras["royalty_type"]     = "";							//分账类型
        $paras["freeze_details"]   = "";							//分账冻结串
        //不可空，生成签名，此参数必须放$paras其他参数的后面
        $paras["sign"]             = $this->createSign($paras,$http_arg["key"]);

        $res = $this->requestAsHttpPOST($paras,$http_arg);					//发送请求
    }

    /**
     * 退款
     * $paras 公用交易参数
     * $http_arg http请求参数
     */
    public function refund($paras,$http_arg) {
        global $NOTIFY_URL;

        /*不可空*/
        $paras["service"]         = "refund";						//接口名称
        $paras["notify_url"]      = $NOTIFY_URL;					//返回地址
        $paras["out_trade_no"]    = "102015032014345745051";		//业务订单号，唯一
        $paras["ebill_trade_no"]  = "1231100100201503260150115";	//联拓金融交易号
        $paras["refund_batch_no"] = "R102015032014345745054";		//退款批次号，唯一，8-28位（原文档8-30位，待定）
        // var_dump($paras["refund_batch_no"]);exit;
        $paras["refund_details"]  = "0.0^system to buyer^$18600000001^CA21000000001004^0.02^,ticketPrice,systemRoyaltyChargeFirstAccountToSystem^|18600000002^CA21000000001004^0.01^,ticketPrice,systemRoyaltyChargeSecondAccountToSystem^";				//退款明细串
        /*可空*/
        $paras["borrow_flag"]     = "";								//垫退标识
        $paras["private_msg"]     = "";								//商户私有域
        //不可空，生成签名，此参数必须放$paras其他参数的后面
        $paras["sign"]            = $this->createSign($paras,$http_arg["key"]);

        $res = $this->requestAsHttpPOST($paras,$http_arg);					//发送请求
    }

    /**
     * 生成签名
     * $paras 请求参数字符串
     * $key 密钥
     * return 生成的签名
     */
    public function createSign($paras,$key){
        $sort_array = $this->array_sort(array_filter($paras));				//删除数组中的空值并排序
        $prestr = $this->create_linkstring($sort_array);     				//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $prestr.$key;										//把拼接后的字符串再与安全校验码直接连接起来
        $mysgin = $this->sign($prestr,$paras["sign_type"]);			    //把最终的字符串签名，获得签名结果
        return $mysgin;
    }


    /**
     * 对数组排序
     * $array 排序前的数组
     * return 排序后的数组
     */
    public function array_sort($array) {
        ksort($array);												//按照key值升序排列数组
        return $array;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * $array 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    public function create_linkstring($array) {
        $str  = "";
        while (list ($key, $val) = each ($array)) {
            //键值为空的参数不参与排序，键名为key的参数不参与排序
            if($val != null && $val != "" && $key != "key" && $key != "sign_type")
                $str.=$key."=".$val."&";
        }
        $str = substr($str,0,count($str)-2);						//去掉最后一个&字符
        return $str;												//返回参数
    }

    /**
     * 签名字符串
     * $prestr 需要签名的字符串
     * $sign_type 签名类型，也就是sec_id
     * return 签名结果
     */
    public function sign($prestr,$sign_type) {
        $sign='';
        if($sign_type == 'MD5') {
            $sign = md5($prestr);									//MD5加密
        }elseif($sign_type =='DSA') {
            //DSA 签名方法待后续开发
            die("DSA 签名方法待后续开发，请先使用MD5签名方式");
        }elseif($sign_type == ""){
            die("sign_type为空，请设置sign_type");
        }else {
            die("暂不支持".$sign_type."类型的签名方式");
        }
        return strtolower($sign);									//返回参数并小写
    }

    /**
     * 发送http请求报文
     * $post_data 要提交的http报文内容
     * $http_arg http请求的参数
     */
    public function requestAsHttpPOST($array,$http_arg){
        $http_data = $this->array_sort(array_filter($array));				//删除数组中的空值并排序
        $post_data = http_build_query($http_data);
        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type:application/x-www-form-urlencoded;charset='.$http_arg["encoding"],
                'content' => $post_data,
                'timeout' => $http_arg["http_time_out"] * 1000 		//超时时间,*1000将毫秒变为秒（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($http_arg["front_url"], false, $context);
        //echo nl2br(htmlspecialchars($result))."<br><br>";			//打印返回的xml字符串
        return $result;
    }


    public function getPayNum($sum,$price=0.01){
        if (is_string($sum))
            $sum = trim($sum);

        $sum = floatval($sum);
        return $sum/$price;
    }


}
