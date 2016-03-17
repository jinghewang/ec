<?php
namespace common\helpers;

/**
 * 缓存帮助类
 * @author wjh 2014年11月4日
 * Class BCacheHelper
 */
class BaseCacheHelper {


    /**
     * set
     * @param $id
     * @param callable $value
     * @return bool
     */
    public static function set($id,$value,$expire=0)
    {
        if (is_callable($value)) {
            $data = $value();
        }
        else{
            $data= $value;
        }
        return  Yii::app()->cache->set($id,$data,$expire);
    }

    /**
     * delete
     * @param $id
     * @return bool
     */
    public static function delete($id)
    {
        return Yii::app()->cache->delete($id);
    }

    /**
     * deleteValue
     * @param $id
     * @return bool
     */
    public static function deleteValue($id)
    {
        return Yii::app()->cache->deleteValue($id);
    }

    /**
     * get
     * @author wjh
     * @param $id
     * @param callable $func
     * @param bool $autoset auto set func
     * @throws Exception
     * @return mixed
     */
    public static function get($id,$func=null,$autoset=true)
    {
        $iscache = Yii::app()->params['switch']['memcache'];
        if (empty($iscache)){//未启用memcache 缓存
            if(!empty($func) && is_callable($func))
                return $func();
            else
                return false;
        }
        else{//启用缓存
            //$id,$value
            $value=Yii::app()->cache->get($id);
            if($value===false && $autoset)
            {
                // 因为在缓存中没找到，重新生成 $value
                if (!empty($func) && is_callable($func)) {
                    $value = $func();
                    $result = Yii::app()->cache->set($id,$value);
                    if(!$result)
                        throw new Exception('cache set false');
                }
                else{
                    return false;
                }
				$value=Yii::app()->cache->get($id);
            }
            return $value;
        }
    }

    /**
     * 前导字符
     */
    const PRECHAR ='k:';

    /**
     * 获取缓存KEY
     * @param $key string or array
     * @return mixed string | array
     */
    public static function getCacheKey($key){
        if (!is_array($key)) {
            return self::PRECHAR . $key;
        } else {
            $data = array();
            array_walk($key, function ($ekey) use (&$data) {
                $data[] = self::PRECHAR . $ekey;
            });
            return $data;
        }
    }

    /**
     * 获取缓存KEY前导字符
     * @param $key
     * @return string
     */
    public static function getPreChar(){
        return self::PRECHAR;
    }

    /**
     * keyExists
     * @param $key
     * @param $data
     * @return bool
     */
    public static function keyExists($key,$data)
    {
        $key = self::getCacheKey($key);
        return array_key_exists($key,$data);
    }

    /**
     * 缓存中查找
     * @author wjh 20121222
     * @param $key
     * @param $data
     * @return mixed
     */
    public static function find($key,$data,$returnType='array',$model=null){
        $key = self::getCacheKey($key);
        if (!is_array($key)){
            if(!isset($data[$key]))
                return null;

            $data = $data[$key];
            return $returnType == 'array' ? $data : self::getModel($model, $data);
        }
        else{
            $result = array();
            array_walk($key,function($ekey)use(&$result,$data,$returnType,$model){
                if(isset($data[$ekey])){
                    $row = $returnType == 'array' ? $data[$ekey] : self::getModel($model, $data[$ekey]);
                    $result[] = $row;
                }
            });
            return $result;
        }
    }

    /**
     * 缓存中查找
     * @author wjh 20121222
     * @param $key
     * @param $data
     * @return mixed
     */
    public static function initArray($key,$data,$dataType='array'){
        $result = array();
        array_walk($data, function ($row) use (&$result, $key, $data, $dataType) {
            $key = self::getCacheKey($dataType == 'array' ? $row[$key] : $row->key);
            $result[$key] = $row;
        });
        unset($data);
        return $result;
    }

    /**
     * 使用缓存键初始化数组值
     * @author wjh 20121222
     * @param $data
     * @return mixed
     */
    public static function initIndexedArrayValue($data){
        $result = array();
        array_walk($data, function ($v,$k) use (&$result, $data) {
            $key = self::getCacheKey($v);
            $result[$k] = $key;
        });
        unset($data);
        return $result;
    }

