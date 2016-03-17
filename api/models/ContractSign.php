<?php

namespace api\models;

use common\helpers\BaseDataHelper;
use common\helpers\DataHelper;
use api\models\Contract;
use Yii;

/**
 * This is the model class for table "contract_sign".
 *
 * @property integer $sign_id
 * @property integer $contr_id
 * @property string $sign_name
 * @property integer $sign_userid
 * @property integer $sign_time
 * @property string $sign_data
 * @property string $sign_sign
 * @property string $sign_file
 * @property string $sign_code
 * @property string $mobile
 * @property string $code
 * @property string $email
 * @property string $openid
 * @property string $bindtime
 * @property string $sign_data_image
 * @property Contract $contract
 */
class ContractSign extends \yii\db\ActiveRecord
{
    /**
     * ContractSign constructor.
     * @param int $contr_id
     */
    public function __construct($contr_id=null)
    {
        if (!empty($contr_id)){
            $model = ContractSign::find()->where("contr_id='{$contr_id}'")->one();
            if ($model)
                $this->setAttributes($model->attributes);
        }
        else{
            $this->contr_id = $contr_id;
        }
    }
    /**
     * @return mixed
     */
    public function getSignDataImage()
    {
        return empty($this->sign_data)?'':"<img class=\"img-responsive sign-image\" src=\"{$this->sign_data}\">" ;
    }
    public function getContract()
    {
        return $this->hasOne('api\models\Contract', ['contr_id' => 'contr_id']);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract_sign';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contr_id'], 'required'],
            [['contr_id', 'sign_userid', 'sign_time'], 'integer'],
            [['sign_data'], 'string'],
            [['bindtime'], 'safe'],
            [['sign_name','sign_code', 'mobile', 'code', 'email', 'openid'], 'string', 'max' => 100],
            [['sign_sign', 'sign_file'], 'string', 'max' => 255]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sign_id' => '签名id',
            'contr_id' => '合同id',
            'sign_name' => '签名用户',
            'sign_userid' => '签名用户id',
            'sign_time' => '签名时间',
            'sign_data' => '签名数据',
            'sign_sign' => '签名数据签名',
            'sign_file' => '签名文件',
            'sign_code' => '签名验证码',
            'mobile' => '手机号',
            'code' => '验证码',
            'email' => '邮箱',
            'openid' => 'openid',
            'bindtime' => '绑定时间',
        ];
    }
    public function beforeSave($insert)
    {
        if ($this->isNewRecord)
            $this->sign_time = DataHelper::getCurrentTime();

        if (!empty($this->sign_data))
            $this->sign_sign = DataHelper::getSign($this->sign_data);

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
