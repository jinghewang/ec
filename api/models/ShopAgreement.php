<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "shop_agreement".
 *
 * @property integer $contr_id
 * @property string $name
 * @property string $addr
 * @property string $time
 * @property string $goods
 * @property integer $duration
 * @property string $memo
 * @property integer $agree
 * @property integer $index
 * @property string $extra_data
 */
class ShopAgreement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_agreement';
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
            [['extra_data'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['addr'], 'string', 'max' => 200],
            [['goods', 'memo'], 'string', 'max' => 255]
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
            'goods' => '主要商品信息',
            'duration' => '时长（分钟）',
            'memo' => '其它说明',
            'agree' => '同意',
            'index' => '序号',
            'extra_data' => 'Extra Data',
        ];
    }
}
