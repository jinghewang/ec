<?php
namespace common\helpers;

/**
 * 公共常量定义类
 * User: wjh
 * Date: 14-6-3
 * Time: 上午9:50
 */

class BDefind {

    //区域 国内 出境
    public static $SORT_GN = 1;
    public static $SORT_CJ = 0;

    //--------------------------------------------------
    //二状态数组
    const TWO_YES = 1;
    const TWO_NO = 0;

    /**
     * 二状态数组
     */
    public static $TWO = array(
        self::TWO_YES=>'是',
        self::TWO_NO=>'否',
    );

    //---------------------------------------------------

    //三状态数组
    const THREE_OK = 1;
    const THREE_NO = 0;
    const THREE_CANCEL = -1;

    /**
     * 三状态数组
     */
    public static $THREE = array(
        self::THREE_OK => '是',
        self::THREE_NO => '否',
        self::THREE_CANCEL => '取消',
    );

    //---------------------------------------------------

    //性别
    const SEX_MALE = 1;
    const SEX_FEMALE = 2;
    const SEX_UNDEFIND = -1;


    /**
     * @var array 性别
     */
    public static $SEX = array(
        self::SEX_MALE=>'男',
        self::SEX_FEMALE=>'女',
        self::SEX_UNDEFIND=>'保密'
    );

    //是否选中
    const CHECKED_TRUE = 1;
    const CHECKED_FALSE = 0;
    const CHCKED_UNDEFIND = -1;


    /**
     * @var array 是否选中
     */
    public static $CHECKED = array(
        self::CHECKED_TRUE=>'是',
        self::CHECKED_FALSE=>'否',
        self::CHCKED_UNDEFIND=>''
    );


    const ITEM_TYPE_MAN	= '成人';


    /**
     * 发票类型
     **/
    const BILL_ADDPRO	= '1';
    const BILL_NORMAL	= '2';
    const BILL_PRO	= '3';

    static $BILL = array(
        self::BILL_ADDPRO => '增值税专用发票',
        self::BILL_NORMAL => '普通发票',
        self::BILL_PRO => '专业发票',
    );


    /**
     * 项目类型
     **/
    const TYPE_ORDER	= '1';
    const TYPE_GROUP	= '2';
    const TYPE_PROVIDER	= '3';
    const TYPE_AGENT	= '4';
    const TYPE_SERVICE	= '5';
    const TYPE_OTHER	= '100';

    static $TYPE = array(
        self::TYPE_ORDER => '提供商',
        self::TYPE_GROUP => '代理商',
        self::TYPE_PROVIDER => '批发商',
        self::TYPE_AGENT => '接待社',
    );


    /**
     * 支付方式
     **/
    const PAYTYPE_CHECK	= '1';
    const PAYTYPE_CASH	= '2';
    const PAYTYPE_REMIT	= '3';

    static $PAYTYPE= array(
        self::PAYTYPE_CHECK => '支票',
        self::PAYTYPE_CASH => '现金',
        self::PAYTYPE_REMIT => '汇款',
    );


    /**
     * 结算类型
     **/
    const FINAL_PROVIDER	= '1';
    const FINAL_GROUP	= '2';
    const FINAL_CHANNEL	= '3';
    const FINAL_AGENT	= '4';

    static $FINAL = array(
        self::FINAL_PROVIDER => '提供商',
        self::FINAL_GROUP => '组团社',
        self::FINAL_CHANNEL => '渠道商',
        self::FINAL_AGENT => '门市',
    );

    /**
     * 结算类型
     **/
    const FAQ_TOTYPE_PROVIDER	= '1';
    const FAQ_TOTYPE_GROUP	= '2';

    static $FAQ_TOTYPE = array(
        self::FAQ_TOTYPE_PROVIDER => '线路选购者',//线路选购者（有可能是组团社或代理商，对于上级都叫选购者
        self::FAQ_TOTYPE_GROUP => '线路操作者',//线路操作者（团控人员）
    );

	/**
	 * 
	 * 出境自由人
	 * @var OUTSIDE_BORDERS_FREE_PEOPLE
	 */
	const OUTSIDE_BORDERS_FREE_PEOPLE= '53be3f44dba57';

	/**
	 * 
	 * 出境跟团游
	 * @var OUTSIDE_TRAVEL_WITH_THE_GROUP
	 */
	const OUTSIDE_TRAVEL_WITH_THE_GROUP= '53be3cd281221';

	/**
	 * 
	 * 国内跟团游
	 * @var INTERNAL_TRAVEL_WITH_THE_GROUP
	 */
	const INTERNAL_TRAVEL_WITH_THE_GROUP = '535caecc00d7d';

