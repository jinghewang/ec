<?php

namespace api\models;

use api\services\AccessTokenService;
use common\helpers\BaseDataHelper;
use common\helpers\DataHelper;
use common\models\User;
use api\services\ContractService;
use Yii;
use yii\base\ModelEvent;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "contract".
 *
 * @property integer $contr_id
 * @property string $contr_no
 * @property string $vercode
 * @property string $type
 * @property integer $is_lock
 * @property string $lock_time
 * @property integer $status
 * @property integer $audit_status
 * @property integer $is_submit
 * @property string $sub_time
 * @property string $sign_time
 * @property string $sign_time_f
 * @property string $price
 * @property integer $num
 * @property string $transactor
 * @property string $oldcontr
 * @property string $orgid
 * @property string $createtime
 * @property string $userid
 * @property string $modified
 * @property string $extra_data
 * @property string $path
 * @property string $filesign
 */
class Contract extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract';
    }

    /**
     * 合同类型-台湾
     */
    const CONTRACT_TYPE_TW='T';

    /**
     * 合同类型-出境
     */
    const CONTRACT_TYPE_OUTSIDE='C';

    /**
     * 合同类型-国内
     */
    const CONTRACT_TYPE_INSIDE='G';

    /**
     * 合同类型-一日游
     */
    const CONTRACT_TYPE_ONETRIP='Y';

    /**
     * 合同类型-包车合同
     */
    const CONTRACT_TYPE_CARTRIP='B';

    /**
     * 合同类型-地接社合同
     */
    const CONTRACT_TYPE_ENTRUST='J';

    static $contract_type=array(
        self::CONTRACT_TYPE_INSIDE=>'国内合同',
        self::CONTRACT_TYPE_OUTSIDE=>'出境合同',
        self::CONTRACT_TYPE_TW=>'台湾合同',
        self::CONTRACT_TYPE_ONETRIP=>'一日游合同',
        self::CONTRACT_TYPE_CARTRIP=>'包车合同',
        self::CONTRACT_TYPE_ENTRUST=>'地接社合同',
    );

    /**
     * 合同状态
     */
    const CONTRACT_STATUS_CANCEL='-1'; //已取消
    const CONTRACT_STATUS_UNCOMMIT='0'; //未提交
    const CONTRACT_STATUS_COMMITIN='1'; //已提交
    const CONTRACT_STATUS_SIGNIN='2'; //签发中
    const CONTRACT_STATUS_SIGNED='3'; //已签发

    static $contract_status=array(
        self::CONTRACT_STATUS_CANCEL=>'已取消',
        self::CONTRACT_STATUS_UNCOMMIT=>'未提交',
        self::CONTRACT_STATUS_COMMITIN=>'已提交',
        self::CONTRACT_STATUS_SIGNIN=>'签发中',
        self::CONTRACT_STATUS_SIGNED=>'已签发',
    );

    /**
     * 合同通用状态
     */
    const CONTRACT_YES='1';
    const CONTRACT_NO='0';

