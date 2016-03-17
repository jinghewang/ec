<?php
namespace common\models;

use api\models\ContractSign;
use api\services\WxService;
use common\helpers\BDataHelper;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class CodeForm extends Model
{
    public $code;
    public $state;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'state'], 'required']
        ];
    }



    /**
     * Returns the attribute labels.
     *
     * @return array attribute labels (name => label)
     * @see generateAttributeLabel()
     */
    public function attributeLabels()
    {
        return [
            'code'=>'代码',
            'state'=>'状态',
        ];

    }

    public function isValuable()
    {
        return !empty($this->code);
    }

}
