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
use api\services\ContractService;
use Yii;
use yii\base\Exception;

/**
 * BDataHelper is a static class that provides a collection of helper methods for creating HTML views.
 *
 * @author wangjinghe <wangjinghe@vive.net.cn>
 * @package system.web.helpers
 * @since 1.0
 */
class BDataHelper extends DataHelper
{
    /**
     * merge query params to one string
     * @param array $params
     * @return string
     */
    public static function getQueryParams($params = array())
    {
        if ($params)
            $params = array_merge(Yii::$app->request->queryParams, $params);
        else
            $params = Yii::$app->request->queryParams;

        array_walk($params,function(&$v,$k){
            $v = "$k=$v";
        });
        $params = implode('&',$params);
        if ($params)
            $params = '?' . $params;

        return $params;
    }

    public static function checkBoolResult($var)
    {
        if ($var)
            return 'true';
        else
            return 'false';
    }

    public static function checkEmptyResult($var)
    {
        if (empty($var))
            return 'true';
        else
            return 'false';
    }

    /**
     * 通过wkhtmltopdf生成pdf
     * @author lvkui
     * @date 20160223
     * @param $html 网页地址
     * @param string $filename 文件名包含地址
     * @param bool $exec 已取消
     * @param bool $download 是否为下载
     * @param bool $generate 是否仅生成
     */
    public static  function generate_pdf($html,$filename = 'download.pdf',$exec = false,$download = false,$generate=true)
    {
        //删除已存在文件
        if(file_exists($filename)){
            @unlink($filename);
        }

        //生成pdf
        $cmd="wkhtmltopdf --footer-right [page]/[topage] --margin-top 15 --margin-bottom 10 --margin-left 15 --margin-right 15 --page-size A4 {$html} {$filename}";
        exec($cmd);
        if(!file_exists($filename)){
            throw new Exception('文件生成错误');
        }

        //水印
        ContractService::setWater($filename);

        //仅进行生成操作
        if(!$generate){
            $pdf = file_get_contents($filename);

            /*if($exec === true){
                exec('sudo xvfb-run --server-args="-screen 0, 1024x680x24" wkhtmltopdf --use-xserver '.$html.' '.$filename);
                $pdf = file_get_contents($filename);
            } else {
                $descriptorspec = array(
                    0 => array('pipe', 'r'), // stdin
                    1 => array('pipe', 'w'), // stdout
                    2 => array('pipe', 'w'), // stderr
                );

                $process = proc_open('xvfb-run --server-args="-screen 0, 1024x680x24" wkhtmltopdf --use-xserver '.$html.' -', $descriptorspec, $pipes);
                $pdf = stream_get_contents($pipes[1]);
                $errors = stream_get_contents($pipes[2]);

                fclose($pipes[1]);
                $return_value = proc_close($process);
                if ($errors) die('PDF GENERATOR ERROR:<br />' . nl2br(htmlspecialchars($errors)));

            }*/


            header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
            header('Pragma: public');
            header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
            header('Content-Length: '.strlen($pdf));

            if($download === true){
                header('Content-Description: File Transfer');
                header('Content-Type: application/force-download');
                header('Content-Type: application/octet-stream', false);
                header('Content-Type: application/download', false);
                header('Content-Type: application/pdf', false);
                header('Content-Disposition: attachment; filename="'.basename($filename).'";');
                header('Content-Transfer-Encoding: binary');
            } else {
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="'.basename($filename).'";');
            }
            echo $pdf;
            die;
        }
    }


    /**
     * 下载文件
     * @param $filename
     * @throws \yii\base\Exception
     */
    public static function downfile($filename){
        $filename=realpath($filename);
        if(!$filename){
            throw new Exception('文件不存在');
        }

        $file = file_get_contents($filename);
        header('Content-Length: '.strlen($file));
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filename).'";');
        header('Content-Transfer-Encoding: binary');
        echo $file;
        die;
    }

    /**
     * 格式化path
     * @param $path
     * @return mixed
     */
    public static function format_path($path){
       return str_replace('/',DIRECTORY_SEPARATOR,$path);
    }


    /**
     * 判断当前用户是否具有权限
     * @example BDataHelper::check_access('/access-app/create')
     * @param $permission
     * @return bool
     */
    public static function check_access($permission){
        $user=Yii::$app->user;
        return Yii::$app->authManager->checkAccess($user->id,$permission);
    }

    /**
     * 过滤菜单权限
     * @param $menu
     * @return bool
     */
    public static function filter_menu_access($menu){
        return array_filter($menu,function($v){
            return self::check_access($v['url'][0]);
        });
    }

    /**
     * 判断当前用户是否具有角色
     * @param $rolename
     * @return bool
     */
    public static function check_role($rolename){
        $user=Yii::$app->user;
        $roles=Yii::$app->authManager->getRolesByUser($user->id);
        $role_keys=array_keys($roles);
        return in_array($rolename,$role_keys);
    }

    /**
     * 判断当前用户是仅具有当前角色
     * @return bool
     */
    public static function check_only_role($rolename='operator'){
        $user=Yii::$app->user;
        $roles=Yii::$app->authManager->getRolesByUser($user->id);
        $role_keys=array_keys($roles);
        $flag=in_array($rolename,$role_keys);
        if($flag&&count($role_keys)==1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取当前用户
     * @return User|mixed|\yii\web\User
     */
    public static function getCurrentUser(){
        return Yii::$app->user;
    }

    /**
     * 获取当前用户id
     * @return int|string
     */
    public static function getCurrentUserId($defaultvalue = null){
        return Yii::$app->user->id;
    }
}
