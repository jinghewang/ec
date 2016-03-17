<?php
namespace common\models;

use api\models\ContractSign;
use api\services\WxService;
use common\helpers\BDataHelper;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * Login form
 */
class SignLoginForm extends Model
{
    public $mobile;
    public $code;
    public $openid;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['mobile', 'code'], 'required'],
            [['mobile','code', 'openid'], 'string', 'max' => 100],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['code', 'validateCode'],
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
            'mobile'=>'手机号',
            'code'=>'验证码',
            'openid'=>'openid',
            'rememberMe'=>'记住我'
        ];

    }


    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateCode($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getContractSign();
            if (!$user || !$user->code == $this->code) {
                $this->addError($attribute, '手机号或者验证码不正确.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        /**
         * @var ContractSign $cs
         */
        $wxService = new WxService();
        if (true || $this->validate()) {
            $cs = ContractSign::find()->where("mobile='{$this->mobile}'")->orderBy('sign_id desc')->one();
            if ($cs && $cs->mobile == $this->mobile && $cs->code == $this->code){
                $cs->openid = $this->openid;
                $cs->bindtime = BDataHelper::getCurrentTime();
                if (!$cs->save())
                    throw new Exception('操作失败，原因：'.json_encode($cs->errors));

                $wxService->setLogin($cs->attributes);
                return true;
            }
            else
                return false;
            //return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }


    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function logout()
    {
        /**
         * @var ContractSign $cs
         */
        $wxService = new WxService();
        $wxService->setLogout();
        Yii::$app->response->redirect($wxService->loginPage);
    }

    /**
     *
     * @return ContractSign|null
     */
    protected function getContractSign()
    {
        if ($this->_user === null) {
            $this->_user = ContractSign::findOne(['mobile'=>$this->mobile]);
        }

        return $this->_user;
    }
}
