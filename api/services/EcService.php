<?php
namespace api\services;
use api\models\Chargeable;
use api\models\Contract;
use api\models\Group;
use api\models\Other;
use api\models\Routes;
use api\models\ShopAgreement;
use api\models\Text;
use api\models\Traveller;
use api\models\ContractSign;
use common\helpers\BaseDataHelper;
use common\helpers\DataHelper;
use api\services\ContractService;
use yii\base\Exception;
use Yii;

/**
 * Created by PhpStorm.
 * User: robin
 * Date: 2015/11/18
 * Time: 16:11
 */
class EcService
{

    /**
     * 格式化错误信息
     * @param $model
     * @return string
     */
    function getErrorMes($model){
        $err = $model->errors;
        $returnHtml = '';
        foreach ($err as $label => $message) {
            foreach ($message as $k => $v) {
                $returnHtml .= "{$k}：{$v}";
            }
           /* $returnHtml .= "；";*/
        }
        return  $returnHtml;
    }

    /**
     * @author lvkui
     * @date 20150906
     * @param $str 原始中文字符串
     * @return string
     */
    public  function unicode_encode($str)
    {
        $str = json_encode($str);
        return trim($str, '"');
    }

    /**
     * @author lvkui
     * @date 20150906
     * @param $str
     * @return string
     */
    public  function unicode_decode($str)
    {
        if (empty($str))
            return $str;

        $str_decode = '["' . $str . '"]';
        $str_decode = json_decode($str_decode);
        if (count($str_decode) == 1) {
            return $str_decode[0];
        }
        return $str;
    }

    /**
     * 是否存在
     * @param $filed
     * @return string
     */
    public function exist($array,$k){
        if(isset($array[$k])){
            return $array[$k];
        }else{
            return null;
        }
    }

