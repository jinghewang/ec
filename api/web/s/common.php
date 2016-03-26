<?php
/**
 * Created by PhpStorm.
 * User: hltravel
 * Date: 16/3/25
 * Time: 下午5:17
 */


$host = '127.0.0.1';
$db = 'ec';
$username = 'root';
$password = 'root';
$link = null;


function get_link(){
    global $host,$username,$password,$link;
    $link = mysqli_connect($host,$username,$password);
    mysqli_query($link,'set names utf8');
    mysqli_query($link,'use ec');
    return $link;
}


/**
 * override print_r
 * @author wjh 2014-7-1
 * @param $expression
 * @param null $return
 */
function print_r2($expression,$expression2=null,$expression3=null,$expression4=null,$expression5=null,$expression6=null,$expression7=null, $return = null){
    echo '<pre>';
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
    echo '</pre>';
}