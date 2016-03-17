<?php
namespace api\services;

use api\models\Debug;
use common\helpers\BDataHelper;

/**
 * Created by PhpStorm.
 * User: wjh
 * Date: 14-6-2
 * Time: 上午9:58
 */

class DebugService {


    //日志类型
    const TYPE_GROUP = 1;
    const TYPE_ORDER = 2;
    const TYPE_PAY = 3;
    const TYPE_PWD = 10000;

    /**
     * 日志类型
     */
    public static $TYPE = array(
        self::TYPE_GROUP => '团',
        self::TYPE_ORDER => '订单',
        self::TYPE_PAY => '支付',
        self::TYPE_PWD => '用户密码',
    );

    /**
     * 写入订单日志
     * @author wjh 20140828
     * @param string $name
     * @param string $content [optional]
     * @param int $typeid [optional]
     * @throws Exception
     */
    public static function Log($name,$content=null,$typeid=0){
        if (is_array($content))
            $content = json_encode($content);

        $debug = new Debug();
        $debug->name= $name;
        $debug->content = $content;
        $debug->typeid = $typeid;
        $debug->createtime = BDataHelper::getCurrentTime();
        if(!$debug->save()){
            throw new Exception(BDataHelper::getErrorMsg('Debug 保存失败',$debug));
        }
    }


    /**
     * 删除10天前调试日志
     * @author wjh 20140828
     */
    public static function clearLog(){
        Debug::model()->deleteAll('createtime<DATE_SUB(SYSDATE(),INTERVAL 1 HOUR)');//DAY
    }


}