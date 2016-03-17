<?php
namespace api\services;

/**
 * Created by PhpStorm.
 * User: robin
 * Date: 2015/11/18
 * Time: 16:11
 */
class EcTools
{

    const CORP = '北京神舟国际旅行社集团有限公司';
    const CORPCODE = 'L-BJ-CJ00080';
    const CORPCODE2 = 'TBJCJ00080';
    const SCOPE = '旅行社、旅游酒店、商业、餐饮等';



    const VERCODE_GN = 1;
    const VERCODE_CJ = 2;
    const VERCODE_PT = 3;
    const VERCODE_ZYR = 4;

    /**
     * ec合同类型/vercode
     * @var array
     */
    public static $VERCODE = array(
        self::VERCODE_GN => '国内',
        self::VERCODE_CJ => '出境',
        self::VERCODE_PT => '赴台湾',
        self::VERCODE_ZYR => '自由人',
    );


    /**
     * erp合同类型->ec vercode
     * @var array
     */
    public  static  $ec_contract_type= array(
        TourContract::OUTSIDE_CONTRACT => 2,//'出境',
        TourContract::INSIDE_CONTRACT => 1,//'国内',
        TourContract::SINGLE_CONTRACT => 5,// '委托',
        TourContract::TAIWAN_CONTRACT => 3,//'台湾',
    );

    /**
     * erp证件类型 -> EC证件类型
     * 1-身份证，
     * 2-士官证，
     * 3-港澳通行证，
     * 4-护照，
     * 5-赴台证，
     * 6-回乡证，
     * 7-台胞证，
     * 8-其他
     * @var array
     */
    public static $EC_CERT_TYPE = array(
        OrderMember::CERT_TYPE_IDCARD => 1,// '身份证',
        OrderMember::CERT_TYPE_ARMY => 2,// '军官证',
        OrderMember::CERT_TYPE_HKPASS=>3,//港澳通行证
        OrderMember::CERT_TYPE_PASSPORT=>4,//护照
        OrderMember::CERT_TYPE_TWPASS => 5,// '赴台证'
        OrderMember::CERT_TYPE_MTPHKPASS=>6,//回乡证
        OrderMember::CERT_TYPE_MTPTWPASS=>7,//台胞证
        OrderMember::CERT_TYPE_OTHERPASS => 8,// 其他',

    );

    public static function getTravelerJson($ver_code, $order_arr)
    {
        /**
         * @var Order $order_arr
         * @var Group $group_arr
         * @var TourContract $ht_arr
         * @var OrderMember[] $members
         * @var OrderMember $signMember
         * @var User $orderUser
         */
        $group_arr = $order_arr->group;
        $signMember = $order_arr->ordermembers[0];
        $members = $order_arr->ordermembers;
        $orderUser = User::model()->findByPk($order_arr->order_userid);

        $data = array();
        if ($ver_code == 1) {//1:浙江省国内旅游合同
            $data = array(
                'traveler' => $signMember->cn_name,
                'addr' => ''
            );
        } elseif ($ver_code == 2) {//2:浙江省出境旅游合同
            $data = array(
                'traveler' => $signMember->cn_name,
                'addr' => ''
            );
        } elseif ($ver_code == 3) {//3:浙江省赴台湾地区旅游合同
            $data = array(
                'traveler' => $signMember->cn_name,
                'addr' => ''
            );
        } elseif ($ver_code == 4) {//4.自由行
            $data = array(
                'traveler' => $signMember->cn_name,
                'addr' => ''
            );
        }
        return self::getJsonData($data);
    }

    /**
     * 获取区域名称
     * @param $title
     * @return string
     */
    public static function getSortName($title){
        $sort = BCacheHelper::getSort($title);
        if ($sort && $sort['cn_name'])
            return $sort['cn_name'];
        else
            return '';
    }

