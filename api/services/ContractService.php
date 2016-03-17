<?php
namespace api\services;

use common\helpers\BDataHelper;
use Yii;
use api\models\Contract;
use api\models\ContractVersion;
use api\models\Traveller;
use api\models\Group;
use common\helpers\DataHelper;
use common\helpers\Pdf_Watermark;
use yii\base\Exception;
use yii\base\Security;
use yii\helpers\VarDumper;
use yii\web\Request;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2015/7/21
 * Time: 23:05
 */
class ContractService
{

    /**
     * 生成合同号
     * @param bool $isAutomatic 是否自动生成
     * @return string
     */
    static function generateNumber ($isAutomatic=true,$user,$type,$oldcontr=''){

        $arr_number=[];
        //生成号
        if($isAutomatic){
            $arr_number[]=Contract::CONTRNO_SYSTEM;
        }else{
            $arr_number[]=Contract::CONTRNO_APP;
        }

        //经营许可证号简写
        $license=str_replace('-','',$user->org->license2);
        $arr_number[]=$license;

        if($isAutomatic){
            $arr_number[]=strtoupper($type);//类型
            $arr_number[]=DataHelper::getCurrentDate('Ymd');//年月日
            $arr_number[]=self::getRandom();//5位流水号
        }else{
            $arr_number[]=$oldcontr;
        }

        return implode('',$arr_number);
    }

    private static function getRandom(){
        return rand(10000,99999);
    }

    /**
     * 获取合同的pdf文件路径
     * @param $ecno
     */
    public static function getPdfPath($ecno){
        $ec = Contract::find()->where(['contr_no'=>$ecno])->one();
        if(empty($ec)){
            throw new Exception('获取文件路径失败，原因：合同不存在。');
        }

        if(empty($ec->path)){
            $date=BDataHelper::getCurrentDate('Y-m');
            $dir=$_SERVER['DOCUMENT_ROOT'].'/pdf/'.$date;
            $dir=BDataHelper::format_path($dir);
            if(!file_exists($dir)){
                mkdir($dir);
            }
            $path =$dir.'/'.$ecno.'.pdf';
            return BDataHelper::format_path($path);
        }else{
            $dir=dirname($ec->path);
            if(!file_exists($dir)){
                mkdir($dir);
            }
            return $ec->path;
        }
    }

    /**
     * 获取生成pdf合同地址
     * @param $ecno
     * @return string
     */
    public static function getHtmlForPdf($ecno){
        return  Yii::$app->request->hostInfo."/contract-version/html?ecno={$ecno}";
    }

    /**
     * 生成合同
     * @param $ecno
     * @param $generate
     */
    public static function generate_pdf($ecno,$generate=true){
        $ec = Contract::find()->where(['contr_no'=>$ecno])->one();
        if(empty($ec)){
            throw new Exception('合同不存在');
        }

        $html=self::getHtmlForPdf($ecno);
        $fileName =self::getPdfPath($ecno);
        BDataHelper::generate_pdf($html,$fileName,true,true,$generate);
    }

    /**
     * 发送合同邮件
     * @param $email
     * @param $ecno
     */
    public static function send_email($email,$ecno){
        $ec = Contract::find()->where(['contr_no'=>$ecno])->one();
        $assigned = Traveller::find()->andWhere('contr_id=:contr_id and is_leader=:is_leader', [':contr_id' => $ec->contr_id, ':is_leader' => '1'])->one();
        $group = Group::find()->andWhere('contr_id=:contr_id', [':contr_id' => $ec->contr_id])->one();
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($email);
        $mail->setSubject("旅游电子合同签名成功");
        $mail->setHtmlBody("<div>
        <p>尊敬的 {$assigned->name} ：</p>
        <p>您出团日期为 {$group->bgndate}的合同【{$ec->contr_no}】 已经上传至旅游局备案并完成签字，请确认附件中PDF合同内容。</p>
        <p>您可以下载合同附件查阅合同备案情况 。祝您旅途愉快！[北京汇来科技有限公司]</p>
        </div>");

        $file=self::getPdfPath($ecno);
        if(file_exists($file)){
            $mail->attach($file,array('fileName'=>"{$ecno}.pdf",));
        }
        $mail->send();
    }

    /**
     * 已签字保存合同文件及文件签名
     * @param $ecno
     */
    public static function save_file_info($ecno){
        $ec = Contract::find()->where(['contr_no'=>$ecno])->one();
        $file=self::getPdfPath($ecno);
        if(!file_exists($file)){
            throw new Exception('保存文件信息失败，原因：合同文件不存在。');
        }
        if($ec->status==Contract::CONTRACT_STATUS_SIGNED){
            $ec->path=$file;
            $ec->filesign=sha1_file($file);
            $ec->save();
        }
    }

    /**
     * 添加水印
     * @param $filename
     * @param string $text
     * @return bool
     */
    public static function setWater($filename,$text='Huilai Technology'){
        $mpdf = new Pdf_Watermark();
        return $mpdf->pdf_set_watermark_text($filename, $filename, $text,50,150);
    }
}