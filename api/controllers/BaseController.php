<?php
namespace api\controllers;

use api\services\AjaxStatus;
use yii\web\Controller;

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 *
 * @property bolean $debug 是否输出调试信息
 *
 */
class BaseController extends Controller
{

    /**
     * wirte log
     * @author wjh 20150401
     * @param $name
     * @param null $content
     * @param int $typeid
     * @throws Exception
     */
    public function Log($name,$content=null,$typeid=0){
        $logtype = Yii::app()->params['switch']['logtype'];
        if (!empty($logtype) && $logtype == 'table'){
            DebugService::Log($name,$content=null,$typeid=0);
        }
        else{
            BDataHelper::print_r($name);
            BDataHelper::print_r($content);
        }
    }

    /**
     * get script array
     * @author wjh 20150401
     * @param $data
     * @return string
     */
    public function getScriptArray($data)
    {
        $data2 = array();
        foreach ($data as $key => $value) {
            $data2[] = "$key:'$value',";
        }
        return '{' . implode(',', $data2) . '}';
    }

    protected function getRestMap() {
        $map = array();
        $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
        return $map;
    }

}