    /**
     * 获取省份名称
     * @param $title
     * @return mixed
     */
    public static function getSortProvinceName($title){
        $sort = BCacheHelper::getSort($title);
        if ($sort['level'] > 4)
            return self::getSortProvinceName($sort['psid']);
        else
            return $sort['cn_name'];
    }

    /**
     * 获取国家名称
     * @param $title
     * @return mixed
     */
    public static function getSortCountryName($title){
        $sort = BCacheHelper::getSort($title);
        if ($sort['level'] > 2)
            return self::getSortCountryName($sort['psid']);
        else
            return $sort['cn_name'];
    }

    /**
     * 合同版本号
     * 1:国内旅游合同
     * 2:出境旅游合同
     * 3:赴台湾地区旅游合同
     * 4.自由人
     * @param $type
     * @return mixed
     */
    public static function getVerCode($type){
        return self::$ec_contract_type[$type];
    }

    public static function getSupplierJson($ver_code, $order_arr)
    {
        $group_arr = $order_arr->group;
        $signMember = $order_arr->ordermembers[0];
        $members = $order_arr->ordermembers;
        $orderUser = User::model()->findByPk($order_arr->order_userid);

        $data = array();
        return self::getJsonData($data);
    }

    public static function getJsonData($data){
        return json_encode($data);
    }

    public static function getGroupcorpJson($ver_code, $order_arr)
    {
        /**
         * @var Order $order_arr
         * @var Group $group_arr
         * @var TourContract $ht_arr
         * @var OrderMember[] $members
         * @var OrderMember $signMember
         * @var User $orderUser
         */
        $group_arr = $order_arr->group;
        $signMember = $order_arr->ordermembers[0];
        $members = $order_arr->ordermembers;
        $orderUser = User::model()->findByPk($order_arr->order_userid);

        $data = array(
            'corp' => self::CORP,//corp 旅行社 true
            'corpCode' => self::CORPCODE,//corpCode 旅行社业务经营许可证编号 true
            'scope' => self::SCOPE//scope 经营范围 true
        );

        return self::getJsonData($data);
    }

    public static function getLineJson($ver_code, $order_arr)
    {
        /**
         * @var Order $order_arr
         * @var Group $group_arr
         * @var TourContract $ht_arr
         * @var OrderMember[] $members
         * @var OrderMember $signMember
         * @var User $orderUser
         */
        $group_arr = $order_arr->group;
        $signMember = $order_arr->ordermembers[0];
        $members = $order_arr->ordermembers;
        $orderUser = User::model()->findByPk($order_arr->order_userid);

        $data = array(
            'linename' => $group_arr->title,// linename 线路名称 true
            'teamcode' => $group_arr->gid,//teamcode 团号 false
        );
        return self::getJsonData($data);
    }

