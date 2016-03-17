<?php

namespace api\models;

//use Faker\Provider\DateTime;
use common\helpers\DataHelper;
use common\models\User;
use Yii;

/**
 * This is the model class for table "access_token".
 *
 * @property string $tokenid
 * @property string $clientid
 * @property string $appkey
 * @property string $orgid
 * @property string $uid
 * @property integer $validity
 * @property string $createtime
 */
class AccessToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'access_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tokenid'], 'required'],
            [['validity','uid'], 'integer'],
            [['createtime'], 'safe'],
            [['tokenid', 'clientid', 'appkey'], 'string', 'max' => 32],
            [['orgid'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tokenid' => '令牌tokenid',
            'clientid' => '接入客户端ID',
            'appkey' => '应用标识',
            'orgid' => '请求组织',
            'uid' => '请求操作员',
            'validity' => '令牌有效期（天）',
            'createtime' => '创建时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppkey()
    {
        return $this->hasOne(AccessApp::className(), ['appkey' => 'appkey']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrg(){
        return $this->hasOne(Organization::className(), ['orgid' => 'orgid']);
    }

    /**
     * @inheritdoc
     * @return AccessTokenQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AccessTokenQuery(get_called_class());
    }

    /**
     * This method is called at the beginning of inserting or updating a record.
     * The default implementation will trigger an [[EVENT_BEFORE_INSERT]] event when `$insert` is true,
     * or an [[EVENT_BEFORE_UPDATE]] event if `$insert` is false.
     * When overriding this method, make sure you call the parent implementation like the following:
     *
     * @param boolean $insert whether this method called while inserting a record.
     * If false, it means the method is called while updating a record.
     * @return boolean whether the insertion or updating should continue.
     * If false, the insertion or updating will be cancelled.
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord){
            //$this->tokenid = 'ssda';
            $this->createtime = DataHelper::getCurrentTime();
        }

        return parent::beforeSave($insert);
    }
}