    /**
     * [否决的，请使用 find]缓存中查找
     * @author wjh 20121222
     * @param $key
     * @param $attributes
     * @return mixed
     */
    public static function cacheFind($key,$attributes,$returnType='array',$model=null){
        return self::find($key,$attributes,$returnType,$model);
    }

    /**
     * 缓存中查找
     * @author wjh 20121222
     * @param $key
     * @param $attributes
     * @return mixed
     */
    private static function findAll($key,$attributes,$returnType='array',$model=null){
        $key = self::getCacheKey($key);
        if (!is_array($key))
            $key = array($key);

        $data = array();
        array_walk($key,function($ekey)use(&$data,$attributes,$returnType,$model){
            $row = $returnType == 'array' ? $attributes[$ekey] : self::getModel($model, $attributes[$ekey]);
            $data[] = $row;
        });
        return $data;
    }

    /**
     * 转换普通数组为 model
     * @author wjh 20141222
     * @param $model
     * @param $attributes
     * @return mixed
     */
    public static function getModel($model,$attributes){
        if(BArrayHelper::isCActiveRecord($model))
            $obj = clone $model;
        else
            $obj = new $model();

        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * 刷新ActiveRecord 缓存内容
     * @author wjh
     * @param BActiveRecord $model
     */
    public static function refreshActiveRecordCache($model)
    {
        try {
            $classname = get_class($model);
            if (BArrayHelper::array_value_exists($classname, BCacheHelper::$CACHETYPE)) {
                $key = BDefind::getKeyByValue(BCacheHelper::$CACHETYPE, $classname);
                BCacheHelper::delete($key);
            }
        } catch (Exception $e) {
        }
    }


    public static function initCacheData($data, $key, $datatype = 'array')
    {
        $data2 = array();
        foreach ($data as $item) {
            $key = $datatype == 'array' ? $item['userid'] : $item->key;
            $key = BCacheHelper::getCacheKey($key);
            $data2[$key] = $item;
        }
        return $data2;
    }

    //---------------------------test-------------------------------------

    public static function testAll(){
        $returnType= 'array';
        BDataHelper::print_r('------------testArray-----------');
        $ht = BCacheHelper::testArray($returnType,true);
        BDataHelper::print_r($ht);

        BDataHelper::print_r('------------testModel-----------');
        $ht = BCacheHelper::testModel($returnType,true);
        BDataHelper::print_r($ht);

        BDataHelper::print_r('------------testGetArray-----------');
        $ht = BCacheHelper::testGetArray($returnType,true);
        BDataHelper::print_r($ht);

        BDataHelper::print_r('------------testGetModel-----------');
        $ht = BCacheHelper::testGetModel($returnType,true);
        BDataHelper::print_r($ht);
    }

    public static function testGetArray($returnType='array',$refresh=false,$autoset=true){
        $cachekey = 'test-array-array';
        return BCacheHelper::get($cachekey);
    }

    public static function testArray($returnType='array',$refresh=false,$autoset=true){
        $cachekey = 'test-array-array';
        if ($refresh)
            BCacheHelper::delete($cachekey);

        $data = self::get($cachekey,function(){
            $users = User::model()->findAll(array('condition' => 'isdelete=0', 'limit' => 10));
            $users = BArrayHelper::getModelAttributesArray($users,'userid',BCacheHelper::getPreChar());
            return $users;
        },$autoset);

        return $data;
    }

    public static function testGetModel($returnType='array',$refresh=false,$autoset=true){
        $cachekey = 'test-array-model';
        return BCacheHelper::get($cachekey);
    }

    public static function testModel($returnType='array',$refresh=false,$autoset=true){
        $cachekey = 'test-array-model';
        if ($refresh)
            BCacheHelper::delete($cachekey);

        $data = self::get($cachekey,function(){
            $users = User::model()->findAll(array('condition' => 'isdelete=0', 'limit' => 10));
            $users = BArrayHelper::getModelIndexArray($users,'userid',BCacheHelper::getPreChar());
            return $users;
        },$autoset);

        return $data;
    }


}
