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

/**
 * CHtml is a static class that provides a collection of helper methods for creating HTML views.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.web.helpers
 * @since 1.0
 */
abstract class BaseDataHelper {

	/**
	 *
	 * @var UNDELETED 未删除
	 */
	const UNDELETED = 0;

	/**
	 *
	 * @var DELETED 已删除
	 */
	const DELETED = 1;

	const ID_PREFIX = 'yt';

	const VARCHAR = 'S';

	const INT = 'I';

	const TEXT_AREA = 'T';

	static $ARRAY_DATA_TYPE = array(self::VARCHAR => 'text', self::INT => 'text', self::TEXT_AREA => 'textarea');
	/**
	 * @var string the CSS class for displaying error summaries (see {@link errorSummary}).
	 */
	public static $errorSummaryCss = 'errorSummary';

	/**
	 * @var string the HTML code to be appended to the required label.
	 * @see label
	 */
	public static $afterRequiredLabel = ' <span class="required">*</span>';
	/**
	 * @var integer the counter for generating automatic input field names.
	 */
	public static $count = 0;

	public static $liveEvents = true;
	/**
	 * @var boolean whether to close single tags. Defaults to true. Can be set to false for HTML5.
	 * @since 1.1.13
	 */
	public static $closeSingleTags = true;
	/**
	 * @var boolean whether to render special attributes value. Defaults to true. Can be set to false for HTML5.
	 * @since 1.1.13
	 */
	public static $renderSpecialAttributesValue = true;
	/**
	 * @var callback the generator used in the {@link CHtml::modelName()} method.
	 * @since 1.1.14
	 */
	private static $_modelNameConverter;

	//public static $userid=1;


    private $data = array();


    function __get($name)
    {
        if ($name === 'userid') {
            return Yii::app()->session['__userid'];
        }
        else{
            return $this->data[$name];
        }
    }


    function __set($name, $value)
    {
        if ($name === 'userid') {
            Yii::app()->session['__userid'] = $value;
        }
        else{
            $this->data[$name] =$value;
        }
    }

    /**
     * 返回默认团员中未设置人员签名
     * @author wjh 2014-6-4
     * @return string the member sign
     */
    public static function getDefaultMemberSign() {
        $member = OrderMember::model()->findByPk('0');
        return $member->memsign;
    }


	/**
	 * 返回性别字符串显示
	 * The {@link CApplication::charset application charset} will be used for encoding.
	 * $sex string $text data to be encoded
	 * @return string the string data
	 * @see http://www.php.net/manual/en/function.htmlspecialchars.php
	 */
	public static function getSexName($sex) {
		return BArrayHelper::getValue(BDefind::$SEX,$sex);
	}

	/**
	 * 返回性别字符串显示
	 * The {@link CApplication::charset application charset} will be used for encoding.
	 * $sex string $text data to be encoded
	 * @return string the string data
	 * @see http://www.php.net/manual/en/function.htmlspecialchars.php
	 */
	public static function getSexValue($sexname) {
		return BDefind::getKey(BDefind::$SEX,$sexname);
	}

	/**
	 * Renders a array for sex.
	 * @author wjh
	 * @version 2014-3-31
	 * @return array 性别数组
	 */
	public static function getSexArray() {
		return BDefind::$SEX;
	}


	/**
	 * 返回可为空的字符串
	 * The {@link CApplication::charset application charset} will be used for encoding.
	 * $obj1 string $text data to be encoded
	 * $obj2 string $text data to be encoded
	 * @return string the string data
	 * @see http://www.php.net/manual/en/function.htmlspecialchars.php
	 */
	public static function getNullableString($obj1, $obj2) {
		if (isset($obj1) && $obj1 != null) {
			return (string) $obj1;
		} elseif (isset($obj2) && $obj2 != null) {
			return (string) obj2;
		} else {
			return '';
		}
	}

	/**
	 * 返回签名
	 * The {@link CApplication::charset application charset} will be used for encoding.
	 * $obj1 string $text data to be encoded
	 * $obj2 string $text data to be encoded
	 * @return string the string data
	 * @see http://www.php.net/manual/en/function.htmlspecialchars.php
	 */
	public static function getSign($text) {
		return sha1($text);
	}

	/**
	 * 返回文件签名
	 * @param string $filename filename
	 * @return string the string data
	 */
	public static function getFileSign($filename) {
		return sha1_file($filename);
	}

	/**
	 * 返回多个文件下载地址
	 * @author wjh
	 * @version 2014-4-29
	 * @param array $files fileid array
	 * @return array the file url array
	 */
	public static function getFileUrls($files) {
		if (!is_array($files)) {
			throw Exception('$files is array required');
		}

		$urls = array();
		foreach ($files as $id) {
			$url = Yii::app()->params['fileViewUrl'] . "/id/$id";
			$fileurl = BNetHelper::httpRequest($url);
			array_push($urls, $fileurl);
		}

		return $urls;
	}

	/**
	 * 返回一个文件下载地址
	 * @author wjh
	 * @version 2014-4-29
	 * @param string $fileid fileid
	 * @return string the file url
	 */
	public static function getFileUrl($fileid) {
		if (empty($fileid)) {
			throw Exception('$fileid is required');
		}

		$fileid = trim($fileid);
		$url = Yii::app()->params['fileViewUrl'] . "/id/$fileid";
		return BNetHelper::httpRequest($url);
	}

	/**
	 * 返回一个文件下载地址
	 * @author wjh
	 * @version 2014-4-29
	 * @param string $fileid fileid
	 * @return string the file url
	 */
	public static function getFileInfo($fileid) {
		if (empty($fileid)) {
			throw Exception('$fileid is required');
		}

		$fileid = trim($fileid);
		$url = Yii::app()->params['fileViewUrl'] . "/id/$fileid";
		return CJSON::decode(BNetHelper::httpRequest($url));
	}

	/**
	 * 返回数据库表ID；<br>
	 * 该ID不重复
	 *
	 */
	public static function getId() {
		return uniqid();
	}

	public static function getSku_id() {
		return self::random(15);
	}