	/**
	 * 
	 * 国内自由人
	 * @var INTERNAL_FREE_PEOPLE
	 */
	const INTERNAL_FREE_PEOPLE = '53be3e881964a';
	
	/**
	 * 
	 * 可选式自由人
	 * @var OPTIONAL_FREE_PEOPLE
	 */
	const OPTIONAL_FREE_PEOPLE = '53be3f56c303c';

	/**
	 * 
	 * 海岛游
	 * @var ISLAND_TOUR
	 */
	const ISLAND_TOUR = '53be3f6a27238';

	/**
	 * 
	 * 游轮游
	 * @var CRUISE_TOUR
	 */
	const CRUISE_TOUR = '53be3f71a504c';
	
	/**
	 * 
	 * 周边游
	 * @var TOUR_AROUND
	 */
	const TOUR_AROUND = '53be3f78aac48';

    /**
     * 文件下载URL
     */
    const FILE_DOWN_URL = '/webApi/downFile/fid/';
	
	/**
	 * 
	 * 产品类型
	 * @var array $PRODUCT_TYPE_ARRAY
	 */
	static $PRODUCT_TYPE_ARRAY = array(
		self::INTERNAL_TRAVEL_WITH_THE_GROUP => '国内跟团游',
		self::OUTSIDE_TRAVEL_WITH_THE_GROUP => '出境跟团游',
		self::INTERNAL_FREE_PEOPLE => '国内自由人',
		self::OUTSIDE_BORDERS_FREE_PEOPLE => '出境自由人',
	);

	static function isOutSide($type_id) {
		$isOutSite = false;
		if (!empty($type_id)) {
			if ($type_id == self::OUTSIDE_BORDERS_FREE_PEOPLE || $type_id == self::OUTSIDE_TRAVEL_WITH_THE_GROUP || $type_id == self::ISLAND_TOUR || $type_id==self::CRUISE_TOUR)
				$isOutSite = true;
		}
		return $isOutSite;
	}
	
	/**
	 * 根据团号查询
	 * @var SEARCH_GROUP_GID
	 */
	const SEARCH_GROUP_GID = 1;
	/**
	 * 
	 * 根据出团日期查询
	 * @var unknown_type
	 */
	const SEARCH_GROUP_DATE = 2;
	/**
	 * 
	 * 根据订单查询团
	 * @var unknown_type
	 */
	const SEARCH_GROUP_ORDER = 3;
	
	


    /**
     * 获取类型定义中的 index 或 key
     * @author wjh 2014-6-3
     * @param array $arr
     * @param string $text index or key
     * @return string
     */
    public static function getKey($arr,$text){
        return BArrayHelper::getKey($arr,$text);
    }

    /**
     * 获取类型定义的值（文本）内容
     * @author wjh 2014-6-3
     * @param array $arr
     * @param mixed $index index or key name
     * @return mixed
     */
    public static function getValue($arr,$index){
        return BArrayHelper::getValue($arr,$index);
    }


    /**
     * 根据值查找键值
     * @author wjh 2014-6-3
     * @param array $arr
     * @param mixed $value index or key name
     * @return mixed
     */
    public static function getKeyByValue($arr,$value){
        foreach ($arr as $ekey=>$evalue) {
            if ($evalue == $value)
                return $ekey;
        }
        return null;
    }


    /**
     * 移除枚举中的元素
     * @author wjh 2014-8-19
     * @param $arr
     * @param $items
     * @return array
     */
    public static function removeItems($arr,$items){
        return BArrayHelper::array_remove_keys($arr,$items);
    }


    //-----------------------------
    public static $rules_moble = array();

    /**
     * CRM用户组织
     */
    const C_ORGANIZATION = '553de396a344e';


    /**
     * 门市客户
     */
    const ORG_OTHRER = '客人';// '门市客户';


    /**
     *
     * 周边游
     * @var TOUR_AROUND
     */
    const ISDELETE_YES = 1;
    const ISDELETE_NO = 0;

    /**
     *
     * 产品类型
     * @var array $PRODUCT_TYPE_ARRAY
     */
    static $ISDELETE = array(
        self::ISDELETE_YES => '停用',
        self::ISDELETE_NO => '启用'
    );

    /**
     * 提供商管理组织ID
     */
    const ORG_PROVIDER_ORGID = 'c8lv4fql59lnv';

    /**
     * 神舟ORGID
     */
    const ORG_BTG_ORGID = '91CB80A2-FBFF-4C23-A862-B4246D9607AF';
    /**
     * 神舟ORG NAME
     */
    const ORG_BTG_ORGNAME = '神舟国旅';


