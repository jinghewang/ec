<?php
/**
 * Created by PhpStorm.
 * User: hltravel
 * Date: 16/3/25
 * Time: 下午5:00
 */

require_once('common.php');


function sess_open(){
    showMsg(__FUNCTION__);
}
function sess_close(){
    showMsg(__FUNCTION__);
}

function sess_read($sess_id){
    showMsg(__FUNCTION__);
}

function sess_write($sess_id, $sess_data){
    showMsg(__FUNCTION__);
}

function sess_destroy($sess_id){
    showMsg(__FUNCTION__);
}

function sess_gc(){
    showMsg(__FUNCTION__);
}

function showMsg($func){
    echo "<pre>{$func}</pre>";

}

session_set_save_handler('sess_open','sess_close','sess_read','sess_write','sess_destroy','sess_gc');

session_start();


$_SESSION['name'] = 'zhangsan';

$_SESSION['arr'] = [1,2,3];

print_r2($_SESSION);

//session 默认存储位置



//session 可以存储变量类型






