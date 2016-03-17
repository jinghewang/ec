<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "smarty_test".
 *
 * @property integer $id
 * @property string $name
 * @property integer $age
 * @property string $created
 */
class SmartyTest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'smarty_test';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['age'], 'integer'],
            [['created'], 'safe'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'name' => '名称',
            'age' => '年龄',
            'created' => '创建时间',
        ];
    }
}
