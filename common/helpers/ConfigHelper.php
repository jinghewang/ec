<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 2016/1/4
 * Time: 15:43
 */

namespace common\helpers;


class ConfigHelper extends BaseConfigHelper
{

    /**
     * 获取 btg 配置信息
     * @author wjh
     * @date 20150601
     * @param $key
     * @param null $defaultValue
     * @return string
     */
    public static function getBtgConfig($key, $defaultValue = null)
    {
        return self::getParamsConfig('btg', $key, $defaultValue);
    }

    /**
     * 获取 company 配置信息
     * @author wjh
     * @date 20150601
     * @param $key
     * @param null $defaultValue
     * @return string
     */
    public static function getCompanyConfig($key, $defaultValue = null)
    {
        return self::getParamsConfig('company', $key, $defaultValue);
    }

    /**
     * 获取 app 配置信息
     * @author wjh
     * @date 20150601
     * @param $key
     * @param null $defaultValue
     * @return string
     */
    public static function getAppConfig($key, $defaultValue = null)
    {
        return self::getParamsConfig('app', $key, $defaultValue);
    }

}