<?php
namespace common\helpers;

/**
 * BDataHelper class file.
 *
 * @author wangjinghe <wangjinghe@vive.net.cn>
 * @link http://www.vive.net.cn/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.vive.net.cn/license/
 */
use Yii;

/**
 * BDataHelper is a static class that provides a collection of helper methods for creating HTML views.
 *
 * @author wangjinghe <wangjinghe@vive.net.cn>
 * @package system.web.helpers
 * @since 1.0
 */
class DataHelper extends BaseDataHelper
{
    public static $orgid = '1';


    /**
     * 获取异常处理信息
     * @author wjh 2014-9-13
     * @param $msg
     * @param null $obj
     * @return string
     */
    public static function getErrorMsg($msg, $obj = null)
    {
        if ($obj instanceof CActiveRecord) {
            //$msg = SnapshotHelper::encodeArray($obj->errors);
            //echo BDataHelper::getAjaxResponse($obj->getErrors(),$obj->getAttributes(),"json");
            /*    if (empty($msg))
                    $msg = get_class($obj) . '操作失败';*/

            $err = $obj->errors;
            $returnHtml = '';
            foreach ($err as $label => $message) {
                //$returnHtml .= "<strong>$label:</strong>";
                foreach ($message as $k => $v) {
                    $returnHtml .= "$v &emsp;";
                }
                $returnHtml .= "<br>";
            }
            return $msg . ' ' . $returnHtml;
        } elseif ($obj instanceof Exception) {
            return $msg . ' ' . $obj->getMessage();
        } else {
            return $msg;
        }
    }

    /**
     * 判断当前用户是否包含某个角色
     * @author wjh 2014-9-13
     * @param string $roleKey 关键字,如 agent_ ,provider_  ,provider_manager
     * @return bool 是否包含
     */
    public static function checkCurrentUserRole($roleKey, $userid = null)
    {
        if (empty($userid))
            $userid = BDataHelper::getCurrentUserid();
        $roles = Assignments::model()->findAllByAttributes(array('userid' => $userid));
        $data = BArrayHelper::array_func($roles, function ($k, $v, $userdate = null) {
                return array($v->itemname);
            },
            function ($k, $v, $userdata = null) use ($roleKey) {
                $rolename = $v->itemname;
                return substr($rolename, 0, strlen($roleKey)) == $roleKey;
            });

        return count($data);
    }


    /**
     * 判断当前用户是否包含某个角色
     * @author lvkui
     * @param $roleKey
     * @return bool
     */
    public static function checkCurrentUserRole2($roleKey)
    {
        $roles=Yii::app()->user->user->roles;
        $roles=BArrayHelper::array_column($roles,'name');
        return in_array($roleKey,$roles);
    }


    /**
     * 返回当前登录用户ID
     * @author bolen
     * @version 2014-8-13
     * @return string 用户ID
     */
    public static function getCurrentUserid($defaultvalue = null)
    {
        if (!isset(Yii::app()->user) || !isset(Yii::app()->user->user)) {
            if (!is_null($defaultvalue))
                return $defaultvalue;
            else
                throw new Exception("Not logged in");
        }
        return Yii::app()->user->user->userid;
    }


    /**
     * 获取 hlt 配置信息
     * @author wjh
     * @date 20150406
     * @param $key
     * @param null $defaultvalue
     * @return string
     */
    public static function getHltConfig($key, $defaultvalue = null)
    {
        return self::getParamsConfig('hlt', $key, $defaultvalue);
    }


    /**
     * 获取 switch 配置信息
     * @author wjh
     * @date 20150406
     * @param $key
     * @param null $defaultvalue
     * @return string
     */
    public static function getSwitchConfig($key, $defaultvalue = null)
    {
        return self::getParamsConfig('switch', $key, $defaultvalue);
    }


    /**
     * 获取 search 配置信息
     * @author wjh
     * @date 20150406
     * @param $key
     * @param null $defaultvalue
     * @return string
     */
    public static function getSearchConfig($key, $defaultvalue = null)
    {
        return self::getParamsConfig('search', $key, $defaultvalue);
    }

    /**
     * 获取 hlt 配置信息
     * @author wjh
     * @date 20150406
     * @param $key
     * @param null $defaultvalue
     * @return string
     */
    public static function getSmsConfig($key, $defaultvalue = null)
    {
        return self::getParamsConfig('sms', $key, $defaultvalue);
    }


    /**
     * 获取 btg 配置信息
     * @author wjh
     * @date 20150601
     * @param $key
     * @param null $defaultvalue
     * @return string
     */
    public static function getBtgConfig($key, $defaultvalue = null)
    {
        return self::getParamsConfig('btg', $key, $defaultvalue);
    }

    /**
     * 获取hlt 配置信息
     * @author wjh
     * @version 2015-4-6
     * @return string 用户对象User
     */
    private static function getParamsConfig($param, $key, $defaultvalue = null)
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
     * 返回当前登录用户对象User
     * @author bolen
     * @version 2014-8-13
     * @return User 用户对象User
     */
    public static function getCurrentUser()
    {
        if (!isset(Yii::app()->user->user)) {
            throw new Exception("Not logged in");
        }
        return Yii::app()->user->user;
    }

    /**
     * 返回当前登录用户组织ID
     * @author bolen
     * @version 2014-8-13
     * @return string 组织ID
     */
    public static function getCurrentOrgid($defaultvalue = null)
    {
        if (!isset(Yii::app()->user) || !isset(Yii::app()->user->user)) {
            if (!is_null($defaultvalue))
                return $defaultvalue;
            else
                throw new Exception("Not logged in");
        }
        return Yii::app()->user->user->orgid;
    }

