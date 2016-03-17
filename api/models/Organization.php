<?php

namespace api\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "organization".
 *
 * @property string $orgid
 * @property string $name
 * @property string $parentid
 * @property integer $lft
 * @property integer $rgt
 * @property integer $level
 * @property string $enname
 * @property string $vercode
 * @property string $seal
 * @property string $logo
 * @property string $license
 * @property string $teltext
 * @property string $createuserid
 * @property string $createtime
 * @property string $extra_data
 * @property integer $eccount
 * @property integer $isdelete
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organization';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orgid'], 'required'],
            [['lft', 'rgt', 'level', 'isdelete','isaudit','eccount','createuserid'], 'integer'],
            [['createtime'], 'safe'],
            [['extra_data'], 'string'],
            //[['orgid', 'parentid', 'vercode', 'seal', 'logo', 'teltext'], 'string', 'max' => 40],
            [['name', 'enname','license'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'orgid' => '组织ID',
            'name' => '中文名称',
            'parentid' => '父组织',
            'lft' => '左值',
            'rgt' => '右值',
            'level' => '级别',
            'enname' => '英文名称',
            'license' => '经营许可证',
            'vercode' => '合同版本',
            'seal' => '签章图片',
            'logo' => 'logo',
            'teltext' => '短语',
            'createuserid' => '创建人',
            'createtime' => '创建时间',
            'extra_data' => '暂定信息 营业地址 addr联系电话 tel传真 fax邮编 zip邮箱 mail',
            'isaudit'=>'是否需要审核',
            'eccount' =>'电子合同剩余份数',
            'isdelete' => '状态',
        ];
    }

    /**
     * 获取组织logo
     * @method getLogo
     * @return \yii\db\ActiveQuery
     */
    public function getLogo(){
        return $this->hasOne(Fileupload::className(),['logo'=>'fileid']);
    }

    /**
     * 获取组织短语模板
     * @method getTelText
     * @return \yii\db\ActiveQuery
     */
    public function getTeltext(){
        return $this->hasOne(Text::className(),['teltext'=>'sign']);
    }

    /**
     * 获取组织合同版本
     * @return \yii\db\ActiveQuery
     */
    public function getVercode()
    {
        return $this->hasOne(ContractVersion::className(),['vercode'=>'vercode']);
    }

    /**
     * 获取组织签章图片
     * @method getTelText
     * @return \yii\db\ActiveQuery
     */
    public function getSeal(){
        return $this->hasOne(Fileupload::className(),['seal'=>'fileid']);
    }

    /**
     * @return mixed
     */
    public function getSealDataImage()
    {
        return empty($this->seal)?"":"<img class=\"img-responsive rsign-image\" src=\"{$this->seal}\">";

    }

    /**
     * 获取组织下的合同
     * @return \yii\db\ActiveQuery
     */
    public function getContracts(){
        return $this->hasMany(Contract::className(),['orgid'=>'orgid']);
    }

    /**
     * 获取组织创建人
     * @method getUser
     * @return \yii\db\ActiveQuery
     */
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'createuserid']);
    }

}
