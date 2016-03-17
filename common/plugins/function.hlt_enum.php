<?php
use api\models\Contract;
use \yii\base\Exception;

/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {hlt_enum} function plugin
 * Type:     function<br>
 * Name:     hlt_enum<br>
 * Purpose:  print out a string value
 *
 * @author Monte Ohrt <monte at ohrt dot com>
 * @link   http://www.smarty.net/manual/en/language.function.counter.php {counter}
 *         (Smarty online manual)
 *
 * @param array $params parameters
 * @param Smarty_Internal_Template $template template object
 * @return null|string
 * @throws Exception
 */
function smarty_function_hlt_enum($params, $template)
{
    if (empty($params["type"]))
        throw new \Exception('type 参数传入错误');

    if (!isset($params["value"]))
        throw new \Exception('value 参数传入错误');

    $type = $params["type"];
    $value = $params["value"];

    $text = '';
    switch($type){
        case "sex":
            $text = empty($value)? '女':'男';
            break;

        case "payType"://旅游费用支付方式
            $text = Contract::$ArrayPayment[$value];;
            break;

        default:
            $text ='未知内容';
            break;
    }
    return $text;
}