    /**
     * 返回当前登录用户组织对象Organization
     * @author wjh
     * @version 2014-8-13
     * @return Organization 组织对象
     * @throws Exception
     */
    public static function getCurrentOrg()
    {
        if (!isset(Yii::app()->user->user)) {
            throw new Exception("Not logged in");
        }
        $org = Organization::model()->findByPk(Yii::app()->user->user->org->orgid);
        return $org;
    }

    /**
     * 获取是毫秒
     * @author bolen
     * @version 2014-8-13
     * @return string 组织对象Organization
     */
    public static function getMicroTime()
    {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }


    //定义业务系统相关部分内容

    /**
     * 返回币种数组
     * @author wjh
     * @version 2014-3-27
     * @return array 币种数组
     */
    public static function getCurrencyArray()
    {
        return BDataHelper::getConfigArray('currency');
    }

    /**
     * 返回币种数组
     * @author wjh
     * @version 2014-3-27
     * @return array 币种数组
     */
    public static function getCurrency($index)
    {
        return BDataHelper::getConfigArray('currency', $index);
    }

    /**
     * 返回支付方式
     * @author wjh
     * @version 2014-3-27
     * @return array 币种数组
     */
    public static function getPayMethodArray()
    {
        return array('0' => '请选择', '1' => '现金', '2' => '支票', '3' => '混合',);

    }

    /**
     *
     * 定义库存的标准
     * @author wangjingzhi
     * @date Apr 29, 2014 5:11:55 PM
     * @return 库存标准数组
     *
     */
    public static function getSkuArray()
    {
        return array('0' => '请选择', '1' => '个', '2' => '套');
    }

    /**
     *
     * 获取单位标准数组
     * @author wangjingzhi
     * @date Apr 29, 2014 5:01:19 PM
     * @return 单位标准数组
     *
     */
    public static function getUnitStandardArray()
    {
        return array('' => '请选择', 'P' => '按人', 'O' => '按次', 'D' => '按天', 'H' => '按时', 'M' => '按月', 'W' => '按星期', 'Y' => '按年');
    }

    /**
     *
     * 获取类目类型数组
     * @author wangjingzhi
     * @date Apr 29, 2014 5:03:24 PM
     * @return 类目类型数组
     *
     */
    public static function getCategoryTypeArray()
    {
        return array('baseinfo' => '基本', 'journey' => '行程', 'price' => '价格', 'contract' => '合同', 'contract' => '其他');
    }

    /**
     *
     * 定义是否必选数组
     * @author wangjingzhi
     * @date Apr 29, 2014 5:17:37 PM
     * @return 是否数组
     *
     */
    public static function getAreRequiredArray()
    {
        return array('0' => '是', '1' => '否');
    }

    /**
     * 获取 request 参数
     * @author wjh 20150408
     * @param $key
     * @param null $defaultvalue
     * @return null|string
     */
    public static function getRequestParam($key, $defaultvalue = null)
    {
        return empty($_REQUEST[$key]) ? $defaultvalue : trim($_REQUEST[$key]);
    }


    /**
     * 获取 request 参数
     * @author wjh 20150408
     * @param $key
     * @param null $defaultvalue
     * @return null|string
     */
    public static function getRequestCondition($key, $searchKey, $defaultvalue = null)
    {
        $value = empty($_REQUEST[$key]) ? $defaultvalue : trim($_REQUEST[$key]);
        if (!empty($value))
            return "{$searchKey}='{$value}'";
        else
            return null;

    }



    /**
     * 拼接 url 参数
     * @author wjh 20150923
     * @param $key
     * @param null $requestKey
     * @return string
     */
    public static function getRequestUrlParam($key, $requestKey = null)
    {
        $value = self::getRequestParam(empty($requestKey) ? $key : $requestKey);
        return empty($value) ? '' : "/$key/$value";
    }

    /**
     *
     * 无子节点
     * @var HAS_NOT_CHILD
     */
    const HAS_NOT_CHILD = 0;

    /**
     *
     * 有子节点
     * @var HAS_CHILD
     */
    const HAS_CHILD = 1;

    /**
     *
     * 定义是否有子节点数组
     * @author wangjingzhi
     * @date Apr 29, 2014 5:17:37 PM
     * @return array
     *
     */
    public static function getHasChildArray()
    {
        return array(self::HAS_NOT_CHILD => '无', self::HAS_CHILD => '有');
    }


    /**
     * 检测是否手机号
     * @param $str
     * @return bool
     */
    public static function getJavaDateTime($str)
    {
        return strtotime($str) * 1000;
    }

    /**
     * 检测是否手机号
     * @param $str
     * @return bool
     */
    public static function checkMobile($str)
    {
        if (preg_match("/1[34578]{1}\d{9}$/", $str))
            return true;
        else
            return false;
    }

    /**
     *
     * 产品模型
     * @var PRODUCT_MODEL
     */
    const PRODUCT_MODEL = 'P';

    /**
     *
     * 团模型
     * @var GROUP_MODEL
     */
    const GROUP_MODEL = 'G';

    /**
     * 定义模型分类
     * @author wangjingzhi
     * @date May 8, 2014 12:11:34 PM
     * @return array
     *
     */
    public static function getModelTypeArray()
    {
        return array(
            //'' => '请选择',
            //self::PRODUCT_MODEL => '产品模型',
            self::GROUP_MODEL => '团模型');
    }

    const MEAL = '餐标';

    const TRAFFICE = '交通';

    const HOTEL = '住宿';

