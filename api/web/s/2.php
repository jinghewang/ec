<?php
/**
 * Created by PhpStorm.
 * User: hltravel
 * Date: 16/3/25
 * Time: 下午5:00
 */


require_once('common.php');



function sess_open(){
    echo __FUNCTION__;
    global $host,$username,$password,$link;
    $link = mysqli_connect($host,$username,$password);
    mysqli_query($link,'set names utf8');
    mysqli_query($link,'use ec');
}

function sess_close(){
    echo __FUNCTION__;
}

function sess_read($sess_id){
    echo __FUNCTION__;
    global $host,$username,$password,$link;
    $sql = "select sess_data from `session` where sess_id = '$sess_id'";
    $result = mysqli_query($sql);  // $link 可以自己找到，或可以声明为全局变量
    if($rows = mysqli_fetch_assoc($result)){
        return $rows['sess_data'];
    }else{
        return '';
    }
}

function sess_write($sess_id, $sess_data){
    echo __FUNCTION__;
    if (empty($sess_data))
        return true;

    //--
    $link = get_link();
    $result = mysqli_query($link,"select * from session WHERE sess_id='{$sess_id}'");
    if($result && $result->num_rows > 0)
        $sql = "update `session` set sess_id='$sess_id', sess_data='$sess_data', times=now() WHERE sess_id='{$sess_id}'";  //这是为了gc()
    else
        $sql = "insert into `session` values('$sess_id', '$sess_data', now())";  //这是为了gc()
    return mysqli_query($link,$sql);
}

function sess_destroy($sess_id){
    echo __FUNCTION__;
    $link = get_link();
    $sql = "delete from `session` where sess_id = '$sess_id'";
    return mysqli_query($link,$sql);
}

function sess_gc(){
    echo __FUNCTION__;
}


//var_dump(session_save_path());

session_set_save_handler('sess_open','sess_close','sess_read','sess_write','sess_destroy','sess_gc');


session_start();

//$_SESSION['name'] = 'wjh';
//$_SESSION['age'] = 15;
//$_SESSION['add'] = 'bj';

var_dump($_SESSION['name']);

//session_destroy();