    /**
     * 上传或者提交合同
     * @author lvkui
     * @param $data
     * @param bool $method
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    function sys_submitContract($data,$token,$method=true){
        $tran=null;
        try{
            $tran = Yii::$app->db->beginTransaction();
            if($this->isStrEmpty($data,'contract')){
                throw new Exception('an empty string is not allowed for $contract');
            }

            $user=AccessTokenService::getCurrentUser($token);

            //电子合同
            $ec=$data['contract'];
            $model= new Contract();
            $model->vercode=$ec['vercode'];
            $model->type=$ec['type'];
            $model->is_lock=Contract::CONTRACT_NO;
            $model->status=$method?Contract::CONTRACT_STATUS_COMMITIN:Contract::CONTRACT_STATUS_UNCOMMIT;
            $model->audit_status=$user->org->isaudit?Contract::CONTRACT_YES:Contract::CONTRACT_NO;
            $model->is_submit=$model->status;
            $model->sub_time=$model->is_submit?DataHelper::getCurrentTime():'';
            $model->price=$ec['price'];
            $model->num=$ec['num'];
            $model->transactor=$ec['transactor'];
            $model->oldcontr=$ec['contr_no'];

            $model->contr_no=ContractService::generateNumber(empty($model->oldcontr),$user,$model->type,$model->oldcontr);
            $model->orgid=$user->orgid;
            $model->createtime=BaseDataHelper::getCurrentTime();
            $model->userid=$user->id;

            $count=Contract::find()->where(['contr_no'=>$model->contr_no])->count();
            if($count>0){
                throw new Exception('合同已存在');
            }

            if(!$model->save()){
                throw new Exception($this->getErrorMes($model));
            }



            //线路信息
            if(isset($data['group'])){
                $g=$data['group'];
                $gModel=new Group();
                $gModel->contr_id=$model->contr_id;

                //团号
                if($this->isStrEmpty($g,'teamcode')){
                    throw new Exception('团信息不完整,$group->teamcode');
                }
                $gModel->teamcode=$g['teamcode'];

                //线路名称
                if($this->isStrEmpty($g,'linename')){
                    throw new Exception('团信息不完整,$group->linename');
                }
                $gModel->linename=$g['linename'];

                $gModel->personLimit=$g['personLimit']; //最低成团人数
                $gModel->payGuide=$g['payGuide']; //导游服务费
                $gModel->days=$g['days']; //行程天数
                $gModel->nights=$g['nights']; //几晚
                $gModel->bgndate=$g['bgndate']; //出发时间
                $gModel->enddate=$g['enddate']; //返回时间
                $gModel->from=$g['from']; //出发地
                $gModel->aim=$g['aim']; //目的地
                if(!$gModel->save()){
                    throw new Exception(DataHelper::getErrorMsg($this->getErrorMes($gModel)));
                }
            }

            //游客信息
            if(isset($data['traveller'])){
                foreach($data['traveller'] as $t){
                    $tModel=new Traveller();
                    $tModel->contr_id=$model->contr_id;

                    //姓名
                    if($this->isStrEmpty($t,'name')){
                        throw new Exception('游客信息不完整,$traveller->name');
                    }
                    $tModel->name=$this->exist($t,'name');

                    $tModel->sex=$this->exist($t,'sex'); //性别
                    $tModel->birthday=$this->exist($t,'birthday'); //生日
                    $tModel->nation=$this->exist($t,'nation'); //国籍
                    $tModel->folk=$this->exist($t,'folk'); //民族
                    $tModel->mobile=$this->exist($t,'mobile'); //手机
                    $tModel->idtype=$this->exist($t,'idtype'); //证件类型
                    $tModel->idcode=$this->exist($t,'idcode'); //证件号码
                    $tModel->addr=$this->exist($t,'addr'); //住址
                    $tModel->no=$this->exist($t,'no'); //排序号
                    $tModel->is_leader=$this->exist($t,'is_leader'); //是否是签约代表
                    if($tModel->is_leader){
                        $tModel->extra_data=$this->exist($t,'extra_data');  //签约代表补充信息
                    }

                    if(!$tModel->save()){
                        throw new Exception(DataHelper::getErrorMsg($this->getErrorMes($tModel)));
                    }
                }
            }


            //行程信息
            if(isset($data['routes'])){
                $r=$data['routes'];
                if(!empty($r)){
                    foreach($r as $k=>$j){
                        $rModel=new Routes();
                        $rModel->contr_id=$model->contr_id;
                        $rModel->parentid='0';
                        $rModel->title=$j['title'];
                        $rModel->ctype=Routes::ROUTE_TYPE_JOURNEY;
                        $rModel->index=$j['index'];
                        if($rModel->save()){
                            $parentid=$rModel->id;
                            if(!empty($j['citys'])){
                                foreach($j['citys'] as $i=>$c){
                                    $cModel=new Routes();
                                    $cModel->contr_id=$model->contr_id;
                                    $cModel->parentid=$parentid;
                                    $cModel->title=$c['title'];
                                    $cModel->ctype=Routes::ROUTE_TYPE_CITY;
                                    $cModel->transit=$c['transit'];
                                    $cModel->index=$c['index'];
                                    $cModel->from=$c['from'];
                                    $cModel->aim_city=$c['aim_city'];
                                    $cModel->aim_country=$c['aim_country'];
                                    $cModel->sign=DataHelper::getSign($c['content']);
                                    $text=Text::findOne($cModel->sign);
                                    if(empty($text)){
                                        $text=new Text();
                                        $text->sign=$cModel->sign;
                                        $text->content=$c['content'];
                                        if(!$text->save()){
                                            throw new Exception(DataHelper::getErrorMsg($this->getErrorMes($text)));
                                        }
                                    }

                                    //$cModel->extra_data=$c['extra_data'];
                                    if(!$cModel->save()){
                                        throw new Exception(DataHelper::getErrorMsg($this->getErrorMes($cModel)));
                                    }
                                }
                            }
                        }else{
                            throw new Exception(DataHelper::getErrorMsg($this->getErrorMes($rModel)));
                        }
                    }
                }
            }


            //购物协议
            if(isset($data['shops'])){
                foreach($data['shops'] as $shop){
                    $sModel= new  ShopAgreement();
                    $sModel->contr_id=$model->contr_id;
                    $sModel->name=$shop['name']; //购物地点
                    $sModel->addr=$shop['addr']; //场所
                    $sModel->time=$shop['time']; //具体时间
                    $sModel->goods=$shop['goods']; //主要商品信息
                    $sModel->duration=$shop['duration']; //停留时间 分钟
                    $sModel->memo=$shop['memo']; //其他说明
                    $sModel->agree=$shop['agree']; //是否同意
                    $sModel->index=$shop['index']; //序号
                    if(!$sModel->save()){
                        throw new Exception(DataHelper::getErrorMsg($this->getErrorMes($sModel)));
                    }
                }
            }

            //自费协议
            if(isset($data['chargeables'])){
                foreach($data['chargeables'] as $charge){
                    $chModel= new  Chargeable();
                    $chModel->contr_id=$model->contr_id;
                    $chModel->name=$charge['name']; //项目名称和内容
                    $chModel->addr=$charge['addr']; //地点
                    $chModel->time=$charge['time']; //具体时间
                    $chModel->price=$charge['price']; //费用
                    $chModel->duration=$charge['duration']; //项目时长
                    $chModel->memo=$charge['memo']; //其他说明
                    $chModel->agree=$charge['agree']; //是否同意
                    $chModel->index=$charge['index']; //序号
                    if(!$chModel->save()){
                        throw new Exception(DataHelper::getErrorMsg($this->getErrorMes($chModel)));
                    }
                }
            }

            //合同其它信息
            if(isset($data['other'])){
                $other=$data['other'];
                $oModel=new Other();
                $oModel->contr_id=$model->contr_id;
                $oModel->groupcorp=$other['groupcorp']; //组团社信息
                $oModel->pay=$other['pay']; //支付信息
                $oModel->insurance=$other['insurance']; //保险相关
                $oModel->group=$other['group']; //拼团约定
                $oModel->goldenweek=$this->exist($other,'goldenweek'); //黄金周协议
                $oModel->controversy=$this->exist($other,'controversy'); //争议处理
                $oModel->other=$this->exist($other,'other'); //其他约定
                $oModel->effect=$this->exist($other,'effect'); //合同效力
                if(!$oModel->save()){
                    throw new Exception(DataHelper::getErrorMsg($this->getErrorMes($oModel)));
                }
            }

            //发送短信
            $this->sendMessage($model);
            //设置合同状态为签发中
            $model->status=Contract::CONTRACT_STATUS_SIGNIN;
            if(!$model->save()){
                throw new Exception('合同状态[签发中]更新失败！');
            }

            $tran->commit();
            return array('contr_id'=>$model->contr_id,'contr_no'=>$model->contr_no);
        }catch (Exception $e){
            $tran->rollBack();
            throw $e;
        }
    }

    /**
     * @取消电子合同
     * @author lvkui
     * @param $data
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    function sys_cancelContract($data){
        try{
            if($this->isStrEmpty($data,'contr_id')){
                throw new Exception('an empty string is not allowed for $contr_id');
            }

            if($this->isStrEmpty($data,'contr_no')){
                throw new Exception('an empty string is not allowed for $contr_no');
            }

            $ec=Contract::find()->where(['contr_id'=>$data['contr_id'],'contr_no'=>$data['contr_no']])->one();
            if(empty($ec)){
                throw new Exception('contract does not exist');
            }

            //发送短信
            $leader=$ec->travelLearder;
            if(empty($leader)){
                throw new Exception('未找到游客代表');
            }
            $template=SmsService::getMessageCancelTemplate($leader->name,$ec->contr_id);
            $result=SmsService::batchSend($leader->mobile,$template);
            if(!$result){
                throw new Exception('短信发送失败');
            }

            //取消合同更改合同状态
            $ec->status=Contract::CONTRACT_STATUS_CANCEL;
            if(!$ec->update()){
                throw new Exception('取消合同失败');
            }

        }catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * 根据合同号获取合同uuid
     * @param $contr_no 合同号
     * @return 返回JSON结果
     * @throws Exception
     */
    function sys_getContractUuid($data){
        try{

            if($this->isStrEmpty($data,'contr_no')){
                throw new Exception('an empty string is not allowed for $contr_no');
            }

            $ec=Contract::find()->where(['contr_id'=>$data['contr_id'],'contr_no'=>$data['contr_no']])->one();
            if(empty($ec)){
                throw new Exception('contract does not exist');
            }
            return ['contr_id'=>$ec->contr_id];
        }catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 获取合同的手机号和验证码
     * @param string $sid 合同id
     * @return 返回JSON结果
     * @throws Exception
     */
    function sys_getGetMsgCode($sid){
        //TODO:缺少实现
    }

    /**
     * 根据合同id获取合同签字时间
     * @param $data
     * @return 返回JSON结果
     * @throws Exception
     */
    function sys_getSignCreate($data){

        try{
            if($this->isStrEmpty($data,'contr_id')){
                throw new Exception('an empty string is not allowed for $contr_id');
            }

            $ec=Contract::find()->where(['contr_id'=>$data['contr_id']])->one();
            if(empty($ec)){
                throw new Exception('contract does not exist');
            }

            return ['issign'=>$ec->status==Contract::CONTRACT_STATUS_SIGNED?'1':'0','signtime'=>$ec->sign_time];
        }catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * 重发短信功能
     * @author lvkui
     * @param $data
     * @return 返回JSON结果
     * @throws Exception
     */
    function sys_getResendMsg($data){
        try{

            if($this->isStrEmpty($data,'contr_id')){
                throw new Exception('an empty string is not allowed for $contr_id');
            }

            if($this->isStrEmpty($data,'guestname')){
                throw new Exception('an empty string is not allowed for $guestname');
            }

            if($this->isStrEmpty($data,'guestmobile')){
                throw new Exception('an empty string is not allowed for $guestmobile');
            }

            $ec=Contract::find()->where(['contr_id'=>$data['contr_id']])->one();
            if(empty($ec)){
                throw new Exception('contract does not exist');
            }

            $name=$data['guestname'];
            $mobile=$data['guestmobile'];
            $template=SmsService::getMessageTemplate($name,$ec->contr_id,$mobile);
            $isSend= SmsService::batchSend($mobile,$template);
            if(!$isSend){
                throw new Exception('重发短信失败');
            }

        }catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * 发送短信[主动发送]
     * @param 合同model
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public  function sendMessage($model){
        $leader=Traveller::find()->where(['contr_id'=>$model->contr_id,'is_leader'=>'1'])->one();
        if(empty($leader)){
            throw new Exception('未找到旅游代表');
        }

        $template=SmsService::getMessageTemplate($leader->name,$model->contr_id,$leader->mobile);
        $result=SmsService::batchSend($leader->mobile,$template);
        if(!$result){
            throw new Exception('发送短信失败');
        }
    }


    /**
     * @更改合同状态[未提交--提交]
     * @author lvkui
     * @param $data
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    function sys_submitStatus($data){

        $tran=null;
        try{

            if($this->isStrEmpty($data,'contr_id')){
                throw new Exception('an empty string is not allowed for $contr_id');
            }

            if($this->isStrEmpty($data,'contr_no')){
                throw new Exception('an empty string is not allowed for $contr_no');
            }

            $tran = Yii::$app->db->beginTransaction();
            $ec=Contract::find()->where(['contr_id'=>$data['contr_id'],'contr_no'=>$data['contr_no']])->one();
            if(empty($ec)){
                throw new Exception('contract does not exist');
            }
            if($ec->status=Contract::CONTRACT_STATUS_UNCOMMIT){
                $ec->status=Contract::CONTRACT_STATUS_COMMITIN;
                if($ec->save()){
                    $this->sendMessage($ec);
                    $ec->status=Contract::CONTRACT_STATUS_SIGNIN;
                    $ec->save();
                }
            }
            $tran->commit();
        }catch (Exception $e) {
            $tran->rollBack();
            throw $e;
        }
    }


    /**
     * 补充签名信息
     * @authro lvkui
     * @param $data
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    function sys_submitSign($data){

        try{
            if($this->isStrEmpty($data,'contr_id')){
                throw new Exception('an empty string is not allowed for $contr_id');
            }

            if($this->isStrEmpty($data,'base64image')){
                throw new Exception('an empty string is not allowed for $base64image');
            }

            //TODO:缺少补充签名信息，暂不提供

        }catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 是否为空
     * @param $array 数组
     * @param $key 键值
     * @return bool
     */
    public  function isStrEmpty($array,$key){
        $empty=true;
        $value=$this->exist($array,$key);
        if(is_string($value)){
            if(!is_null($value)){
                $empty= false;
            }
        }elseif(is_array($value)){
            if(!empty($value)){
                $empty= false;
            }
        }
        return $empty;
    }


}