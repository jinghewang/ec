<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "text".
 *
 * @property string $sign
 * @property string $content
 * @property integer $weight
 */
class Text extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sign'], 'required'],
            [['content'], 'string'],
            [['weight'], 'integer'],
            [['sign'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sign' => '签名',
            'content' => '信息',
            'weight' => '权重',
        ];
    }
}
