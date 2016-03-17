<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "file".
 *
 * @property string $fsign
 * @property string $server
 * @property string $path
 * @property string $filename
 * @property string $expname
 * @property string $created
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fsign'], 'required'],
            [['created'], 'safe'],
            [['fsign'], 'string', 'max' => 40],
            [['server', 'expname'], 'string', 'max' => 100],
            [['path'], 'string', 'max' => 500],
            [['filename'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fsign' => '文件签名',
            'server' => '域名',
            'path' => '地址',
            'filename' => '文件名',
            'expname' => '扩展名',
            'created' => '创建时间',
        ];
    }
}
