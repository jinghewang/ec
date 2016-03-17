<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "chargeable".
 *
 * @property integer $contr_id
 * @property string $name
 * @property string $addr
 * @property string $time
 * @property double $price
 * @property integer $duration
 * @property string $memo
 * @property integer $agree
 * @property integer $index
 * @property string $extra_data
 */
class Chargeable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chargeable';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contr_id'], 'required'],
            [['contr_id', 'duration', 'agree', 'index'], 'integer'],
            [['time'], 'safe'],
            [['price'], 'number'],
            [['extra_data'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['addr'], 'string', 'max' => 200],
            [['memo'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contr_id' => 'Contr ID',
            'name' => '购物场所名称',
            'addr' => '地点',
            'time' => '具体时间',
            'price' => '费用（元）',
            'duration' => '时长（分钟）',
            'memo' => '其它说明',
            'agree' => '同意',
            'index' => '序号',
            'extra_data' => 'Extra Data',
        ];
    }
}
