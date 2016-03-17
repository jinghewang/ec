<?php

namespace api\services;

use common\services\Sms;
use Yii;
use yii\base\Exception;
use yii\base\ExitException;
use yii\data\Pagination;
use yii\data\Sort;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\services;
use common\helpers\DataHelper;
use api\models\Contract;
use api\models\ContractSign;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class SmsService
{




    public function actionSms()
    {
        $gf = new GlobalFunction();
        $result = $gf->sendText('15210061902','测试下内容');
        var_dump($result);
    }

    /**
     * Deletes an existing Country model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public static function batchSend($mobiles,$msg)
    {
        //return true;

        $sms = new Sms();
        $result = $sms->batchSend($mobiles,$msg);
        if ($result && $result->BatchSendResult && $result->BatchSendResult > 0)
            return true;
        return false;
    }

    public function actionSend($m){
        $sms = new Sms();
        $result=$sms->batchSend($m,Sms::$MesTemp[Sms::MES_TEMP_SIGN]);
        var_dump_die($result);
    }

    public function actionSel(){
        try{
            $sms = new Sms();
            $result=$sms->selSum();
            var_dump_die($result);
        }catch (Exception $e){
            var_dump_die($e->getMessage());
        }
    }

    /**
     * 获取短信验证信息
     * @param $code
     * @param $name
     * @param $cno
     * @param $url
     * @param $company
     * @return mixed
     */
    public static function getMessgeCodeTemplate($code, $cno, $company)
    {
        $msg = preg_replace_callback('/\{(?<fld>.+?)\}/', function ($matches) use ($code, $cno,$company) {
            switch($matches['fld']){
                case '$code':
                    return $code;
                    break;
                case '$cno':
                    return $cno;
                    break;
                case '$company':
                    return $company;
                    break;
                default:
                    return '';
                    break;
            }
        }, DataHelper::getSmsConfig('template_code'));
        return $msg;
    }


    /**
     * 获取短信模板
     * $name,$cno,$url,$code,$company
     * @return mixed
     */
    public static  function getMessageTemplate($name,$ecid,$mobile){

        $model=Contract::findOne($ecid);
        if(empty($model)){
            throw new Exception('合同不存在');
        }

        $url="http://a.btgerp.com/contract-sign/sign?cno={$model->contr_id}";
        $company='神舟国旅';
        $cno=$model->contr_no;

        //保存验证码
        $code=self::getCode();
        $cs= new ContractSign();
        $cs->contr_id = $ecid;
        $cs->mobile=$mobile;
        $cs->code = $code;
        if(!$cs->save()){
            throw new Exception('验证码保存失败');
        }

        $template = preg_replace_callback('/\{(?<fld>.+?)\}/', function ($matches) use ($code,$name, $cno, $url, $company) {
            switch($matches['fld']){
                case '$code':
                    return $code;
                    break;
                case '$name':
                    return $name;
                    break;
                case '$cno':
                    return $cno;
                    break;
                case '$url':
                    return $url;
                    break;
                case '$company':
                    return $company;
                    break;
                default:
                    return $name;
                    break;
            }
        }, DataHelper::getSmsConfig('template'));
        return $template;
    }

    /**
     * 获取取消合同短信模板
     * @param $name 姓名
     * @param $ecid 合同ID
     * @return mixed
     */
    public static  function getMessageCancelTemplate($name,$ecid){

        $model=Contract::findOne($ecid);
        if(empty($model)){
            throw new Exception('合同不存在');
        }

        $company=$model->org->name;
        $cno=$model->contr_no;

        $template = preg_replace_callback('/\{(?<fld>.+?)\}/', function ($matches) use ($name, $cno, $company) {
            switch($matches['fld']){
                case '$name':
                    return $name;
                    break;
                case '$cno':
                    return $cno;
                    break;
                case '$company':
                    return $company;
                    break;
                default:
                    return $name;
                    break;
            }
        }, DataHelper::getSmsConfig('template_cancel'));
        return $template;
    }

    /**
     * 验证码
     * @return string
     */
    public  static  function getCode(){
        return DataHelper::randomNum(4);
    }

}
