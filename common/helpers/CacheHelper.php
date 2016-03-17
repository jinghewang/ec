<?php
namespace common\helpers;

/**
 * 缓存帮助类
 * @author wjh 2014年11月4日
 * Class BCacheHelper
 */
class CacheHelper extends BaseCacheHelper {


    const CACHETYPE_USERS = 'users';
    const CACHETYPE_ORGS = 'orgs';
    const CACHETYPE_CHANNELES = 'channels';
    const CACHETYPE_SORTS = 'sorts';
    const CACHETYPE_PRODUCTTYPES = 'producttypes';
    const CACHETYPE_GROUPTAGS = 'grouptags';
    const CACHETYPE_TOKENS = 'tokens';

    /**
     * 缓存分类
     * @var array
     */
    static $CACHETYPE= array(
        self::CACHETYPE_USERS => 'User',
        self::CACHETYPE_ORGS => 'Organization',
        self::CACHETYPE_CHANNELES => 'Channel',
        self::CACHETYPE_SORTS => 'Sort',
        self::CACHETYPE_PRODUCTTYPES => 'ProductType',
        self::CACHETYPE_GROUPTAGS => 'Tag',
        self::CACHETYPE_TOKENS => 'AccessToken',
    );


    public static function clearAll(){
        foreach (self::$CACHETYPE as $key=>$type) {
            BDataHelper::print_r($key);
            self::delete($key);
        }
    }


    //producttype
    private static function _getProductTypes(){
        return function () {
            $data = ProductType::model()->findAll(array('index'=>'type_id'));
            $data2 = array();
            foreach ($data as $key=>$model) {
                $key = self::getCacheKey($model->type_id);
                $data2[$key] = $model->attributes;
            }
            unset($data);
            return $data2;
        };
    }

    /**
     * getProductType
     * @author wjh 2014-12-30
     * @param $id
     * @param string $returnType
     * @return mixed
     */
    public static function getProductType($id,$returnType='array',$data=null){
        $types = empty($data)? self::getProductTypes():$data;
        return BCacheHelper::find($id,$types,$returnType,ProductType::model());
    }

    public static function getProductTypes($returnType='array',$refresh=false,$autoset=true){
        if ($refresh)
            BCacheHelper::delete( self::CACHETYPE_PRODUCTTYPES);
        $data = self::get( self::CACHETYPE_PRODUCTTYPES,self::_getProductTypes(),$autoset);
        if ($returnType=='model') {
            array_walk($data,function(&$v){
               $v = BCacheHelper::getModel('ProductType',$v);
            });
        }
        return $data;
    }

    public static function setProductTypes(){
        return self::set( self::CACHETYPE_PRODUCTTYPES,self::_getProductTypes());
    }

    //grouptag
    private static function _getGroupTags()
    {
        return function () {
            $condition = empty($condition) ? ' isdelete=0' : $condition . ' and isdelete=0';
            $data = Tag::model()->findAllByAttributes(array('mtype' => Tag::MTYPE_GROUP), array('index' => 'tagsign', 'condition' => $condition, 'order' => 'listno desc'));
            $data2 = array();
            foreach ($data as $key=>$model) {
                $key = self::getCacheKey($model->tagsign);
                $data2[$key] = $model->attributes;
            }
            unset($data);
            return $data2;
        };
    }

    /**
     * getGroupTag
     * @author wjh 2014-12-30
     * @param $id
     * @param string $returnType
     * @return mixed
     */
    public static function getGroupTag($id,$returnType='array',$data=null){
        $types = empty($data)? self::getGroupTags():$data;
        return BCacheHelper::find($id,$types,$returnType,GroupTag::model());
    }

    public static function getGroupTags($returnType='array',$refresh=false,$autoset=true){
        if ($refresh)
            BCacheHelper::delete( self::CACHETYPE_GROUPTAGS);
        $data = self::get(self::CACHETYPE_GROUPTAGS,self::_getGroupTags(),$autoset);
        if ($returnType=='model') {
            array_walk($data,function(&$v){
                $v = BCacheHelper::getModel('Tag',$v);
            });
        }
        return $data;
    }

    public static function setGroupTags(){
        return self::set(self::CACHETYPE_GROUPTAGS,self::_getGroupTags());
    }

    //channel
    private static function _getChannels(){
        return function () {
            $data = Channel::model()->findAll(array('index'=>'chanid','order'=>'chanid'));
            $data2 = array();
            foreach ($data as $key=>$model) {
                $key = self::getCacheKey($model->chanid);
                $data2[$key] = $model->attributes;
            }
            unset($data);
            return $data2;
        };
    }

    public static function getChannel($id,$returnType='array',$data = null){
        $types = empty($data)? self::getChannels():$data;
        return BCacheHelper::find($id,$types,$returnType,Channel::model());
    }

    public static function getChannels($returnType='array',$refresh=false,$autoset=true,$condition=null){
        if ($refresh)
            BCacheHelper::delete(self::CACHETYPE_CHANNELES);
        $data = self::get(self::CACHETYPE_CHANNELES,self::_getChannels(),$autoset);
        if(!empty($condition) && is_callable($condition))
            $data = array_filter($data,function($v)use($condition){
                return $condition($v);
            });

        if ($returnType=='model') {
            array_walk($data,function(&$v){
                $v = BCacheHelper::getModel('Channel',$v);
            });
        }
        return $data;
    }