    public static function getPayJson($ver_code, $order_arr,$extra_data)
    {
        /**
         * @var Order $order_arr
         * @var Group $group_arr
         * @var TourContract $ht_arr
         * @var OrderMember[] $members
         * @var OrderMember $signMember
         * @var User $orderUser
         * @var Stock $stock
         * @var GItem $g
         */
        $group_arr = $order_arr->group;
        $signMember = $order_arr->ordermembers[0];
        $members = $order_arr->ordermembers;
        $orderUser = User::model()->findByPk($order_arr->order_userid);

        //价格信息
        $qs = Quotation::model()->with('stock')->findAllByAttributes(array('chanid'=>$order_arr->chanid,'gid'=>$order_arr->gid));
        $data = array();
        foreach ($qs as $q) {
            $stock = $q->stock;
            $item = array(
                'gid' => $q->gid,
                'qid' => $q->qid,
                'saleprice' => $q->saleprice,
                'chanid' => $q->chanid,
                'sku_id' => $q->sku_id,
                'title' => $stock->title,
                'service_items' => $stock->service_items,
                'service_items_length' => count(explode(',', $stock->service_items)),
                'reffer_sku_id' => $stock->reffer_sku_id);

            if($stock->reffer_sku_id == '0')
                $item['adult'] = 1;

            $gs = GItem::model()->with('parent')->findAllByPk( explode(',',$stock->service_items));
            foreach ($gs as $g) {
                if ($g->parent->ctype == 'child' and $g->ctype == 'price'){
                    $item['child'] = 1;
                    break;
                }
            }
            $data[] = $item;
        }


        $data1 = BArrayHelper::array_func($data,null,function($k,$v){
            return isset($v['adult']) && $v['adult'] == 1;
        });

        $data2 = BArrayHelper::array_func($data,null,function($k,$v){
            return isset($v['child']) && $v['child'] == 1;
        },function($a,$b){
            if ($a['service_items_length'] == $b['service_items_length'])
                return 0;
            return ((int)$a['service_items_length'] < (int)$b['service_items_length']) ? -1 : 1;
        });
        $data2 = BArrayHelper::array_func_index($data2);
        $data_adult = $data1[0];
        $data_child='';
        if(!empty($data2)){
            $data_child = $data2[0];
        }


      /*  BDataHelper::print_r($data_adult);
        BDataHelper::print_r($data_child);
        die;*/

        $guideprice=GroupExtra::getExtraData($order_arr->parentgroup,GroupForm::ATTRIBUTENAME_GUIDE_PRICE);
        if(empty($guideprice)){
            $guideprice='0';
        }

        $data = array(//todo:
            'payEachAdult' => $data_adult['saleprice'],//payEachAdult 成人单人花费（元/人） true //todo:
            'payEachChild' => empty($data_child) ? '' : $data_child['saleprice'],//payEachChild 儿童单人花费（元/人） false
            'payTravel' => $extra_data['price'],//payTravel 合计花费 true
            'payGuide' =>$guideprice,//payGuide 导游服务费 true
            'payDeadline' => $extra_data['paydeadline'],//payDeadline 旅游费用交纳期限 true
            'payType' => $extra_data['payment'],//payType  旅游费用交纳方式【1：现金 2：支票 3：信用卡 4：其他】 true
            'payOther' => '其它',//payOther 旅游费用其他支付方式 true
        );
        return self::getJsonData($data);
    }

    public static function getEcCno($cno){
        return self::CORPCODE2 . $cno;
    }

    /**
     * 生成签名
     * @param $cno
     * @return string
     * @throws Exception
     */
    public static function getSign($cno){
        $file = __DIR__. "/sign_wjh.png";
        if($fp = fopen($file,"rb", 0))
        {
            $gambar = fread($fp,filesize($file));
            fclose($fp);
            $base64 = chunk_split(base64_encode($gambar));
            return $base64;
        }
        else
            throw new Exception('sign 生成失败');
    }

    /**
     * 生成签名图片
     * @param $cno
     * @throws Exception
     */
    public static function getSignImage($cno){
        //Filetype: JPEG,PNG,GIF
        $file = __DIR__. "/sign_wjh.png";
        if($fp = fopen($file,"rb", 0))
        {
            $gambar = fread($fp,filesize($file));
            fclose($fp);

            $base64 = chunk_split(base64_encode($gambar));
            $encode = '<img src="data:image/jpg/png/gif;base64,' . $base64 .'" >';
            echo $encode;
        }
        else
            throw new Exception('sign 生成图片失败');
    }

    /**
     * 生成签名图片
     * @param $cno
     * @throws Exception
     */
    public static function getSignImageUrl($cno){
        //Filetype: JPEG,PNG,GIF
        $file = __DIR__. "/sign_wjh.png";
        if($fp = fopen($file,"rb", 0))
        {
            $gambar = fread($fp,filesize($file));
            fclose($fp);

            $base64 = chunk_split(base64_encode($gambar));
            $encode = 'data:image/jpg/png/gif;base64,' . $base64;
            echo $encode;
        }
        else
            throw new Exception('sign 生成图片失败');
    }

