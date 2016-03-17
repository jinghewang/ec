<?php
use \yii\base\Exception;

/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {hlt_checkbox} function plugin
 * Type:     function<br>
 * Name:     hlt_result<br>
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
function smarty_function_hlt_result($params, $template)
{
    if (empty($params["value"]))
        $value = 0;
    else
        $value = $params["value"];

    return $value?'同意':'不同意';
}
