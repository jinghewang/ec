<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 2016/1/4
 * Time: 15:45
 */

namespace common\helpers;


use Yii;

class BaseConfigHelper
{
    /**
     * 获取hlt 配置信息
     * @author wjh
     * @version 2015-4-6
     * @return string 用户对象User
     */
    public static function getParamsConfig($param, $key, $defaultvalue = null)
    {
        if (isset(Yii::$app->params[$param]) && isset(Yii::$app->params[$param][$key])) {
            if(is_string(Yii::$app->params[$param][$key])){
                return trim(Yii::$app->params[$param][$key]);
            }else{
                return Yii::$app->params[$param][$key];
            }
        }
        return $defaultvalue;
    }

    /**
     * 获取hlt 配置信息
     * @author wjh
     * @version 2015-4-6
     * @return string 用户对象User
     */
    public static function getParamsConfigArray($param,$defaultvalue=null)
    {
        if (isset(Yii::$app->params[$param]))
            return Yii::$app->params[$param];

        return $defaultvalue;
    }
}