/**
     * 合同审核状态
     */
    const CONTRACT_AUDITSTATUS_YES='1';
    const CONTRACT_AUDITSTATUS_NO='0';
    public static  $ArrayPayment=array('1'=>'现金', '2'=>'支票', '3'=>'信用卡',);
    public static  $ArrayPurchases=array('1'=>'委托旅行社购买', '2'=>'自行购买', '3'=>'放弃购买',);
    public static  $ArrayGroup_Fail=array('0'=>'委托出团','1'=>'延期出团','2'=>'改签线路','3'=>'解除合同');
    public static  $ArrayConciliation=array('1'=>'仲裁委员会','2'=>'人民法院');
    public static  $ArrayIs_Fight=array('1'=>'同意','0'=>'不同意');
    /**
     * @return mixed
     */
    public function getSignTimeF()
    {
        if (empty($this->sign_time))
            return '';
        return DataHelper::getSignTime($this->sign_time);
    }
    /**
     * 合同号生成规则
     */
    const CONTRNO_SYSTEM='G';
    const CONTRNO_APP='T';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contr_no','vercode','type','price','num'], 'required'],
            [['contr_id', 'is_lock', 'status', 'audit_status', 'is_submit', 'num','userid'], 'integer'],
            [['lock_time', 'sub_time', 'sign_time', 'createtime', 'modified'], 'safe'],
            [['price'], 'number'],
            [['path'],'string','max'=>'255'],
            [['filesign'],'string','max'=>'100'],
            [['extra_data'], 'string'],
            [['contr_no', 'oldcontr'], 'string', 'max' => 60],
            [['vercode', 'transactor', 'orgid'], 'string', 'max' => 40],
            [['type'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contr_id' => '流水号',
            'contr_no' => '合同号',//合同号 生成号+经营许可证号简写 + 合同类型 + 6位年月日 + 5位流水号
            'vercode' => '合同版本,关联version',
            'type' => '合同类别 ：赴台游合同T 、出境游合同C、国内游合同G、一日游合同Y 、包车合同B、地接社合同J',
            'is_lock' => '是否锁定',
            'lock_time' => '锁定时间',
            'status' => '合同状态 取消、签发..',
            'audit_status' => '审核状态',
            'is_submit' => '是否提交',
            'sub_time' => '提交时间',
            'sign_time' => '签发时间',
            'price' => '旅游费用合计',
            'num' => '人数',
            'transactor' => '经办人',
            'oldcontr' => '原合同号',
            'orgid' => '所属公司',
            'createtime' => '创建时间',
            'userid' => '创建人',
            'modified' => '修改时间',
            'extra_data' => 'Extra Data',
        ];
    }

    public function getSign()
    {
        return $this->hasOne(ContractSign::className(), ['contr_id' => 'contr_id']);
    }

    public function getTraveller()
    {
        return $this->hasOne(Traveller::className(), ['contr_id' => 'contr_id']);
    }

    /**
     * 获取自费项目
     * @return \yii\db\ActiveQuery
     */
    public function getChargeables(){
        return $this->hasMany(Chargeable::className(),['contr_id'=>'contr_id']);
    }

    /**
     * 获取购物协议
     * @return \yii\db\ActiveQuery
     */
    public function getShopAgreements(){
        return $this->hasMany(ShopAgreement::className(),['contr_id'=>'contr_id']);
    }

    /**
     * 获取行程信息
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes(){
        return $this->hasMany(Routes::className(),['contr_id'=>'contr_id']);
    }

    /**
     * 获取游客信息
     * @return \yii\db\ActiveQuery
     */
    public function getTravels(){
        return $this->hasMany(Traveller::className(),['contr_id'=>'contr_id']);
    }

    /**
     * 获取游客代表信息
     * @return \yii\db\ActiveQuery
     */
    public function getTravelLearder(){
        return $this->hasOne(Traveller::className(),['contr_id'=>'contr_id'])
            ->andWhere(['is_leader'=>Traveller::LEADER_YES]);
    }

    /**
     * 获取合同团的信息
     * @return \yii\db\ActiveQuery
     */
    public function getGroup(){
        return $this->hasOne(Group::className(),['contr_id'=>'contr_id']);
    }

    /**
     * 获取合同的其它信息
     * @return \yii\db\ActiveQuery
     */
    public function getOther(){
        return $this->hasOne(Other::className(),['contr_id'=>'contr_id']);
    }

    /**
     * 获取合同的组织信息
     * @return \yii\db\ActiveQuery
     */
    public function getOrg(){
        return $this->hasOne(Organization::className(),['orgid'=>'orgid']);
    }

    /**
     * 获取合同的创建人信息
     * @return \yii\db\ActiveQuery
     */
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'userid']);
    }

    /**
     * 合同版本
     * @return \yii\db\ActiveQuery
     */
    public function getVersion(){
        return $this->hasOne(ContractVersion::className(),['vercode'=>'vercode']);
    }

    public function beforeSave($insert)
    {
        $this->modified=BaseDataHelper::getCurrentTime();
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }


}
