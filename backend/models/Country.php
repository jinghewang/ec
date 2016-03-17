<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property string $code
 * @property string $name
 * @property integer $population
 * @property string $createtime
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['population'], 'integer'],
            [['createtime'], 'safe'],
            [['code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => '国家代码',
            'name' => '国家名称',
            'population' => '国家人口',
            'createtime' => '创建时间',
        ];
    }
}
