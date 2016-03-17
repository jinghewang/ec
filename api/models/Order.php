<?php

namespace api\models;

use common\helpers\BDefind;
use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $oid
 * @property string $appkey
 * @property string $orgid
 * @property integer $createuserid
 * @property string $createtime
 * @property string $out_trade_no
 * @property string $paysum
 * @property integer $paynum
 * @property integer $status
 * @property string $post1
 * @property string $post2
 * @property string $posttime
 * @property string $result
 * @property string $resulttime
 * @property string $callback
 * @property string $callbacktime
 *
 *
 * @property string $statusname
 */
class Order extends \yii\db\ActiveRecord
{


    /**
     * @return mixed
     */
    public function getStatusname()
    {
        return BDefind::getValue(self::$STATUS,$this->status);
    }


    const STATUS_SUCCESS = 1;
    const STATUS_FAIL = -1;
    const STATUS_DEFAULT = 0;

    static $STATUS = [
        self::STATUS_SUCCESS => '成功',
        self::STATUS_FAIL => '失败',
        self::STATUS_DEFAULT => '默认'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createuserid', 'paynum', 'status'], 'integer'],
            [['createtime', 'posttime', 'resulttime', 'callbacktime'], 'safe'],
            [['paysum'], 'number'],
            [['post1', 'post2', 'result', 'callback'], 'string'],
            [['appkey'], 'string', 'max' => 50],
            [['orgid'], 'string', 'max' => 40],
            [['out_trade_no'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'oid' => '订单号',
            'appkey' => 'appkey',
            'orgid' => '组织id',
            'createuserid' => '创建人id',
            'createtime' => '创建时间',
            'out_trade_no' => '业务订单号',
            'paysum' => '付款金额',
            'paynum' => '购买次数',
            'status' => '状态',
            'post1' => '发送数据1',
            'post2' => '发送数据2',
            'posttime' => '发送时间',
            'result' => '结果数据',
            'resulttime' => '结果数据时间',
            'callback' => '回调数据',
            'callbacktime' => '回调时间',
        ];
    }
}
