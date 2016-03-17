<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {date_now} function plugin
 * Type:     function<br>
 * Name:     date_now<br>
 * Purpose:  print out a string value
 *
 * @author Monte Ohrt <monte at ohrt dot com>
 * @link   http://www.smarty.net/manual/en/language.function.counter.php {counter}
 *         (Smarty online manual)
 *
 * @param array                    $params   parameters
 * @param Smarty_Internal_Template $template template object
 *
 * @return string|null
 */
function smarty_function_date_now($params, $template)
{
    if(empty($params["format"])) {
        $format = "Y年m月d日";//2016年01月07日
    } else {
        $format = $params["format"];
    }
    return date($format, time());
}