	function create_guid() {
		$charid = strtoupper(md5(uniqid(mt_rand(), true)));
		$hyphen = chr(45);// "-"
		$uuid = chr(123)// "{"
 . substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12) . chr(125);// "}"
		return $uuid;
	}


    //$result=random(10);//生成10位随机数
    //$result=random(10, '0123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');//生成10位字母数字混合字符串
    //echo "<input type='text' size='20' value='{$result}'>";
    /**
     * 产生随机字符串
     *
     * @param    int $length 输出长度
     * @param    string $chars 可选的 ，默认为 0123456789
     * @return   string     字符串
     */
    public static function  random($length, $chars = '0123456789abcdefghijklmnpqrstuvwxyz')
    {
        $hash = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }

	//$result=random(10);//生成10位随机数
	//$result=random(10, '0123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');//生成10位字母数字混合字符串
	//echo "<input type='text' size='20' value='{$result}'>";
	/**
	 * 产生随机字符串
	 *
	 * @param    int $length 输出长度
	 * @param    string $chars 可选的 ，默认为 0123456789
	 * @return   string     字符串
	 */
	public static function  randomNum($length, $chars = '0123456789')
	{
		$hash = '';
		$max = strlen($chars) - 1;
		for ($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
		return $hash;
	}


	/*
	 * 获取当前时间
	 */
	public static function getCurrentTime($format = 'Y-m-d H:i:s') {
		date_default_timezone_set('PRC');
		return date($format, time());
	}

	/*
 * 获取当前时间
 */
	public static function getSignTime($date,$format = 'Y年m月d日') {
		date_default_timezone_set('PRC');
		return date($format,strtotime($date));
	}
	/*
 * 获取当前时间
 */
	public static function getCurrentDate($format = 'Y-m-d') {
		date_default_timezone_set('PRC');
		return date($format, time());
	}

    /*
 * 获取当前时间,包含毫秒
 */
    public static function getCurrentTimeX($format = 'Y-m-d H:i:s x') {
        date_default_timezone_set('PRC');
        return date($format, time());
    }


    /*
 * 获取当前时间
 */
    public static function getWeek($dates) {
       if(empty($dates)){
           return '';
       }

        $data = explode(',',$dates);
        $arr = array();
        $data = BArrayHelper::removeEmpty($data);
        array_walk($data,function($v,$k){
            $arr[] = self::getw($v);
        });
        return print_r($data);
    }

    public function getw($date){
        $weekarray=array("日","一","二","三","四","五","六");
        return $weekarray[date("w",$date)];
    }



    /*
     * 加密金额数据
     * @author wjh
     * @ver 2014-5-27
     */
    public static function enBalance($deValue) {
        return sprintf('--%s--',$deValue);
    }


	/**
	 * 获取日期和时间
	 * @param string $str 时间字符串
	 * @author wjh
	 * @version 2014-4-26
	 */
	public static function getDateTime($dt) {
		return date('Y-m-d H:i:s', strtotime($dt));
		;
	}

	/**
	 * 获取日期
	 * @param string $str 时间字符串
	 * @author wjh
	 * @version 2014-4-26
	 */
	public static function getDate($dt) {
        if (empty($dt))
            return null;

		return date('Y-m-d', strtotime($dt));
	}


    /**
     * 获取日期
     * @param string $str 时间字符串
     * @author wjh
     * @version 2014-4-26
     */
    public static function getDateSchedule($dt) {
        if (empty($dt))
            return null;

        return date('Y-m-d H:i', strtotime($dt));
    }
	
	/**
	 * 获取日期2,月份，日为单数
	 * @param string $str 时间字符串
	 * @author wjh
	 * @version 2014-4-26
	 */
	public static function getDateSimple($dt) {
		return date('Y-n-j', strtotime($dt));
		;
	}
	
	/**
	 * 获取当前日期月份
	 * @author wangjingzhi
	 * @version Sep 5, 2014 2:16:33 PM
	 * @param string $dt
	 * @return string month
	 */
	public static function getDateMonth($dt) {
		return date('n', strtotime($dt));
	}
	
	/**
	 * 获取当前日期年份
	 * @author wangjingzhi
	 * @version Sep 5, 2014 2:16:33 PM
	 * @param string $dt
	 * @return string Year
	 */
	public static function getDateYear($dt) {
		return date('Y', strtotime($dt));
	}

    /**
     * 获取日期天部分
     * @param string $str 时间字符串
     * @author wjh
     * @version 2014-4-26
     */
    public static function getDay($dt) {
        return date('j', strtotime($dt));
    }

    /**
     * 获取日期天部分
     * @param string $str 时间字符串
     * @author wjh
     * @version 2014-4-26
     */
    public static function getMonth($dt) {
        return date('n', strtotime($dt));
    }

	/**
	 * 获取日期年份
	 * @param string $str 时间字符串
	 * @author wjh
	 * @version 2014-4-26
	 */
	public static function getYear($dt = null) {
		$dt = empty($dt) ? BDataHelper::getCurrentTime() : $dt;
		return date('Y', strtotime($dt));
		;
	}

	/**
	 * 获取时间
	 * @param string $str 时间字符串
	 * @author wjh
	 * @version 2014-4-26
	 */
	public static function getTime($dt) {
		return date('H:i:s', strtotime($dt));
		;
	}

	/**
	 * 获取当前日期时间戳
	 * @author wangjingzhi
	 * @version Jul 24, 2014 11:59:17 AM
	 * @param string $date
	 * @return integer timestamp
	 */
	public static function getTimestamp($date) {
		return strtotime($date);
	}
	
	/**
	 * 获取两个日期相差时间戳
	 * @author wangjingzhi E-mail:wangjingzhiqr@163.com
	 * @version Jul 24, 2014 12:09:29 PM
	 * @param string $startDate
	 * @param string $endDate
	 * @return integer timestamp
	 * @example getTimestampDifference('2014-7-14','2014-7-15')
	 */
	public static function getTimestampDifference($startDate,$endDate) {
		return (BDataHelperBase::getTimestamp($endDate) - BDataHelperBase::getTimestamp($startDate));
	}


    /**
     * Init Model
     * @author wjh 2014-5-28
     * @param mixed $model string is model name ,CActiveRecord is model instance
     * @param array $attributes
     * @return CActiveRecord
     */
    public static function initModel($model,$attributes) {
        if (is_string($model)) {
            $model = BReflectHelper::getInstanceByReflect($model);
        }

        if (!$model instanceof CActiveRecord) {
            throw new Exception('$model not is CActiveRecord');
        }

        $model->attributes = array();
        foreach ($model->attributes as $key=>$value) {
            $model->$key=null;
        }

        //unset($model->attributes);
        $model->attributes = $attributes;

        return $model;
    }

	/**
	 * 动态获取实体属性值
	 * @example $rolename = BDataHelper::getModelProperty($role,'rolename');
	 * @example $createusername = BDataHelper::getModelProperty($role,'createuser','name');
	 * @author wjh 2014-3-17
	 * @param model $object model
	 * @param string $property 属性
	 * @param string $subproperty 子属性
	 * @return mixed|string 属性值
	 */
	public static function getModelProperty($object, $property, $subproperty = NULL) {

		try {
			//方式1
			$reflect = new ReflectionObject($object);//$role
			$method = $reflect->getMethod("__get");
			$pvalue = $method->invoke($object, $property);//createuser
			if (!isset($pvalue) || $pvalue == null) {
				return null;
			}

			if ($subproperty == NULL) {
				return $pvalue;
			}

			$reflect2 = new ReflectionObject($pvalue);
			$method2 = $reflect2->getMethod("__get");
			$subvalue = $method2->invoke($pvalue, $subproperty);//name

			return $subvalue;

		} catch (Exception $e) {
			return null;
		}
	}

	/**
	 * 动态获取对象属性值
	 * @example BDataHelper::getObjectProperty($robj, 'str');
	 * @example BDataHelper::getObjectProperty($robj, 'kv','value');
	 * @author wjh 2014-3-17
	 * @param model $object model
	 * @param string $property 属性
	 * @param string $subproperty 子属性
	 * @return mixed|string 属性值
	 */
	public static function getObjectProperty($object, $property, $subproperty = NULL) {

		try {

			$reflect = new ReflectionClass($object);//$role
			$properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_STATIC);
			foreach ($properties as $property) {
				//echo $property->getName()."\n";
			}
			/* var_dump($reflect);
			die(); */

			$property1 = $reflect->getProperty($property);
			$pvalue = $property1->getValue($object);
			if (!isset($pvalue) || $pvalue == null) {
				return null;
			}

			if ($subproperty == NULL) {
				return $pvalue;
			}

			$reflect2 = new ReflectionObject($pvalue);
			$property2 = $reflect2->getProperty($subproperty);
			$subvalue = $property2->getValue($pvalue);

			return $subvalue;

		} catch (Exception $e) {
			return null;
		}
	}

	/**
	 * 获取Request对象属性值
	 * @author wjh 2014-3-31
	 * @param string $property 属性
	 * @param string $subproperty 子属性
	 * @return mixed|string 属性值
	 */
	public static function getRequestProperty($property, $subproperty = NULL) {

		try {
			if (isset($_REQUEST[$property])) {
				if ($subproperty == null) {
					return $_REQUEST[$property];
				} else {
					if (isset($_REQUEST[$property][$subproperty])) {
						return $_REQUEST[$property][$subproperty];
					}
				}
			}

			return null;

		} catch (Exception $e) {
			return null;
		}
	}

	/**
	 * Renders listData for a listData config.
	 * @author wjh	2014-4-1
	 * @version 2014-4-1
	 * @example getListData(array(
	                            'model'=>'Dept',
	                            'valueField'=>'deptid',
	                            'textField'=>'deptname',
	                            'condition'=>'isdelete=1',
	                            'sort'=>'deptid asc'))
	 * @param array $config the config array
	 * @return listData the generated listData
	 */
	public static function getListData($config) {
		$dataProvider = new CActiveDataProvider($config['model'], array('criteria' => array('select' => '*', 
		//'with'=>''
		'condition' => isset($config['condition']) ? $config['condition'] : '',
		//'params'=>array(':wfid'=>$id),
		), 'sort' => array('defaultOrder' => isset($config['sort']) ? $config['sort'] : '',), 'pagination' => array('pageSize' => 100),));

		$sourcedata = $dataProvider->data;
		$data = CHtml::listData($sourcedata, $config['valueField'], $config['textField']);

		return $data;
	}

	/**
	 * Renders listData for a listData config.
	 * @author wjh	2014-4-1
	 * @version 2014-4-1
	 * @example getActiveData(array(
	                                 'model'=>'Dept',
	                                 'condition'=>'isdelete=1',
	                                 'sort'=>'deptid asc'))
	 * @param array $config the config array
	 * @return array the generated listData
	 */
	public static function getActiveData($config) {
		$dataProvider = new CActiveDataProvider($config['model'], array('criteria' => array('select' => isset($config['select']) ? $config['select'] : '*', 
		//'with'=>''
		'condition' => isset($config['condition']) ? $config['condition'] : '',
		//'params'=>array(':wfid'=>$id),
		), 'sort' => array('defaultOrder' => isset($config['sort']) ? $config['sort'] : '',), 'pagination' => array('pageSize' => 100),));

		$sourcedata = $dataProvider->data;

		return $sourcedata;
	}

	/**
	 * 取得App相对目录
	 * @author wjh
	 * @version 2014-4-24
	 * @param string $dir
	 * @return string
	 */
	public static function getAppUrl($url) {
		if (!isset($url)) {
			return '';
		}

		return sprintf('%s%s', Yii::app()->baseUrl, $url);
	}

	/**
	 * 获取模型所有值
	 * @author wjh
	 * @version 2014-4-30
	 * @param CActiveRecord $model model
	 * @param array $escape escape name array
	 * @return array
	 */
	public static function getAttributes($model, $escape) {
		$columns = $model->attributes;
		$relations = $model->metaData->relations;

		if (isset($escape) && $escape != null) {
			$escape = array_combine($escape, $escape);
			$relations = array_diff_key($relations, $escape);
		}

		foreach ($relations as $key => $relation) {
			$pvalue = BDataHelper::getModelProperty($model, $relation->name);
			$columns[$relation->name] = $pvalue;
		}

		return $columns;
	}

	/**
	 * 获取关联模型及其值
	 * @author wjh
	 * @version 2014-4-30
	 * @param CActiveRecord $model model
	 * @param array $escape escape name array
	 * @return array
	 */
	public static function getRelations($model, $escape) {
		$columns = array();
		$relations = $model->metaData->relations;

		if (isset($escape) && $escape != null) {
			$escape = array_combine($escape, $escape);
			$relations = array_diff_key($relations, $escape);
		}

		foreach ($relations as $key => $relation) {
			$pvalue = BDataHelper::getModelProperty($model, $relation->name);
			$columns[$relation->name] = $pvalue;
		}

		return $columns;
	}


    /**
     * 获取关联模型及其值(已经设置了值的部分，不进行数据库查询)
     * @author wjh
     * @version 2014-10-8
     * @param CActiveRecord $model model
     * @param array $escape escape name array
     * @return array
     */
    public static function getValuableRelations($model, $escape=array()) {
        $columns = array();
        $releations = BDataHelper::getRelationSchemas($model,$escape);
        foreach ($releations as $rkey => $rvalue) {

            $mvalue = $rvalue;
            $rvalue = $model->hasRelated($rkey) ? $model->$rkey : null;
            if (is_null($rvalue)) {
                continue;
            }

            $columns[$rkey] = $rvalue;
        }

        return $columns;
    }

	/**
	 * 获取关联模型
	 * @author wjh
	 * @version 2014-4-30
	 * @param CActiveRecord $model model
	 * @param array $escape escape name array
	 * @return array
	 */
	public static function getRelationSchemas($model, $escape) {
		$relations = $model->metaData->relations;
		if (isset($escape) && $escape != null) {
			$escape = array_combine($escape, $escape);
			$relations = array_diff_key($relations, $escape);
		}
		return $relations;
	}

	/**
	 * 获取数组中值
	 * @author wjh
	 * @version 2014-4-30
	 * @param array $arr array
	 * @param string $key1 key1
	 * @param string $key2 key2
	 * @param string $key3 key3
	 * @return array
	 */
	public static function getArrayValue($arr, $key1, $key2 = null, $key3 = null) {
		if (!empty($key1) && empty($key2)) {
			return isset($arr[$key1]) ? $arr[$key1] : null;
		} elseif (!empty($key1) && !empty($key2) && empty($key3)) {
			return isset($arr[$key1][$key2]) ? $arr[$key1][$key2] : null;
		} else {
			return isset($arr[$key1][$key2][$key3]) ? $arr[$key1][$key2][$key3] : null;
		}
	}

    /**
     * 组合返回JSON数据格式
     * @author wjh
     * @version 2014-5-10
     * @param mixed $data string text or array
     * @param string $err error message
     * @param null $type 返回类型，json | jsonp
     * @param null $jsonpfunc 如果 $type= jsonp ，用来指定回调函数
     * @throws Exception
     * @return array
     */
	public static function encodeJsonReturn($data, $err = null,$type='json',$jsonpfunc='jsonpCallback') {
        if(is_null($data)){
            $data= array();
        }
        elseif (BArrayHelper::isCActiveRecord($data)) {
            $data= $data->attributes;
        }
        elseif(BArrayHelper::isCActiveRecordArray($data)) {
            $data= BArrayHelper::getModelAttributesArray($data);
        }
        else{
            //$data = array();
        }

        if (is_array($data)) {
			$data = SnapshotHelper::encodeArray($data);
		}

        if(!empty($type) && $type=='jsonp'){
            $str = sprintf('%s({"err":[%s],"data":%s})',$jsonpfunc,$err,$data);
        }
        else{
            $str = sprintf('{"err":[%s],"data":%s}', $err, $data);
        }
        return $str;
	}


    /**
     * 对 Controller 中此方法重复定义
     * @author wjh 20141123
     * @param $err
     * @param $data
     * @param string $type
     * @return string
     */
    public static function getAjaxResponse($err,$data,$type='json'){
        $responseArr = array('err'=>$err,'data'=>$data);
        switch($type){
            case 'json':
                return CJSON::encode($responseArr);
            case 'html':
                $returnHtml = "<div class='alert in alert-block fade alert-warning'>";
                if(!empty($err)){
                    foreach($err as $label => $message){
                        $returnHtml .= "<strong>$label:</strong>$message[0]<br>";
                    }
                }
                $returnHtml .= "</div>";
                return $returnHtml;
            default:
                return CJSON::encode($responseArr);
        }

    }

	/**
	 * 在输出中添加UTF8 Header,解决中文乱码问题
	 * @author wjh
	 * @version 2014-4-30
	 * @return header
	 */
	public static function addHeaderUTF8() {
		header("Content-Type:text/html;charset=utf-8");
	}

	/**
	 * 在输出中添加<pre>
	 * @author wjh
	 * @version 2014-4-30
	 * @return header
	 */
	public static function addPreStart() {
		print "<pre>";
	}

	/**
	 * 在输出中添加</pre>
	 * @author wjh
	 * @version 2014-4-30
	 * @return header
	 */
	public static function addPreEdn() {
		print "</pre>";
	}

	/**
	 * 在输出中添加<br>
	 * @author wjh
	 * @version 2014-4-30
	 * @return header
	 */
	public static function addBr() {
		print "<br>";
	}


    public static function SumDataProvider($dataProvider,$key) {
       if(is_null($dataProvider) || !$dataProvider instanceof CActiveDataProvider){
           return -1;
       }
        $sum = 0;
        $data =  $dataProvider->getData();
        foreach ($data as $dkey=>$dvalue) {
            $sum += $dvalue[$key];
        }
        return $sum;
    }

	/**
	 * 获取对象编号
	 * @author wjh
	 * @version 2014-5-12
	 * @return string char(11)
	 */
	public static function getModelNumber($model, $pkid) {
		$class = BReflectHelper::getClassByReflect($model);
		return sprintf('%s%s', BDataHelper::getYear(), rand(1000000, 9999999));
	}

    /**
     * 获取对象编号
     * @author wjh
     * @version 2014-5-12
     * @return string char(11)
     */
    public static function getModelNumber15($model, $pkid) {
        $class = BReflectHelper::getClassByReflect($model);
        return sprintf('%s%s', BDataHelper::getYear(), rand(10000000000, 99999999999));
    }

	/**
	 * 获取gid
	 * @author wjh
	 * @version 2014-5-12
	 * @return string char(11)
	 */
	public static function getGid() {
		return self::getModelNumber('Group', 'gid');
	}

    /**
     * 获取oid
     * @author wjh
     * @version 2014-5-30
     * @return string char(11)
     */
    public static function getOid() {
        return self::getModelNumber15('Group', 'gid');
    }


    /**
     * 获取验证码
     * @author wjh
     * @version 2014-5-30
     * @return string char(11)
     */
    public static function getVeryfy() {
        return sprintf('%s%s', BDataHelper::getYear(), rand(0000, 9999));
    }

	/**
	 * 获取pid
	 * @author wjh
	 * @version 2014-5-12
	 * @return string char(11)
	 */
	public static function getPid() {
		return self::getModelNumber('Product', 'pid');
	}


    /**
     * 获取Srbac debug 状态
     * @author wjh 20141215
     * @return mixed
     */
    public static function getSrbacDebug(){
        $srbac = Yii::app()->getModule('srbac');
        return $srbac->debug;
    }

    /**
     * 获取yii::app->session 变量
     * @author wjh
     * @version 2014-5-12
     * @param $var 变量名称
     * @param $default 默认值
     * @return string
     */
    public static function getSessionVariable($var,$default) {
        return empty(Yii::app()->session[$var])?$default:Yii::app()->session['moduleid'] ;
    }

    /**
     * 判断对象是否相等
     * @author wjh 2014-7-6
     * @param object $a
     * @param object $b
     * @return bool
     */
    public static function equalObject($a,$b){
        if (is_null($a) && is_null($b)) {
            return true;
        }
        elseif(is_null($a) && !is_null($b)){
            return false;
        }
        elseif(is_null(!$a) && is_null($b)){
            return false;
        }
        else{
            return trim($a) === trim($b);
        }
    }


    /**
     * 获取pid
     * @author wjh
     * @version 2014-5-12
     * @return string char(11)
     */
    public static function getBaseUrl($queryUrl='') {
        $surl = sprintf('%s%s',Yii::app()->baseUrl,$queryUrl);
        return $surl;
    }

    /**
     * 获取pid
     * @author wjh
     * @version 2014-5-12
     * @return string char(11)
     */
    public static function getBaseUrlAndCheckAccess($queryUrl='') {
        $surl = sprintf('%s%s',Yii::app()->baseUrl,$queryUrl);
        $surl = self::checkMenuAccess($surl);
        return $surl;
    }


    public static function checkMenuAccess($sourceUrl){
        $url = $sourceUrl;
        $url = substr($url,strlen(BDataHelper::getBaseUrl()));

        $ids = explode('/',$url);
        $ids=    BArrayHelper::removeEmpty($ids);

        if(count($ids)<2){
            $item = '';
        }
        elseif(count($ids)==2){
            $item = "$ids[1]$ids[2]";
        }
        elseif(count($ids)==3){
            $item = "$ids[1].$ids[2]$ids[3]";
        }
        elseif(count($ids)>3){
            $item = "$ids[1].$ids[2]$ids[3]";
        }
        else{

        }

        //var_dump(Helper::checkAccess($item));
        if(!Helper::checkAccess($item)){
            $url = null;
        }
        else{
            $url = $sourceUrl;
        }
        return $url;
    }

	/**
	 * 获取pid
	 * @author wjh
	 * @version 2014-5-12
	 * @return string char(11)
	 */
	public static function getServerUrl($queryUrl='',$type='normal') {
		if ($type=='thumbnail')
			$queryUrl = preg_replace('/\/files\//','/files/thumbnail/',$queryUrl);

		$surl = sprintf('http://%s%s',$_SERVER['HTTP_HOST'],Yii::app()->baseUrl);
		return $surl.$queryUrl;
	}


    /**
     * 设置页面标题
     * @author wjh
     * @version 2014-5-12
     * @param string $module 模块
     * @param string $page 页面
     * @param string $function 功能
     * @return string
     */
    public static function setPageTitle($module=null,$page=null,$function=null) {

        $url = '在线预订及智能化管理平台';
        $currentOrg=BDataHelper::getCurrentOrgInfo();
        if(!empty($currentOrg)){
			$url = $currentOrg['pagetitle'];
        }

		$url2 = $page;
        if(!is_null($module)){
			$url2 .= '－'.$module;
        }

		if(!is_null($function)){
			$url2 .= '－'.$function;
		}

        if(!is_null($url)){
			//$url2 .= '－'.$url;
        }
        return $url2;
    }
	
	
	/**
	 * 保存 Extra 数据
	 * @author wjh 2014-5-20
	 * @param array $extraform post data
	 * @param string $pid
	 */
	public static function saveExtraData($extraform,$pid){
		
		//-----
		if (isset($extraform) && is_array($extraform)){
			$extra = $extraform;
			//$pid ='p1';
			$erows = ProductExtra::model()->findAllByAttributes(array('pid'=>$pid));
			if (is_null($erows) || count($erows)==0){
				foreach ($extra as $ekey => $evalue) {
					if (!is_array($evalue)){
						$evalue = array($evalue);
					}
					
					foreach ($evalue as $ikey => $ivalue) {
						$emodel = new ProductExtra();
						$emodel->pid = $pid;
						$emodel->attributename = $ekey;
						$emodel->attributetext = $ivalue;
                        $emodel->attributeindex = $ikey;
						$emodel->vtype = "S";
						$emodel->save();
							
						$ee = $emodel->errors;
					}
				}
			}
			else {
				foreach ($extra as $ekey => $evalue) {
					//exist
					$exist = false;
					foreach ($erows as $xkey => $xvalue) {
						if($xvalue->attributename == $ekey){
							$exist=true;
							break;
						}
						$exist = false;
					}
					
					//数组处理
					if (is_array($evalue)){
						ProductExtra::model()->deleteAllByAttributes(array('pid'=>$pid,'attributename'=>$ekey));
						foreach ($evalue as $ikey => $ivalue) {
							$emodel = new ProductExtra();
							$emodel->pid = $pid;
							$emodel->attributename = $ekey;
							$emodel->attributetext = $ivalue;
                            $emodel->attributeindex = $ikey;
							$emodel->vtype = "S";
							$emodel->save();
						
							$ee = $emodel->errors;
						}
					}
					else {
						$emodel = $exist? ProductExtra::model()->findByAttributes(array('pid'=>$pid,'attributename'=>$ekey)) : new ProductExtra();
						if (!is_array($evalue)){
							$evalue = array($evalue);
						}
							
						foreach ($evalue as $ikey => $ivalue) {
							$emodel->pid = $pid;
							$emodel->attributename = $ekey;
							$emodel->attributetext = $ivalue;
                            $emodel->attributeindex = $ikey;
							$emodel->vtype = "S";
							$emodel->save();
				
							$ee = $emodel->errors;
						}
					}
				}
			}
		
		}
	}
	
	
	/**
	 * 输出扩展字段label
	 * @author wjh 2014-5-20
	 * @param BFormModel $model model
	 * @param string $attribute attribute
	 */
	public static function getExtraLable($model,$attribute) {
		try {
			
			if (empty($model->type_id)){
				throw new Exception('$type_id is empty');
			}
			
			$otype = new ProductType();
			$type =	$otype->findByAttributes(array('type_id'=>$model->type_id));
			$obj = BJSON::decodeToArray($type->struc);
			$extra = $obj['extra_data'];
			
			$extraStruct = ProductExtraStruct::model()->find('attributename=:attributename', array(':attributename' => $attribute));
			if (!isset($extraStruct)){
				echo 'not set struct '.$attribute;
				die();
			}
			
			echo isset($extraStruct)? $extraStruct->attributelabel:$attribute;
			//echo CHtml::label($extraStruct->attributelabel, $attribute,$htmlOptions);
				
		} catch (Exception $ex) {
			echo CJSON::encode(array('success' => false, 'message' => $ex->getMessage()));
			return;
		}
	}
	
	
	/**
	 * 扩展字段数据的展示
	 * @author wjh 2014-5-20
	 * @param BFormModel $model model
	 * @param string $attribute attribute
	 * @return string value string
	 */
	public static function getExtraData($model,$attribute) {
		try {
				
			$otype = null;
			if ($model instanceof Product){
				$otype = new ProductType();
			}
			else if($model instanceof ProductForm){
				$otype = new ProductType();
			}
			else {
				throw new Exception('model is not null');
			}
				
			$type =	$otype->findByAttributes(array('type_id'=>$model->type_id));
			$obj = BJSON::decodeToArray($type->struc);
			$extra = $obj['extra_data'];
				
			$extraStruct = ProductExtraStruct::model()->find('attributename=:attributename', array(':attributename' => $attribute));
			$extraDate = ProductExtra::model()->find('attributename=:attributename and pid=:pid', array(':attributename' => $attribute, ':pid' => $model->pid));
			if (!isset($extraStruct)){
				echo 'not set struct '.$attribute;
				die();
			}
			
			$value = '';
			if (!is_null($extraDate)){
				$value = $extraDate->vtype=='S'? $extraDate->attributetext:$extraDate->attributevalue;
			}
				
			return $value;
	
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * 扩展字段数据的展示
	 * @author wjh 2014-5-20
	 * @param BFormModel $model model
	 * @param string $attribute attribute
	 * @return array
	 */
	public static function getExtraDataArray($model,$attribute) {
		try {
	
			$otype = null;
			if ($model instanceof Product){
				$otype = new ProductType();
			}
			else if($model instanceof ProductForm){
				$otype = new ProductType();
			}
			else {
				throw new Exception('model is not null');
			}
	
			$type =	$otype->findByAttributes(array('type_id'=>$model->type_id));
			if(is_null($type)){
				throw  new Exception('type_id not set');	
			}
			
			$obj = BJSON::decodeToArray($type->struc);
			$extra = $obj['extra_data'];
	
			$extraStruct = ProductExtraStruct::model()->find('attributename=:attributename', array(':attributename' => $attribute));
			$extraDate = ProductExtra::model()->findAll('attributename=:attributename and pid=:pid', array(':attributename' => $attribute, ':pid' => $model->pid));
			if (!isset($extraStruct)){
				echo 'not set struct '.$attribute;
				die();
			}
				
			$value = '';
			$rvalue = array();
			if (!is_null($extraDate)){
				foreach ($extraDate as $ekey => $evalue) {
					$rvalue[] = $evalue->vtype=='S'? $evalue->attributetext:$evalue->attributevalue;
				}
			}
	
			return $rvalue;
	
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * 扩展字段数据的文本框
	 * @author wjh 2014-5-20
	 * @param BFormModel $model model
	 * @param string $attribute attribute
	 */
	public static function getExtraTextField($model,$attribute) {
		try {
	
			$otype = null;
			if ($model instanceof Product){
				$otype = new ProductType();
			}
			else if($model instanceof ProductForm){
				$otype = new ProductType();
			}
			else {
				throw new Exception('model is not null');
			}
	
			$type =	$otype->findByAttributes(array('type_id'=>$model->type_id));
			$obj = BJSON::decodeToArray($type->struc);
			$extra = $obj['extra_data'];
	
			$extraStruct = ProductExtraStruct::model()->find('attributename=:attributename', array(':attributename' => $attribute));
			$extraDate = ProductExtra::model()->find('attributename=:attributename and pid=:pid', array(':attributename' => $attribute, ':pid' => $model->pid));
			if (!isset($extraStruct)){
				echo 'not set struct '.$attribute;
				die();
			}
				
			$value = '';
			if (!is_null($extraDate)){
				$value = $extraDate->vtype=='S'? $extraDate->attributetext:$extraDate->attributevalue;
			}
	
			//echo CHtml::label($extraStruct->attributelabel, $attribute);
				
			echo CHtml::textField(sprintf('ExtraForm[%s]',$attribute),$value,array('id'=> sprintf('ExtraForm_%s',$attribute)));
			if ($extraStruct->isrequired){
				echo CHtml::tag('span',array('class'=>'required'),'*');
			}
	
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	

	/**
	 * 扩展字段数据的展示
	 * @author wangjingzhi
	 * @date May 15, 2014 5:31:23 PM
	 * @param $obj 数据结构
	 * @param $type p(产品)，g(团)
	 * @param $pid 产品ID
	 * @return
	 *
	 */
	public static function generateDataTypeStruc($obj, $type, $pid) {
		$str = '';
		$type = 'text';
		try {
			if ($obj == null)
				throw new Exception('data is not null');

			$obj = BJSON::decodeToArray($obj);
			foreach ($obj as $key => $v) {
				if ($key != 'extra_data')
					continue;

				$str = $str . '<form id="productExtra-form">';
				$str = $str . '<input type="hidden" name="' . $key . '[pid]" value="' . $pid . '"/>';
				foreach ($obj[$key] as $key1 => $v1) {
					$temp = '';
					$extraStruct = ProductExtraStruct::model()->find('attributename=:attributename', array(':attributename' => $key1));

					$extraDate = ProductExtra::model()->find('attributename=:attributename and pid=:pid', array(':attributename' => $key1, ':pid' => $pid));

					if ($extraDate != null && count($extraDate) > 0) {
						$temp = $extraDate->attributetext;
					}

					if ($extraStruct != null && $extraStruct->vtype != null) {
						$type = BDataHelper::getStr(self::$ARRAY_DATA_TYPE, $extraStruct->vtype);

						if ($extraStruct->vtype == self::TEXT_AREA)
							$str = $str . $v1 . ':' . '<' . $type . ' name="' . $key . '[' . $key1 . ']" style = "width: 505px; height: 158px;">' . $temp . '</' . $type . '>' . '<br>';

					} else
						$str = $str . $v1 . ':' . '<input type="text" value="' . $temp . '" name="' . $key . '[' . $key1 . ']"/>' . '<br>';

				}
				$str = $str . '<input type="button" class="button extraClass" name="extraButton" value="提交"/>';
				$str = $str . '</form>';
			}
			$str = $str . '<script type=\'text/javascript\'>';
			$str = $str . '$(function(){$(\'.extraClass\').bind(\'click\',function(){
			$.ajax({
				cache: true,
				type: "POST",
				url:"/btg/product/product/teststr1",
				data:$(\'#productExtra-form\').serialize(),
				async: false,
				error: function(request) {
				    alert("Connection error");
				},
				success: function(rs) {
					if(rs!=null && rs.length>0){
						if($.parseJSON(rs).success == true){
							alert("成功");
						}else
							alert($.parseJSON(rs).message);
					}else{
						alert("失败！");
					}
				}
			});});})';

			$str = $str . '</script>';
			
		} catch (Exception $ex) {
			echo CJSON::encode(array('success' => false, 'message' => $ex->getMessage()));
			return;
		}
		return $str;
	}

	/**
	 * 存储结构数据
	 * @author wangjingzhi
	 * @date May 15, 2014 4:32:43 PM
	 * @param $obj json or arrray
	 * @param $type p(产品) or g(团)
	 * @return
	 *
	 */
	public static function storageDataTypeStruc($post) {
		$pid = '';
		$model = new ProductExtra();
		$tran = Yii::app()->db->beginTransaction();
		try {
			$extraData = $post['extra_data'];
			if ($extraData == null || count($extraData) < 1)
				throw new CHttpException(500, 'extraData is not null');
			$pid = $extraData['pid'];
			if ($pid == null || count($pid) < 1)
				throw new CHttpException(500, 'pid is not null');

			$productExtraData = null;
			foreach ($extraData as $k => $v) {
				if ($k == 'pid')
					continue;
				$productExtraData = ProductExtra::model()->find('pid=:pid and attributename=:attributename', array(':pid' => $pid, ':attributename' => $k));

				if ($productExtraData != null && count($productExtraData) > 0)
					$productExtraData->attributetext = $extraData[$k];
				else {
					$productExtraData = new ProductExtra();
					$productExtraData->attributevalue = 0;
					$productExtraData->etype = 0;
					$productExtraData->mark = 0;
					$productExtraData->attributename = $k;
					$productExtraData->pid = $pid;
					$productExtraData->attributetext = $extraData[$k];
				}
				$productExtraData->save();
			}
			$tran->commit();
			echo CJSON::encode(array('success' => true));
		} catch (Exception $ex) {
			$tran->rollback();
			echo CJSON::encode(array('success' => false, 'message' => $ex->getMessage()));
		}
		
	}
	
	/**
	 * get model
	 * @author wjh 2014-5-16
	 * @param mixed $className string class name or class instance 
	 * @return CActiveRecord record
	 */
	public static function getModel($className=__CLASS__)
	{
		if (is_string($className))
			return CActiveRecord::model($className);
		else 
			return CActiveRecord::model(get_class($className));
	}
	
	
	/**
	 * get class name 
	 * @author wjh 2014-5-16
	 * @param CActiveRecord $instanceName
	 * @return string calss name
	 */
	public static function getClassName($instanceName)
	{
		return get_class($instanceName);
	}
	
	/**
	 * 获取类型PKID内容
	 * @author wjh
	 * @version 2014-5-21
	 * @param string $modelType model type
	 * @param string $pk sort id
	 * @param array $with with array
	 * @param array $escape escape array
	 * @param string $rtype return type , array or json,default array
	 * @return mixed data array or json text
	 */
	public static function getBtgByPk($modelType,$pk,$with=array(),$escape=array(),$rtype='array')
	{
		$model=$modelType::model()->with($with)->findByPk($pk);
		if (is_null($model)){
			return array();
		}
	
		$data = BJSON::encodeModelToArray($model,$escape,2);
		if ($rtype =='json'){
			$data = BJSON::encodeArray($data);
		}
	
		return $data;
	}
	
	

	/**
	 * 获取分项网页URL
	 * @author wjh 2014-5-23
	 * @param array $item
	 * @return string url string 
	 */
	public static function getCtypeUrl($item){
		$burl = '';
		switch ($item['ctype']) {
			case PItem::CTYPE_BASEINFO:
				$burl = "/product/main/baseinfo/type_id/".$item['type_id']."pid/".$item['pid'];
				break;
	
			case PItem::CTYPE_JOURNEY:
				$burl = "/product/main/journeyindex/pid/".$item['pid'];
				break;
					
			case PItem::CTYPE_PRRICE:
				$burl = "/product/main/priceNewIndex/pid/".$item['pid'];
				break;
					
			default:
				$burl = "/product/main/defaultindex/iid/".$item['iid']."/ctype/".$item['ctype'];
				break;
		}
	
		return BDataHelper::getServerUrl($burl);
	}


	/**
	 * 获取团分项网页URL
	 * @author wangjingzhi
	 * @param array $item
	 * @return string url string
	 */
	public static function getGroupCtypeUrl($item){
		$burl = '';
		switch ($item['ctype']) {
			case PItem::CTYPE_BASEINFO:
				$burl = "/group/main/baseinfo/type_id/".$item['type_id']."gid/".$item['gid'];
				break;

			case PItem::CTYPE_JOURNEY:
				$burl = "/group/main/journeyindex/gid/".$item['gid'];
				break;

			case PItem::CTYPE_PRRICE:
				$burl = "/group/main/priceNewIndex/gid/".$item['gid'];
				break;

			default:
				$burl = "/group/main/defaultindex/iid/".$item['iid']."/ctype/".$item['ctype'];
				break;
		}

		return BDataHelper::getServerUrl($burl);
	}

	/**
	 * 获取团分项网页URL
	 * @author wangjingzhi
	 * @param array $item
	 * @return string url string
	 */
	public static function getChildGroupCtypeUrl($item){
		$burl = '';
		switch ($item['ctype']) {
			case PItem::CTYPE_JOURNEY:
				$burl = "/group/main/journeyindex/gid/".$item['gid'];
				break;

			case PItem::CTYPE_PRRICE:
				$burl = "/group/main/priceNewIndex/gid/".$item['gid'];
				break;

			default:
				$burl = "/group/main/defaultindex/iid/".$item['iid']."/ctype/".$item['ctype'];
				break;
		}

		return BDataHelper::getServerUrl($burl);
	}


    /**
     *获取项目信息数组
     * @author wjh
     * @date 14-5-29
     * @param string $cfgid cfgid
     * @param stirng $evalue item key
     * @return array
     */
    public static function getConfigArray($cfgid,$evalue=null){
        $items = ConfigInfo::getItems($cfgid);
        if(is_null($evalue)){
            return $items;
        }
        else{
            return  BArrayHelper::getValue($items,$evalue);
        }
    }

    /**
     * override print_r
     * @author wjh 2014-7-1
     * @param $expression
     * @param null $return
     */
    public static function print_r($expression,$expression2=null,$expression3=null,$expression4=null,$expression5=null,$expression6=null,$expression7=null, $return = null){
        DataHelper::addPreStart();
        print_r($expression, $return);
		if (!empty($expression2))
			print_r($expression2);
		if (!empty($expression3))
			print_r($expression3);
		if (!empty($expression4))
			print_r($expression4);
		if (!empty($expression5))
			print_r($expression5);
		if (!empty($expression6))
			print_r($expression6);
		if (!empty($expression7))
			print_r($expression7);
		DataHelper::addPreEdn();
    }


    /**
     * override print_r
     * @author wjh 2014-7-1
     * @param $expression
     * @param null $return
     */
    public static function cuteString($str, $length,$flag=true) {
        if(mb_strlen($str, 'utf-8') > $length){
            $str=mb_substr($str, 0, $length, 'utf-8');
            if($flag){
                $str=$str.'...';
            }
        }
       return $str;
	}


    /**
     * 对一个日期加一定天数
     * @author wjh 20141025
     * @param DateTiem $date 日期
     * @param int $days 天数
     * @return string 2014-05-9
     */
    public static function  date_add($date,$days){
		try{
			$date = date_add(new DateTime($date),new DateInterval('P'.$days.'D'));
			return $date->format('Y-m-d');
		}
		catch(Exception $e){
			return '';
		}
    }


    /**
     * 将内容进行UNICODE编码，编码后的内容格式：YOKA\u738b （原始：YOKA王）
     * @author wjh 20141106
     * @param $name
     * @return string
     */
    public static function unicode_encode($name)
    {
        $name = iconv('UTF-8', 'UCS-2', $name);
        $len = strlen($name);
        $str = '';
        for ($i = 0; $i < $len - 1; $i = $i + 2)
        {
            $c = $name[$i];
            $c2 = $name[$i + 1];
            if (ord($c) > 0)
            {    // 两个字节的文字
                $str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
            }
            else
            {
                $str .= $c2;
            }
        }
        return $str;
    }

    /**
     * 获取当前时间数值，如传入 start 则计算2者的差值
     * @author wjh 20141204
     * @param int $start
     * @return float
     */
    public static function getMillisecond($start=0) {
        list($s1, $s2) = explode(' ', microtime());
        $now = (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
        return $now-$start;
    }


    /**
     * 将UNICODE编码后的内容进行解码，编码后的内容格式：YOKA\u738b （原始：YOKA王）
     * @author wjh 20141106
     * @param $name
     * @return string
     */
    public static function unicode_decode($name)
    {
        // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
        $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
        preg_match_all($pattern, $name, $matches);
        if (!empty($matches))
        {
            $name = '';
            for ($j = 0; $j < count($matches[0]); $j++)
            {
                $str = $matches[0][$j];
                if (strpos($str, '\\u') === 0)
                {
                    $code = base_convert(substr($str, 2, 2), 16, 10);
                    $code2 = base_convert(substr($str, 4), 16, 10);
                    $c = chr($code).chr($code2);
                    $c = iconv('UCS-2', 'UTF-8', $c);
                    $name .= $c;
                }
                else
                {
                    $name .= $str;
                }
            }
        }
        return $name;
    }


    /**
     * 分割字符串返回数组
     * @author wjh 20141111
     * @param $id
     * @return array
     * @throws CHttpException
     */
    public static function getArrayFromString($id)
    {
        if (empty($id))
            return array();

        if (is_string($id)) {
            $idlist = explode(',', trim($id, ','));
        }
        else{
            $idlist = BArrayHelper::removeEmpty($id);
        }
        return $idlist;
    }


    /**
     * 获取类型定义中的 index 或 key
     * @author wjh 2014-6-3
     * @param array $arr
     * @param string $text index or key
     * @return string
     */
    public static function getKey($arr,$text){
        return BDefind::getKey($arr,$text);
    }

    /**
     * 获取类型定义的值（文本）内容
     * @author wjh 2014-6-3
     * @param array $arr
     * @param mixed $index index or key name
     * @return mixed
     */
    public static function getValue($arr,$index){
        return BDefind::getValue($arr,$index);
    }


    /**
     * 获取一个人的组织类型
     * 门市、代理商、组团社、提供商
     * @author wjh 20141113
     * @param null $userid
     * @return string
     * @throws Exception
     */
    public static function getOrgType($userid=null){

        if (empty($userid))
            $userid = BDataHelper::getCurrentUserid();

        $isRole = BDataHelper::checkCurrentUserRole('group_');
        if($isRole)
            return GroupTransaction::ORGTYPE_GROUP;

        $isRole = BDataHelper::checkCurrentUserRole('sales_');
        if($isRole)
            return GroupTransaction::ORGTYPE_SALES;

        $isRole = BDataHelper::checkCurrentUserRole('provider_');
        if($isRole)
            return GroupTransaction::ORGTYPE_PROVIDER;

        $isRole = BDataHelper::checkCurrentUserRole('agent_');
        if($isRole)
            return GroupTransaction::ORGTYPE_AGENT;

       return GroupTransaction::ORGTYPE_SALES;
    }

    /**
     * 获取除数百分比
     * @author wjh 20141118
     * @param $a
     * @param $b
     * @param int $precision
     * @return float|int
     */
    public static function getPercent($a, $b, $precision = 2)
    {
        if (empty($a) || empty($b)) {
            return 0;
        }
        return round($a / $b, $precision) * 100;
    }


    /**
     * 取得一个变量数值，如果为空返回0
     * @author wjh 20141120
     * @param $num
     * @return float|int
     */
    public static function getNumber($num){
        return empty($num)?0:doubleval($num);
    }

}
