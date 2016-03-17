<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "contract_version".
 *
 * @property string $vercode
 * @property string $pcode
 * @property string $title
 * @property string $fileid
 * @property string $orgid
 * @property string $extra_data
 * @property string $createuser
 * @property string $createtime
 */
class ContractVersion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract_version';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vercode'], 'required'],
            [['extra_data'], 'string'],
            [['createtime'], 'safe'],
            [['createuser'],'integer'],
            [['vercode', 'pcode', 'fileid', 'orgid',], 'string', 'max' => 40],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vercode' => '合同版本号',
            'pcode' => '父版本号',
            'title' => '名称',
            'fileid' => '模板文件',
            'orgid' => '所属组织',
            'extra_data' => '扩展信息',
            'createuser' => '创建人',
            'createtime' => '创建时间',
        ];
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->hasOne(Fileupload::className(),['fileid'=>'fileid']);
    }

}