    public static function setChannels(){
        return self::set(self::CACHETYPE_CHANNELES,self::_getChannels());
    }

    //org
    private static function _getOrganizations(){
        return function () {
            $data = Organization::model()->findAll(array('index'=>'orgid','condition'=>'isdelete=0','order'=>'orgid'));
            $data2 = array();
            foreach ($data as $key=>$model) {
                $key = self::getCacheKey($model->orgid);
                $data2[$key] = $model->attributes;
            }
            unset($data);
            return $data2;
        };
    }

    public static function getOrganization($id,$returnType='array',$data=null){
        $types = empty($data)? self::getOrganizations():$data;
        return BCacheHelper::find($id,$types,$returnType,Organization::model());
    }

    public static function getOrganizations($returnType='array',$refresh=false,$autoset=true,$condition=null){
        if ($refresh)
            BCacheHelper::delete(self::CACHETYPE_ORGS);
        $data = self::get(self::CACHETYPE_ORGS,self::_getOrganizations(),$autoset);
        if(!empty($condition) && is_callable($condition))
            $data = array_filter($data,function($v)use($condition){
                return $condition($v);
            });

        if ($returnType=='model') {
            array_walk($data,function(&$v){
                $v = BCacheHelper::getModel('Organization',$v);
            });
        }
        return $data;
    }

    public static function setOrganizations($type=null){
        return self::set(self::CACHETYPE_ORGS,self::_getOrganizations($type));
    }

    //sort
    private static function _getSorts(){
        return function () {
            $data = Sort::model()->findAll(array('index'=>'sid','condition'=>'','order'=>'sid'));
            $data2 = array();
            foreach ($data as $key=>$model) {
                $key = self::getCacheKey($model->sid);
                $data2[$key] = $model->attributes;
            }
            unset($data);
            return $data2;
        };
    }

    public static function getSort($id,$returnType='array',$data=null){
        $types = empty($data)? self::getSorts():$data;
        return BCacheHelper::find($id,$types,$returnType,Sort::model());
    }

    public static function getSorts($returnType='array',$refresh=false,$autoset=true){
        if ($refresh)
            BCacheHelper::delete(self::CACHETYPE_SORTS);
        $data = self::get(self::CACHETYPE_SORTS,self::_getSorts(),$autoset);
        if ($returnType=='model') {
            array_walk($data,function(&$v){
                $v = BCacheHelper::getModel('Sort',$v);
            });
        }
        return $data;
    }

    public static function setSort(){
        return self::set(self::CACHETYPE_SORTS,self::_getSorts());
    }

    //user
    private static function _getUsers(){
        return function () {
            $data = User::model()->findAll('', array('index' => 'userid', 'condition' => 'isdelete=0', 'order' => 'userid','limit'=>3));
            $data2 = array();
            foreach ($data as $key => $model) {
                $key = self::getCacheKey($model->userid);
                $data2[$key] = $model->attributes;
            }
            unset($data);
            return $data2;
        };
    }

    public static function getUser($id,$returnType='array',$data=null){
        $types = empty($data)? self::getUsers():$data;
        return BCacheHelper::find($id,$types,$returnType,User::model());
    }

    public static function getUsers($returnType='array',$refresh=false,$autoset=true,$condition=null){
        if ($refresh)
            BCacheHelper::delete(self::CACHETYPE_USERS);
        $data = self::get(self::CACHETYPE_USERS,self::_getUsers(),$autoset);
        if(!empty($condition) && is_callable($condition))
            $data = array_filter($data,function($v)use($condition){
                return $condition($v);
            });

        if ($returnType=='model') {
            array_walk($data,function(&$v){
                $v = BCacheHelper::getModel('User',$v);
            });
        }
        return $data;
    }

    public static function setUsers(){
        return self::set(self::CACHETYPE_USERS,self::_getUsers());
    }


    //token
    public static function clearToken($id){
        $cacheData = self::getTokens();
        $key = self::getCacheKey($id);
        unset($cacheData[$key]);
        if(!BCacheHelper::set(self::CACHETYPE_TOKENS,$cacheData))
            throw new Exception('setToken failed');
        return true;
    }

    public static function setToken($id,$data,$dataType='array'){
        $cacheData = self::getTokens();
        $key = self::getCacheKey($id);
        $cacheData[$key] = $dataType == 'model' ? $data->attributes : $data;
        if(!BCacheHelper::set(self::CACHETYPE_TOKENS,$cacheData))
            throw new Exception('setToken failed');
        return true;
    }

    private static function _getTokens(){
        return function () {
            return array();
        };
    }

    public static function getToken($id,$returnType='array',$data=null){
        $types = empty($data)? self::getTokens():$data;
        return BCacheHelper::find($id,$types,$returnType,AccessToken::model());
    }

    public static function getTokens($returnType='array',$refresh=false,$autoset=true,$condition=null){
        if ($refresh)
            BCacheHelper::delete(self::CACHETYPE_TOKENS);
        $data = self::get(self::CACHETYPE_TOKENS,self::_getTokens(),$autoset);
        if(!empty($condition) && is_callable($condition))
            $data = array_filter($data,function($v)use($condition){
                return $condition($v);
            });

        if ($returnType=='model') {
            array_walk($data,function(&$v){
                $v = BCacheHelper::getModel('AccessToken',$v);
            });
        }
        return $data;
    }

}
