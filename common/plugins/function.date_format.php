<?php
use \yii\base\Exception;

/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {date_format} function plugin
 * Type:     function<br>
 * Name:     date_format<br>
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
function smarty_function_date_format($params, $template)
{
    if (empty($params["format"]))
        $format = "Y-m-d H:i:s";
    else
        $format = $params["format"];

    if (empty($params["date"]))
        throw new Exception('date参数错误');

    return date($format, strtotime($params["date"]));
}
