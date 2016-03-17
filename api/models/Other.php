<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "other".
 *
 * @property integer $contr_id
 * @property string $groupcorp
 * @property string $pay
 * @property string $insurance
 * @property string $group
 * @property string $goldenweek
 * @property string $controversy
 * @property string $other
 * @property string $effect
 */
class Other extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'other';
    }

    public static  $ArrayPayment=array('1'=>'现金', '2'=>'支票', '3'=>'信用卡',);
    public static  $ArrayPurchases=array('1'=>'委托旅行社购买', '2'=>'自行购买', '3'=>'放弃购买',);
    public static  $ArrayGroup_Fail=array('0'=>'委托出团','1'=>'延期出团','2'=>'改签线路','3'=>'解除合同');
    public static  $ArrayConciliation=array('1'=>'仲裁委员会','2'=>'人民法院');
    public static  $ArrayIs_Fight=array('1'=>'同意','0'=>'不同意');
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contr_id'], 'required'],
            [['contr_id'], 'integer'],
            [['groupcorp', 'pay', 'insurance', 'group', 'goldenweek', 'controversy', 'other', 'effect'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contr_id' => 'Contr ID',
            'groupcorp' => '组团社信息  corp旅行社名称、corpCode 旅行社业务经营许可证编号 、scope经营范围、addr营业地址、tel 联系电话、fax 传真、zip 邮编、mail 电子信箱',
            
            'pay' => '旅游费用支付方式及时间  payEachAdult 成人单价、payEachChild儿童单间、numAdult 成人数量、numChild儿童数量、payTravel合计花费 、payDeadline旅游费用交纳期限、payType旅游费用交纳方式',
            'insurance' => 'agree 旅游者【1：委托旅行社购买 2：自行购买 3：放弃购买】
            product 保险产品名称',
            'group' => 'transAgree 旅行者__旅行社【0：不同意 1：同意】 
            transAgency 旅行社委托__社履行合同【旅行社名称】 
            delayAgree 旅行者__延期出团【0：不同意 1：同意】
            changeLineAgree 旅行者__改签其他线路出团【0：不同意 1：同意】
            terminateAgree 旅行者__解除合同【0：不同意 1：同意】
            mergeAgree 旅行者__采用拼团方式【0：不同意 1：同意】
            mergeAgency 拼团拼至__出境社成团
            teminateDealType 协商或者调解不成的，按第几种方式处理【1：提交仲裁委员会仲裁；2：依法向人民法院起诉】
            committee 仲裁委员会',
            'goldenweek' => '黄金周特别约定',
            'controversy' => '争议处理',
            'other' => '其他约定事项
            supplementaryClause 其他约定事项',
            'effect' => '合同效力
            copys1 合同一式__份
            copys2 双方各持__份
            agencyComplaintsMobile 出境社监督、投诉电话
            lawCity 旅游质监执法机构 市
            lawComplaintsMobile 旅游质监执法机构 投诉电话
            lawState 旅游质监执法机构 省
            lawEmail 旅游质监执法机构 电子邮箱
            awAddress 旅游质监执法机构 地址 
            lawZip 旅游质监执法机构 邮编
            addr 签约地点',
        ];
    }
}