    /**
     * 获取数据库表字段最大值
     * @author wangjingzhi
     * @date Apr 29, 2014 5:04:08 PM
     * @param $tableName 数据库表名
     * @param $fieldName 表字段名称
     * @return 表字段最大值
     *
     */
    public static function getColumMaxID($tableName, $fieldName)
    {
        $id = 0;
        $sql = 'select max(`' . $fieldName . '`) as id from `' . $tableName . '` limit 1';
        try {
            $cnt = Yii::app()->db->createCommand($sql);
            $dataRow = $cnt->query();
            $data = $dataRow->read();
            $id = $data['id'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return $id;
    }

    /**
     *
     * @author wangjingzhi
     * @date Apr 29, 2014 5:07:24 PM
     * @param array $array 数组对象
     * @param string $index 当前索引
     * @return mixed 当前索引对应的值
     *
     */
    public static function getStr($array, $index)
    {
        return BArrayHelper::getValue($array, $index);
    }


    /**
     * 判断当前用户是否权限
     * 为当前用户或者部门经理
     * @author wjh
     * @date 2014-5-27
     * @param $userid
     * @param null $orgid
     * @throws Exception
     * @return string
     */
    public static function checkUserAudit($userid, $orgid = null)
    {
        if ($userid == BDataHelper::getCurrentUserid())
            return true;

        $user = BDataHelper::getCurrentUser();
        if (($user->level == User::LEVEL_DM || $user->level == User::LEVEL_GM)) {
            $orgids = OrganizationService::getOrgChildrenIds(BDataHelper::getCurrentOrgid(), true);
            return BArrayHelper::array_value_exists($orgid, $orgids);
        }
        return false;
    }

    /**
     * 获取程序的目录
     * @author wjh
     * @date 2015年7月15日
     * @return string
     */
    public static function getAppPath($path = '')
    {
        $base = Yii::app()->basePath;
        $appPath = substr($base, 0, (strlen($base) - strlen("protected") - 1));
        return $appPath . $path;
    }


    /**
     * 获取程序扩展的目录
     * @author wjh
     * @date 2015年7月15日
     * @return string
     */
    public static function getExtensionPath($path = '')
    {
        $base = Yii::app()->extensionPath;
        return $base . $path;
    }


    /**
     * 获取 where 中 in
     * @author wjh
     * @param $attribute
     * @param $data
     * @return string
     */
    public static function getInCondition($attribute, $data)
    {
        if (empty($data)) {
            return '';
        } else {
            $instr = "'" . implode("','", $data) . "'";
            return "$attribute in({$instr})";
        }
    }


    /**
     * 附加搜索条件
     * @author wjh
     * @param string $condition
     * @param string $added
     * @return string
     */
    public static function addCondition($condition, $added)
    {
        if (!empty($condition) && !empty($added))
            return "{$condition} and {$added}";
        elseif (empty($condition) && empty($added)) {
            return "";
        } elseif (!empty($condition) && empty($added)) {
            return $condition;
        } elseif (empty($condition) && !empty($added)) {
            return $added;
        } else {
            return "";
        }
    }


    /**
     * 获取组织名称
     * @author wjh
     * @date 2014-5-27
     * @param array $ids orgid array
     * @return string
     */
    public static function getOrganizationName($ids)
    {
        $data = self::getOrganizations($ids);
        return implode(',', $data);
    }

    /**
     * 获取组织名称 同 getOrganizationName
     * @author wjh
     * @date 2014-5-27
     * @param array $ids orgid array
     * @return string
     */
    public static function getOrgName($ids)
    {
        $data = self::getOrganizations($ids);
        return implode(',', $data);
    }

    /**
     * 获取用户角色的名称
     * getRolesName
     * @author wangjingzhi
     * @param $roles 角色datas
     * @return string
     */
    public static function getRolesName($roles)
    {
        $data = array();
        if (!empty($roles)) {
            foreach ($roles as $role) {
                if ($role->name == 'sales_manager') {
                    continue;
                }
                $data[] = $role->description;
            }
        }
        return implode(',', $data);
    }

    /**
     * 获取渠道名称（缓存中读取）
     * @author wjh
     * @date 2014-5-27
     * @param mixed $ids chanid array
     * @return string
     */
    public static function getChannelName($ids)
    {
        if (!is_array($ids))
            $ids = array($ids);

        $orgs = BCacheHelper::getChannel($ids);
        $orgs = BArrayHelper::array_column($orgs, 'name', 'chanid');
        return implode(',', $orgs);
    }

    /**
     * 获取区域名称（缓存中读取）
     * @author wjh
     * @date 2014-5-27
     * @param mixed $ids sid array
     * @return string
     */
    public static function getSortName($ids)
    {
        if (!is_array($ids))
            $ids = array($ids);

        $sorts = BCacheHelper::getSorts($ids);
        $sorts = BArrayHelper::array_column($sorts, 'sid', 'cn_name');
        return implode(',', $sorts);
    }

    /**
     * 获取用户名称
     * @author wjh
     * @date 2014-5-27
     * @param array $ids userid array
     * @return string
     */
    public static function getUserName($ids)
    {
        $data = self::getUsers($ids);
        return implode(',', $data);
    }

    /**
     * 获取组织数组
     * @author wjh
     * @date 2014-5-27
     * @param mixed $ids orgid string or orgid string array
     * @return string
     */
    public static function getOrganizations($ids)
    {
        BDataHelper::addHeaderUTF8();
        if (!is_array($ids))
            $ids = array($ids);

        $data = BCacheHelper::getOrganization($ids);
        return BArrayHelper::array_column($data, 'name', 'orgid');
    }

    /**
     * 获取用户数组
     * @author wjh
     * @date 2014-5-27
     * @param array $ids userid array
     * @return string
     */
    public static function getUsers($ids)
    {
        BDataHelper::addHeaderUTF8();
        if (!is_array($ids))
            $ids = array($ids);

        $data = BCacheHelper::getUser($ids); //User::model()->findAllByPk($ids);
        return BArrayHelper::array_column($data, 'name', 'userid');
    }

    /**
     * 判断是否来源 btgtravel
     * @author wjh 2015-3-1
     * @return bool
     */
    public static function isSource()
    {
        if (!empty($_REQUEST['source']))
            return true;
        else
            return false;
    }


    /**
     * 根据来源判断页面布局
     * @author wjh 2015-3-1
     * @return string
     */
    public static function getLayout($defaultLayout)
    {
        if (!empty($_REQUEST['source']))
            return "//layouts/mainframe_" . trim($_REQUEST['source']);
        else
            return $defaultLayout;
    }

    /**
     * 判断是否来源 btgtravel
     * @author wjh 2015-3-1
     * @return bool
     */
    public static function getSource()
    {
        if (!empty($_REQUEST['source']))
            return trim($_REQUEST['source']);
        else
            return '';
    }


    /**
     * ensureArray
     * @author wjh 2015-1-4
     * @param $var
     */
    public static function ensureArray(&$var)
    {
        if (!is_array($var))
            $var = array($var);
    }

    /*
     * downLoadFile
     * @author lvkui
     * @date 2014-06-03
     * @param $fign
     */
    public static function downLoadFile($fign, $file_name)
    {
        $model = File::model()->findByPk($fign);
        $filePath = 'http://' . $model->server . $model->path;
        $filename = str_replace(' ', '', $file_name); //去除空格
        $filename = iconv("utf-8", "GB2312//IGNORE", $filename); //解决乱码
        $file = @fopen($filePath, "r");

        if (!$file) {

            throw new Exception("文件不存在");

        } else {

            Header("Content-type: " . $model->expname);
            Header("Content-Disposition: attachment; filename=" . $filename);
            while (!feof($file)) {

                echo fread($file, 50000);

            }
            fclose($file);
        }
    }


    /*
    * downLoadFile
    * @author lvkui
    * @date 2014-06-03
    * @param $fign sign or fid
    * @param int $type 1 fid ,0 fsign
    * @throws Exception
    */
    public static function downLoadFileByFid($fid, $type = 0)
    {
        /**
         * @var FileUpload $model
         */
        $model = FileUpload::model()->findByPk($fid);
        self::downLoadFile($model->f_sign, $model->filename);
    }

    /*
     * @var IN_ENQUIRE
     * 询价中
     */
    const IN_ENQUIRE = 0;

    /*
     * @VAR IN_ENQUIREHADN
     * 处理中
     */
    const IN_ENQUIRE_HADN = 1;

    /*
     * @VAR ENQUIRE_PRICE
     * 报价中
     */
    const ENQUIRE_PRICE = 2;

    /*
     * @VAR ENQUIRE_REJECT
     * 已驳回
     */
    const ENQUIRE_REJECT = 3;

    /*
     * @VAR ENQUIRE_CERTAIN
     * 已确认
     */
    const ENQUIRE_CERTAIN = 4;

    /*
     * @var ENQUIRE_CANCEL
     * 已取消
     */
    const ENQUIRE_CANCEL = 5;

    /*
     * getEnquireStatusArray
     * 询价订单状态
     * @author lvkui
     * @date 2014-06-04
     * @return array
     */
    public static function  getEnquireStatusArray()
    {
        return array(
            '' => '—请选择—',
            self::IN_ENQUIRE => '询价中',
            self::IN_ENQUIRE_HADN => '处理中',
            self::ENQUIRE_PRICE => '报价中',
            self::ENQUIRE_REJECT => '已驳回',
            self::ENQUIRE_CERTAIN => '已确认',
            self::ENQUIRE_CANCEL => '已取消',
        );
    }


    /**
     * 获得子团的母团信息
     * @author wjh 2014-6-17
     * @param $gid
     * @return Group
     */
    public static function getParentGroup($gid)
    {
        /**
         * @var Group $group
         */
        $group = Group::model()->findByPk($gid);
        while (!empty($group->parent_gid)) {
            $group = Group::model()->findByPk($group->parent_gid);
        }

        return $group;
    }

    /**
     * 获得子团的母团id
     * @author wjh 2014-6-17
     * @param $gid
     * @return Group
     */
    public static function getParentGroupID($gid)
    {
        /**
         * @var Group $group
         */
        $group = self::getParentGroup($gid);
        if (is_null($group)) {
            return null;
        } else {
            return $group->gid;
        }
    }


    /**
     * startWith
     * @author wjh 2014-6-17
     * @param string $str 原字符串
     * @param string $strart 搜索字符串
     * @return bool
     */
    public static function startWith($str, $strart)
    {
        if (empty($str) || empty($strart)) {
            return false;
        }

        $str = substr($str, 0, strlen($strart));
        return $str == $strart;
    }

    /**
     * 获取订单支付状态
     * @author wjh 20140711
     * @param $paystatus
     * @return string
     */
    public static function getOrderPayStatus($paystatus)
    {
        if ($paystatus == 100) {
            return '已付';
        } elseif ($paystatus == 0) {
            return '未付';
        } else {
            return '未完全支付';
        }
        //return  count($paystatus)?'已付':'未付';
    }


    /**
     * 获取当前人权限过滤condition
     * @author wjh 2014-8-21
     * @param $useridfld
     * @param $orgidfld
     */
    public static function getCurrentUserAuditCondition($useridfld = 'userid', $orgidfld = 'orgid')
    {
        $condition = '';
        $level = Yii::app()->user->user->level;
        switch ($level) {
            case User::LEVEL_EMP:
                $condition .= sprintf("%s='%s'", $useridfld, BDataHelper::getCurrentUserid());
                break;

            case User::LEVEL_DM:
                $condition .= sprintf("%s='%s'", $orgidfld, BDataHelper::getCurrentOrgid());
                break;

            case User::LEVEL_GM:
                $condition .= sprintf("%s='%s'", $orgidfld, BDataHelper::getCurrentOrgid());
                break;

            default:
                $condition .= sprintf("%s='%s'", $useridfld, BDataHelper::getCurrentUserid());
                break;
        }

        return $condition;
    }


    /**
     * 获取用户分管组织ID
     * @author wjh 2014-8-21
     * @param null $userid
     * @return int
     */
    public static function getUserManagerID($userid = null)
    {
        $org = self::getUserManager($userid);
        return $org->orgid;
    }

    public static function  getUserRoles()
    {
        $user = Yii::app()->user;
        if (!empty($user->roles)) {
            $s = serialize($user->roles);
            return unserialize($s);
        } else {
            return array();
        }
    }


    /**
     * 获取用户分管组织
     * @author wjh 2014-8-21
     * @param null $userid
     * @return int
     */
    public static function getUserManager($userid = null)
    {
        if (is_null($userid))
            $userid = BDataHelper::getCurrentUserid();
        $user = User::model()->with('org')->findAll("level in(%s,%s) and orgid=%s ", User::LEVEL_DM, User::LEVEL_GM, $userid);
        return $user->org;
    }

    /**
     * 获取团期
     * @author wjh 2014-8-15
     * @param $group_dates
     * @return string
     */
    public static function getGroupDates($group_dates, $type = 'w')
    {
        //$plan = ProductPlan::model()->find("pid='{$data->pid}'");
        //echo $plan->group_dates;
        //--------
        if ($type == 'd') {
            $weekarray = array("日", "一", "二", "三", "四", "五", "六");
            $dates = explode(',', $group_dates);
            $weeks = array();
            foreach ($dates as $index => $date) {
                $w = date('d/m', strtotime($date));
                $weeks[] = $w;
            }
            //$weeks = array_unique($weeks);
            //sort($weeks);
            $weeks = array_slice($weeks, 0, 10);

            $strs = '';
            foreach ($weeks as $index => $w) {
                if ($index > 0) {
                    $strs .= ',';
                }
                $strs .= '' . $w;
            }
            return $strs;
        } else {
            $weekarray = array("日", "一", "二", "三", "四", "五", "六");
            $dates = explode(',', $group_dates);
            $weeks = array();
            foreach ($dates as $index => $date) {
                $w = date('w', strtotime($date));
                $weeks[] = $w;
            }
            $weeks = array_unique($weeks);
            sort($weeks);
            $strs = '';
            foreach ($weeks as $index => $w) {
                if ($index > 0) {
                    $strs .= ',';
                }
                $strs .= '周' . $weekarray[$w];
            }
            return $strs;
        }
    }

    /**
     * 获取团期
     * @author wjh 2014-8-15
     * @param $gid parent_gid
     * @param int $status 状态
     * @param int $limit 记录数量
     * @param int $itemIndex 行的索引
     * @return string
     */
    public static function getGroupDatesBySearch($gid, $status = 0, $limit = 10, $itemIndex = 0)
    {
        $status_str = empty($status) ? '' : " and status=" . $status;
        $dates = BSqlHelper::queryAll("select gid,group_date,status from `group` where isdelete=0 {$status_str} and parent_gid='{$gid}' order by group_date");
        $weeks = array();
        foreach ($dates as $index => $date) {

            $w = date('d/m', strtotime($date['group_date']));
            $title = BDataHelper::getStr(Group::$ARRAY_GROUP_STATUS, $date['status']);
            if ($index == 0) {
                $weeks[] = empty($status) ? "<span class='label log-info label-info uz-bg-group-status-{$date['status']}' title='{$date["gid"]}({$title})' style='margin:0.1em;margin-left:0.4em;'>" . $w . "</span>" : $w;
            } else {
                $weeks[] = empty($status) ? "<span class='label log-info label-info uz-bg-group-status-{$date['status']}' title='{$date["gid"]}({$title})' style='margin:0.1em;'>" . $w . "</span>" : $w;
            }
        }

        $weeks = array_slice($weeks, 0, $limit);
        $strs = '';

        foreach ($weeks as $index => $w) {
            if ($index == 16)
                $strs .= '<div id="etc' . $itemIndex . '" class="collapse">';
            $strs .= '' . $w;
        }

        if (count($weeks) > 16) {
            return $strs .= '</div>';
        } else {
            return $strs;
        }
    }

    /**
     * 获取团期
     * @author wjh 2014-8-15
     * @param $gid parent_gid
     * @param int $status 状态
     * @param int $limit 记录数量
     * @param int $itemIndex 行的索引
     * @return string
     */
    public static function getGroupDatesBySearch2($gid, $status = 0, $limit = 10)
    {
        $status_str = empty($status) ? '' : " and status=" . $status;
        $dates = BSqlHelper::queryAll("select gid,group_date,status from `group` where isdelete=0 {$status_str} and parent_gid='{$gid}' order by group_date");
        $weeks = array();
        foreach ($dates as $index => $date) {

            $w = date('d/m', strtotime($date['group_date']));
            $title = BDataHelper::getStr(Group::$ARRAY_GROUP_STATUS, $date['status']);
            $weeks[] = empty($status) ? "<span class='label log-info label-info uz-bg-group-status-{$date['status']}' title='{$date["gid"]}({$title})' style='margin:0.1em;'>" . $w . "</span>" : $w;
        }

        $weeks = array_slice($weeks, 0, $limit);
        $strs = implode(',', $weeks);
        return $strs;
    }


    /**
     * 获取团期
     * @author wjh 2014-8-15
     * @param $gid parent_gid
     * @return string
     */
    public static function isVisibleCollapse($gid, $status = 0)
    {
        $status_str = empty($status) ? '' : " and status=" . $status;
        $dates = BSqlHelper::queryAll("select gid,group_date,status from `group` where isdelete=0 {$status_str} and parent_gid='{$gid}' order by group_date");
        if (count($dates) > 16) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * 获取集合中对象的属性值;返回数组集合
     * @param $objects
     * @param $key
     * @return Array
     */
    public static function getObjectToKeys($objects, $key)
    {
        $array = array();
        if (empty($objects) || empty($key)) {
            return $array;
        }
        foreach ($objects as $object) {
            $value = self::getModelProperty($object, $key);
            if (!empty($value)) {
                $array[] = $value;
            }
        }
        return $array;
    }

    public static function getCombinationToString($arr, $m)
    {
        $result = array();
        if ($m == 1) {
            return $arr;
        }

        if ($m == count($arr)) {
            $result[] = implode(',', $arr);
            return $result;
        }

        $temp_firstelement = $arr[0];
        unset($arr[0]);
        $arr = array_values($arr);
        $temp_list1 = self::getCombinationToString($arr, ($m - 1));

        foreach ($temp_list1 as $s) {
            $s = $temp_firstelement . ',' . $s;
            $result[] = $s;
        }
        unset($temp_list1);

        $temp_list2 = self::getCombinationToString($arr, $m);
        foreach ($temp_list2 as $s) {
            $result[] = $s;
        }
        unset($temp_list2);

        return $result;
    }

    public static function getFullCombination($arr)
    {
        $data = array();
        for ($i = 1; $i <= count($arr); $i++) {
            $t = self::getCombinationToString($arr, $i);
            $data = array_merge($data, $t);
        }
        return $data;
    }


    /**
     * 生成 PItem 树型数据
     * @author wjh 20141017
     * @param $data
     * @param $parentid
     * @return array
     * @throws Exception
     */
    public static function getTreeFromArray($data, $parentid)
    {
        $parentid = empty($parentid) ? 0 : $parentid;
        $rdata = array();
        $items = BArrayHelper::array_filter_two($data, 'parentid', $parentid);
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            $item['child'] = self::getTreeFromArray($data, $item['iid']);
            $rdata[] = $item;
        }
        return $rdata;
    }


    public static function getClearArray($data, $flds)
    {
        //$flds = array('iid','parentid','pid','title','ctype','haschild');
        array_walk_recursive($data, function ($v, $k) use ($flds) {
            var_dump($v);
            /*foreach ($v as $ek=>$ev) {
                if(!array_key_exists($ek,$flds))
                    unset($v[$ek]);
            }*/
        });
        return $data;
    }

    /**
     * 截取字符创
     * @param $String  要截取的字符串
     * @param $length  长度
     * @param $Append  是否添加省略号，默认为false
     * @return string  返回截取后的字符串
     */
    public static function  str_cut($String, $Length, $Append = false)
    {

        if (strlen($String) <= $Length) {
            return $String;
        } else {
            $I = 0;
            while ($I < $Length) {
                $StringTMP = substr($String, $I, 1);
                if (ord($StringTMP) >= 224) {
                    $StringTMP = substr($String, $I, 3);
                    $I = $I + 3;
                } elseif (ord($StringTMP) >= 192) {
                    $StringTMP = substr($String, $I, 2);
                    $I = $I + 2;
                } else {
                    $I = $I + 1;
                }
                $StringLast[] = $StringTMP;
            }
            $StringLast = implode("", $StringLast);
            if ($Append) {
                $StringLast .= "...";
            }
            return $StringLast;
        }
    }


    /**
     * 根据身份证号获取用户今天是否生日、生日日期及几天后生日
     * @author lvkui 2014-11-12
     * @param $IDCard 身份证号
     * @return array
     */
    public static function getUserInfoByIDCard($IDCard)
    {
        $result = array();
        $date = BDataHelper::getCurrentTime('m-d');
        $nowYear = BDataHelper::getCurrentTime('Y');

        if (empty($IDCard)) {
            $result['birthday'] = '';
            $result['flag'] = false;
            return $result;
        }

        if (!preg_match("/^[1-9]([0-9a-zA-Z]{17}|[0-9a-zA-Z]{14})$/i", $IDCard)) {
            $result['birthday'] = '';
            $result['flag'] = false;
        } else {

            $tmonth = '';
            $tday = '';
            if (strlen($IDCard) == 18) {
                $tmonth = intval(substr($IDCard, 10, 2));
                $tday = intval(substr($IDCard, 12, 2));
            } elseif (strlen($IDCard) == 15) {
                $tmonth = intval(substr($IDCard, 8, 2));
                $tday = intval(substr($IDCard, 10, 2));
            }

            $result['birthday'] = $tmonth . "-" . $tday; //生日 月日
            $birthday = strtotime($nowYear . '-' . $result['birthday']); //生日的时间
            $nowShortTime = strtotime(BDataHelper::getCurrentTime('Y-m-d')); //当前时间
            $agoShortTime = strtotime(date('Y-m-d', $nowShortTime) . ' 23:59:59') + 7 * 3600 * 24;

            if ($birthday >= $nowShortTime && $birthday <= $agoShortTime) {
                $result['flag'] = true;
            } else {
                $result['flag'] = false;
            }
            $count = ($birthday - $nowShortTime) / 3600 / 24;
            $result['count'] = $count;
        }
        return $result;
    }


    /**
     * 根据出生日期获取用户今天是否生日、生日日期及几天后生日
     * @author lvkui 2014-12-02
     * @param $brithday 出生日期
     * @return array
     */
    public static function getBirthdayInfoBydate($brithday)
    {
        $result = array();
        if (empty($brithday)) {
            $result['birthday'] = '';
            $result['flag'] = false;
            $result['count'] = '';
            return $result;
        }

        $tmonth = substr($brithday, 5, 2);
        $tday = substr($brithday, 8, 2);

        $nowYear = BDataHelper::getCurrentTime('Y');
        $result['birthday'] = $tmonth . '-' . $tday; //生日 月日
        $newBirthday = strtotime($nowYear . '-' . $result['birthday']); //生日的时间
        $nowShortTime = strtotime(BDataHelper::getCurrentTime('Y-m-d')); //当前时间
        $agoShortTime = strtotime(date('Y-m-d', $nowShortTime) . ' 23:59:59') + 7 * 3600 * 24;
        if ($newBirthday >= $nowShortTime && $newBirthday <= $agoShortTime) {
            $result['flag'] = true;
        } else {
            $result['flag'] = false;
        }
        $count = ($newBirthday - $nowShortTime) / 3600 / 24;
        $result['count'] = $count;
        $result['old'] = $brithday;
        return $result;
    }


    /**
     * 根据身份证获取生日  年-月-日
     * @param $IDCard
     * @return string
     */
    public static function  getBirthdayByCard($IDCard)
    {
        $birthday = '';
        if (preg_match("/^[1-9]([0-9a-zA-Z]{17}|[0-9a-zA-Z]{14})$/i", $IDCard)) {
            if (strlen($IDCard) == 18) {
                $year = substr($IDCard, 6, 4);
                $tmonth = substr($IDCard, 10, 2);
                $tday = substr($IDCard, 12, 2);
            } elseif (strlen($IDCard) == 15) {
                $year = '19' . substr($IDCard, 6, 2);
                $tmonth = substr($IDCard, 8, 2);
                $tday = substr($IDCard, 10, 2);
            }
            $birthday = $year . '-' . $tmonth . '-' . $tday;
        }

        return $birthday;
    }

    //判断当前登录人是否为门市
    public static function  isAgent()
    {
        return self::checkCurrentUserRole('sales_operator');
    }

    /**
     * 汉字转换成拼音（首字母）
     * @author lvkui
     * @param $str
     * @return null|string
     */
    public static function  str_Py($str)
    {
        if (empty($str)) {
            return '';
        }
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($str{0});
        $s1 = iconv('UTF-8', 'gb2312', $str);
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) return 'A';
        if ($asc >= -20283 && $asc <= -19776) return 'B';
        if ($asc >= -19775 && $asc <= -19219) return 'C';
        if ($asc >= -19218 && $asc <= -18711) return 'D';
        if ($asc >= -18710 && $asc <= -18527) return 'E';
        if ($asc >= -18526 && $asc <= -18240) return 'F';
        if ($asc >= -18239 && $asc <= -17923) return 'G';
        if ($asc >= -17922 && $asc <= -17418) return 'H';
        if ($asc >= -17417 && $asc <= -16475) return 'J';
        if ($asc >= -16474 && $asc <= -16213) return 'K';
        if ($asc >= -16212 && $asc <= -15641) return 'L';
        if ($asc >= -15640 && $asc <= -15166) return 'M';
        if ($asc >= -15165 && $asc <= -14923) return 'N';
        if ($asc >= -14922 && $asc <= -14915) return 'O';
        if ($asc >= -14914 && $asc <= -14631) return 'P';
        if ($asc >= -14630 && $asc <= -14150) return 'Q';
        if ($asc >= -14149 && $asc <= -14091) return 'R';
        if ($asc >= -14090 && $asc <= -13319) return 'S';
        if ($asc >= -13318 && $asc <= -12839) return 'T';
        if ($asc >= -12838 && $asc <= -12557) return 'W';
        if ($asc >= -12556 && $asc <= -11848) return 'X';
        if ($asc >= -11847 && $asc <= -11056) return 'Y';
        if ($asc >= -11055 && $asc <= -10247) return 'Z';
        return null;
    }

    /**
     * 获取团主题数组
     * @author wjh 20141211
     * @param $subtitles
     * @return array
     */
    public static function getGroupSubtitle($subtitles)
    {
        if (!empty($subtitles)) {
            $subtitles = explode(',', $subtitles);
            return $subtitles;
        }
        return array();
    }

    /**
     * 获取是否删除名称
     * @author wjh 20141217
     * @param $isdelete
     * @return mixed
     */
    public static function getIsDeleteName($isdelete)
    {
        return BDefind::getValue(BDefind::$ISDELETE, $isdelete);
    }

    /**
     * 获取是否删除名称
     * @author wjh 20141217
     * @param $isdelete
     * @return mixed
     */
    public static function getIsDeleteArray()
    {
        return array('-1' => '请选择', '0' => '启用', '1' => '停用');
    }

    /**
     * 是否是内勤
     * @author lvkui
     */
    public static function  IsInsidejob()
    {
        return self::checkCurrentUserRole('insidejob_');
    }

    /**
     * 是否admin
     * @author lvkui
     */
    public static function  IsAdmin()
    {
        return self::checkCurrentUserRole('admin');
    }

    /**
     * 是否sytem
     * @author lvkui
     */
    public static function  IsSystem()
    {
        return self::checkCurrentUserRole('system');
    }

    /**
     * 是否提供商
     * @param null $userid
     * @return bool
     */
    public static function isProvider($userid = null)
    {
        return self::checkCurrentUserRole('provider_operator', $userid);
    }

    /**
     * 是否组团社
     * @param null $userid
     * @return bool
     */
    public static function isGroup($userid = null)
    {
        return self::checkCurrentUserRole('group_operator', $userid);
    }

    /**
     * 是否是总公司管理员
     * @author lvkui
     */
    public static function  IsHeadAdmin()
    {
        return self::checkCurrentUserRole('HO_manager');
    }

    /**
     * 是否是公司管理员
     * @author lvkui
     */
    public static function  IsCompanyAdmin()
    {
        return self::checkCurrentUserRole('system');
    }

    /**
     * 是否是总管理员
     * @author lvkui
     */
    public static function  IsBalance()
    {
        return self::checkCurrentUserRole('balance');
    }


    /**
     * 返回价格整数
     * @author wjh 20150206
     * @param $num
     * @return float
     */
    public static function getPrice($num1, $num2)
    {
        if (empty($num2) || empty($num1))
            return 0;

        return ceil($num1 / $num2);
    }


    /**
     * 输出当前控制器所有 actions 链接
     * @author wjh 20150205
     * @param $obj
     */
    public static function showControllerActions($obj)
    {
        $class = get_class($obj);
        $methods = get_class_methods($class);
        $data = array();
        $str = 'action';
        array_walk($methods, function ($v) use (&$data, $str) {
            $sub = substr($v, 0, strlen($str));
            if (strlen($v) > strlen($str) && $sub == $str)
                $data[] = substr($v, strlen($str), strlen($v) - strlen($str));
        });

        foreach ($data as $method) {
            echo "<a href='{$method}' target='_self'>{$method}</a><br>";
        }
        //BDataHelper::print_r($data);
    }

    /**
     * 获取当前登陆的组织相关信息(logo 、title等)
     * @author lvkui
     * @date 2015-03-04
     */
    public static function getCurrentOrgInfo()
    {
        $currentOrg = Yii::app()->session['currentOrg'];
        if (!empty($currentOrg)) {
            return $currentOrg;
        } else {
            Yii::app()->session['currentOrg'] = OrganizationService::getOrgInfo();
            return Yii::app()->session['currentOrg'];
        }
    }

    /**
     * 是否开启同步到hltTravel,默认关闭
     * @author lvkui
     * @date 2015-04-03
     */
    public static function hltSyn()
    {
        return Yii::app()->params['hlt']['hltsyn'];
    }

    /**
     * 获取转换后的状态 hlt
     * @author --lvkui
     * @date 20150406
     * @param $tablename
     * @param $key
     * @param $status
     * @return null
     */
    public static function hlt_statusExchange($tablename, $key, $status)
    {
        try {
            if ($status != null)
                return BDefind::$hlt_Status[$tablename][$key][$status];
            else
                return null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * 获取产品推荐的 orgid
     * @author wjh  20150422
     * @return string
     * @throws Exception
     */
    public static function getAdCompanyOrgid()
    {
        $company = OrganizationService::getCompany(BDataHelper::getCurrentOrgid());
        if (BArrayHelper::array_value_exists($company->orgid, array(BDefind::ORG_MENSHI, BDefind::ORG_ZHONGLV))) {
            return $company->orgid;
        } else {
            return BDefind::ORG_GONGMIN;
        }
    }


    /**
     * 位幂运算
     * @author wjh 20150508
     * @param $key
     * @param $type
     * @return string
     */
    public static function checkBinaryInclude($key, $type)
    {
        if (empty($key) || empty($type))
            return false;

        $type_array = array_reverse(str_split(decbin($type)));
        $key_array = array_reverse(str_split(decbin($key)));
        if (count($key_array) <= count($type_array)) {
            $ttt = $type_array[count($key_array) - 1];
            if ($ttt == '1') {
                return true;
            }
        }
        return false;
    }

    /**
     * 位幂运算
     * @author wjh 20150508
     * @param $key
     * @param $type
     * @return string
     */
    public static function getBinaryInclude($type, $var)
    {
        if (empty($type) || empty($var))
            return false;

        $data = array();
        foreach ($var as $key => $value) {
            $ischecked = BDataHelper::checkBinaryInclude($key, $type);
            if ($ischecked)
                $data[$key] = BDefind::getValue($var, $key);
        }
        return $data;
    }

    /**
     * 是否是门市组织(暂时处理)
     * @author lvkui
     * @params $orgid
     */
    public static function  getIsAgent($orgid = null)
    {
        if (is_null($orgid)) {
            $orgid = self::getCurrentOrgid();
        }

        //父id
        $arr_agentOrgids = array(
            '542290f9cbfd8', //神舟
            '54153e9ad7c12', //公民同业
            '54b8a1523a67a', //康辉
            '55010d1050201', //汇来
        );

        $all_agent = array();
        foreach ($arr_agentOrgids as $v) {
            $childOrgids = OrganizationService::getOrgChildrenIds($v, true);
            $all_agent = array_merge($all_agent, $childOrgids);
        }
        $all_agent = array_unique($all_agent);

        return in_array($orgid, $all_agent);
    }

    /**
     *
     * 获取当前用户是否存在admin.product 角色
     * @return bool
     */
    public function isExistsAdminProduct()
    {
        $exists = false;
        $roles = BDataHelper::getCurrentUser()->roles;
        foreach ($roles as $role) {
            if ($role->name == 'AgentC') {
                $exists = true;
                break;
            }
        }
        return $exists;
    }

    /**
     * 是否是空null||empty
     * 不包含0
     * @param String||Array
     * @return bool
     */
    public static function  isNullOrEmpty($str)
    {
        if (is_null($str)) {
            return true;
        } else {
            if (is_array($str)) {
                if (empty($str)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if ($str == 0 || $str != '') {
                    return false;
                } else {

                    return true;
                }
            }
        }
    }


    /**
     * @author lvkui
     * @date 20150906
     * @param $str 原始中文字符串
     * @return string
     */
    public static function unicode_encode($str)
    {
        $str = json_encode($str);
        return trim($str, '"');
    }

    /**
     * @author lvkui
     * @date 20150906
     * @param $str
     * @return string
     */
    public static function unicode_decode($str)
    {
        if (empty($str))
            return $str;

        $str_decode = '["' . $str . '"]';
        $str_decode = json_decode($str_decode);
        if (count($str_decode) == 1) {
            return $str_decode[0];
        }
        return $str;
    }

    /**
     * 获取HTL团渠道
     * @param $gid
     * @return string
     */
    public static function hltChanid($gid){
        $bool=GroupService::hltProvider_exists($gid);
        $chanid = BDataHelper::getHltConfig('chanid');
        if($bool){
            $chanid=BDataHelper::getHltConfig('menshisystem');
        }
        return $chanid;
    }

    /**
     * getAttributesArray
     * @param $data
     * @return array
     */
    public static function getAttributesArray($data)
    {
        $data2 = array();
        foreach ($data as $item) {
            $data2[] = $item->attributes;
        }
        return $data2;
    }

    /**
     * getAttributesArray
     * @param $data
     * @return array
     */
    public static function print_r2($data)
    {
        if (BArrayHelper::isCActiveRecordArray($data))
            $data = self::getAttributesArray($data);
        self::print_r($data);
    }

}