    /**
     * 门市公司orgid
     */
    const ORG_MENSHI = '437c2430-aa06-4dcf-a9c9-a104711d2901';

    /**
     * 中旅orgid
     */
    const ORG_ZHONGLV ='ff672d5b-8231-49b5-8b8c-35b235d2543d';

    /**
     * 公民公司orgid
     */
    const ORG_GONGMIN ='54153e24783eb';

    /**
     * 同业 orgid
     */
    const ORG_TONGYE ='54153e9ad7c12';

    /**
     * 超时时间
     */
    const TIME_LIMIT=1800;

    /**
     * org 根目录,所有组织
     */
    const ORG_ROOT = '1';

    /**
     *提供商--国内
     */
    const INSIDE='1';

    /**
     * 提供商 --出境
     */
    const OUTSIDE='2';

    /**
     *提供商--出境+国内
     */
    const OUT_INSIDE='3';

    /**
     * 提供商类型
     * @var array
     */
    static $ProviderType=array(
        self::INSIDE=>'国内',
        self::OUTSIDE=>'出境',
        self::OUT_INSIDE=>'国内+出境',
    );

    /**
     * 隐藏的组织（C客户组织、CRM用户组织）
     * @var string
     */
    static $VisibleOrgid = "54efda85a9200','54d10108b40d0','553de396a344e','55a5dc6a0c1aa";

    /**
     * 隐藏的组织（C客户组织、CRM用户组织）
     * @var array
     */
    static $VisibleOrgidArray=array('54efda85a9200',//神舟C客户
        '54d10108b40d0',//马连道C客户
        '553de396a344e',//CRM
        '55a5dc6a0c1aa', //朝阳门C
    );

    /*
     * 通知公告栏目ID
     */
    static $noticeColumn='54f41d34317d9';

    /**
     * @author lvkui
     * @date 2015-04-02
     * User表对应的表名
     */
    const TABLE_NAME_USER='User';

    /**
     * @author lvkui
     * @date 2015-04-02
     * Organization表对应表名
     */
    const TABLE_NAME_ORG='Organization';

    /**
     * @author lvkui
     * @date 2015-04-03
     * Order 对应的表名
     */
    const TABLE_NAME_ORDER='Order';

    /**
     * @author lvkui
     * @date 2015-04-03
     * Group 对应的表名
     */
    const TABLE_NAME_GROUP='Group';

    /**
     * @author lvkui
     * @date 2015-04-06
     * hlt 状态为转换
     * @var array
     */
    static  $hlt_Status=array(
        'User'=>array(
            'status'=>array( //用户状态
                '0'=>'0', //正常
                '1'=>'1', //停用
            ),
            'sex'=>array( //用户性别
                self::SEX_MALE=>'0',
                self::SEX_FEMALE=>'1',
                self::SEX_UNDEFIND=>'2',
            ),
        ),
        'Organization'=>array(
            'status'=>array( //用户状态
                '0'=>'0', //正常
                '1'=>'1', //停用
            ),
            'auth'=>array( //加V
                Organization::VERIFY_NO=>'1', //未加
                Organization::VERIFY_YES=>'2', //已加
            ),
        ),
        'Order'=>array(
            'isoversold'=>array( //是否超售
                '0'=>'0',//正常
                '1'=>'1',//超售
            ),
            'islock'=>array( //订单锁定
                Order::LOCK_UNLOCK=>'0',//未锁定
                Order::LOCK_LOCKED=>'1',//锁定
            ),
            'payStatus'=>array( //订单支付状态
                Order::PAYSTATUS_NO=>'0', //未支付
                Order::PAYSTATUS_FINISH=>'1',//已支付
            ),
            'sex'=>array( //团员名单性别
                self::SEX_MALE=>'0',
                self::SEX_FEMALE=>'1',
                self::SEX_UNDEFIND=>'2',
            ),
            'certtype'=>array( //证件类型
                OrderMember::CERT_TYPE_IDCARD=>'1',
                OrderMember::CERT_TYPE_ARMY=>'2',
                OrderMember::CERT_TYPE_STD=>'3',
                OrderMember::CERT_TYPE_PASSPORT=>'4',
            ),
        ),
        'Group'=>array(

        )
    );

    const CHART_PIE='pie';
    const CHART_LINE_BAR='line_bar';

    static $Chart_Type=array(
        self::CHART_PIE=>'饼状图',
        self::CHART_LINE_BAR=>'线\柱图',
    );

    const CHART_GROUP='group';
    const CHART_ORG='org';
    static $Statistic_Type=array(
        self::CHART_GROUP=>'团类型',
        self::CHART_ORG=>'品牌',
    );
}