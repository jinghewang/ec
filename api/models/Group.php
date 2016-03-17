<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "group".
 *
 * @property integer $contr_id
 * @property string $teamcode
 * @property string $linename
 * @property integer $personLimit
 * @property string $payGuide
 * @property integer $days
 * @property integer $nights
 * @property string $bgndate
 * @property string $enddate
 * @property string $from
 * @property string $aim
 * @property string $extra_data
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contr_id'], 'required'],
            [['contr_id', 'personLimit', 'days', 'nights'], 'integer'],
            [['payGuide'], 'number'],
            [['bgndate', 'enddate'], 'safe'],
            [['extra_data'], 'string'],
            [['teamcode', 'aim'], 'string', 'max' => 100],
            [['linename'], 'string', 'max' => 200],
            [['from'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contr_id' => '合同ID',
            'teamcode' => '团号',
            'linename' => '线路名称',
            'personLimit' => '最低成团人数',
            'payGuide' => '导游服务费',
            'days' => '行程天数',
            'nights' => '几晚',
            'bgndate' => '出团日期',
            'enddate' => '回团日期',
            'from' => '出发城市',
            'aim' => '目的地',
            'extra_data' => '扩展信息',
        ];
    }
}