    /**
     * get TourContract
     * @param $cno
     * @return TourContract
     * @throws Exception
     */
    public static function getTourContract($cno){
        if(empty($cno))
            throw new Exception('请输入合同号');
        $tc = TourContract::model()->findByAttributes(array('contr_no' => $cno));
        if(empty($tc))
            throw new Exception('合同号不存在');
        return $tc;
    }

    /**
     * get TourContract
     * @param $oid
     * @return TourContract
     * @throws Exception
     */
    public static function getTourContractByOid($oid){
        if(empty($oid))
            throw new Exception('请输入订单号');
        $tc = TourContract::model()->findByAttributes(array('oid' => $oid));
        if(empty($tc))
            throw new Exception('合同不存在');
        return $tc;
    }


    /**
     * 获取合同签名代表信息
     * @param $cno
     * @return OrderMember
     * @throws Exception
     */
    public static function getSignMember($cno){
        if(empty($cno))
            throw new Exception('请输入合同号');
        $tc = TourContract::model()->findByAttributes(array('contr_no' => $cno));
        if(empty($tc))
            throw new Exception('合同号不存在');
        //2
        $members = OrderMember::model()->findAllByAttributes(array('oid'=>$tc->oid));
        if(empty($members))
            throw new Exception('不存在团员名单');
        return $members[0];
    }

    public static function getInsuranceJson($ver_code, $order_arr,$extra_data)
    {
        /**
         * @var Order $order_arr
         * @var Group $group_arr
         * @var TourContract $ht_arr
         * @var OrderMember[] $members
         * @var OrderMember $signMember
         * @var User $orderUser
         */
        $group_arr = $order_arr->group;
        $signMember = $order_arr->ordermembers[0];
        $members = $order_arr->ordermembers;
        $orderUser = User::model()->findByPk($order_arr->order_userid);

        $data = array(//todo:
            'agree' => $extra_data['purchases'],//agree 旅游者【1：委托旅行社购买 2：自行购买 3：放弃购买】 true
            'product' => $extra_data['insurance'],//product 保险产品名称 true
        );
        return self::getJsonData($data);
    }

    public static function getGroupJson($ver_code, $order_arr)
    {
        /**
         * @var Order $order_arr
         * @var Group $group_arr
         * @var TourContract $ht_arr
         * @var OrderMember[] $members
         * @var OrderMember $signMember
         * @var User $orderUser
         */
        $group_arr = $order_arr->group;
        $signMember = $order_arr->ordermembers[0];
        $members = $order_arr->ordermembers;
        $orderUser = User::model()->findByPk($order_arr->order_userid);


        $minnum=GroupExtra::getExtraData($order_arr->parentgroup,GroupForm::ATTRIBUTENAME_MINNUM);
        if( empty($minnum)){
            $minnum='1';
        }

        if ($ver_code == 1 || $ver_code == 2) {//1:国内旅游合同 + 出境旅游合同
            $data = array(
                'personLimit' => $minnum,//personLimit 最低成团人数 true
                'transAgree' => '0',//transAgree 旅行者__旅行社【0：不同意 1：同意】 true
                'transAgency' => '',//transAgency 旅行社委托__社履行合同【旅行社名称】 false
                'delayAgree' => '0',//delayAgree 旅行者__延期出团【0：不同意 1：同意】 true
                'changeLineAgree' => '0',//changeLineAgree 旅行者__改签其他线路出团【0：不同意 1：同意】 true
                'terminateAgree' => '1',//terminateAgree 旅行者__解除合同【0：不同意 1：同意】 true
                'mergeAgree' => '1',//mergeAgree 旅行者__采用拼团方式【0：不同意 1：同意】 true
                'mergeAgency' => $order_arr->org->name,//mergeAgency 拼团拼至__出境社成团 false
                'teminateDealType' => '2',//teminateDealType 协商或者调解不成的，按第几种方式处理【1：提交仲裁委员会仲裁；2：依法向人民法院起诉】 true
                'committee' => '',//committee 仲裁委员会 false
            );
        } elseif ($ver_code == 3) {//3:赴台湾地区旅游合同
            $data = array(
                'personLimit' => $minnum,//personLimit 最低成团人数 true
                'delayAgree' => '0',//delayAgree 旅行者__延期出团【0：不同意 1：同意】 true
                'changeLineAgree' => '0',//changeLineAgree 旅行者__改签其他线路出团【0：不同意 1：同意】 true
                'terminateAgree' => '1',//terminateAgree 旅行者__解除合同【0：不同意 1：同意】 true
                'teminateDealType' => '2',//teminateDealType 协商或者调解不成的，按第几种方式处理【1：提交仲裁委员会仲裁；2：依法向人民法院起诉】 true
                'committee' => '',//committee 仲裁委员会 false
            );
        } elseif ($ver_code == 4) {//4.自由行
            $data = array(

            );
        }
        return self::getJsonData($data);
    }

