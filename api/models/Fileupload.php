<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "fileupload".
 *
 * @property string $fileid
 * @property string $fsign
 * @property string $filename
 * @property string $expname
 * @property string $created
 */
class Fileupload extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fileupload';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fileid'], 'required'],
            [['created'], 'safe'],
            [['fileid', 'fsign'], 'string', 'max' => 40],
            [['filename'], 'string', 'max' => 200],
            [['expname'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fileid' => '文件id',
            'fsign' => '文件签名',
            'filename' => '文件名称',
            'expname' => '扩展名',
            'created' => '创建时间',
        ];
    }

    /**
     * 关联文件表File
     * @method getFile
     * @return \yii\db\ActiveQuery
     */
    public function  getFile(){
        return $this->hasOne(File::className(),['fsign'=>'fsign']);
    }
}