    public static function getGoldenWeekJson($ver_code, $order_arr)
    {
        /**
         * @var Order $order_arr
         * @var Group $group_arr
         * @var TourContract $ht_arr
         * @var OrderMember[] $members
         * @var OrderMember $signMember
         * @var User $orderUser
         */
        $group_arr = $order_arr->group;
        $signMember = $order_arr->ordermembers[0];
        $members = $order_arr->ordermembers;
        $orderUser = User::model()->findByPk($order_arr->order_userid);

        $data = array(
        );
        return self::getJsonData($data);
    }

    public static function getControversyJson($ver_code, $order_arr)
    {
        /**
         * @var Order $order_arr
         * @var Group $group_arr
         * @var TourContract $ht_arr
         * @var OrderMember[] $members
         * @var OrderMember $signMember
         * @var User $orderUser
         */
        $group_arr = $order_arr->group;
        $signMember = $order_arr->ordermembers[0];
        $members = $order_arr->ordermembers;
        $orderUser = User::model()->findByPk($order_arr->order_userid);

        $data = array(
        );
        return self::getJsonData($data);
    }

    public static function getOtherJson($ver_code, $order_arr,$extra_data)
    {
        /**
         * @var Order $order_arr
         * @var Group $group_arr
         * @var TourContract $ht_arr
         * @var OrderMember[] $members
         * @var OrderMember $signMember
         * @var User $orderUser
         */
        $group_arr = $order_arr->group;
        $signMember = $order_arr->ordermembers[0];
        $members = $order_arr->ordermembers;
        $orderUser = User::model()->findByPk($order_arr->order_userid);

        $arr_text=TourContractForm::getTextByOrder($order_arr);
        if(!empty($extra_data['other_assumpsit'])){
            $arr_text['other']=$extra_data['other_assumpsit'];
        }

        $supplementaryClause= implode($arr_text);
        /*$supplementaryClause=strip_tags($supplementaryClause,'<p>');*/

        $data = array(
            'supplementaryClause' => $supplementaryClause,//supplementaryClause 其他约定事项 false
            'copys1' => '2',//copys1 合同一式__份 true
            'copys2' => '1',//copys2 双方各持__份 true
            'agencyComplaintsMobile' =>'4000100808',//agencyComplaintsMobile 出境社监督、投诉电话 false
            'lawState' =>'北京',//lawState 旅游质监执法机构 省 false
            'lawCity' =>'北京',//lawCity 旅游质监执法机构 市 false
            'lawComplaintsMobile' =>'12301',//lawComplaintsMobile 旅游质监执法机构 投诉电话 false
            'lawEmail' =>'',//lawEmail 旅游质监执法机构 电子邮箱 false
            'lawAddress' =>'朝阳区建外大街28号北京旅游大厦',//lawAddress 旅游质监执法机构 地址 false
            'lawZip' =>'100022',//lawZip 旅游质监执法机构 邮编 false
        );
        return self::getJsonData($data);
    }
}