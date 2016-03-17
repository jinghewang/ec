<?php

/**
 * Global Function
 * @param $expression
 * @param null $expression2
 */


/**
 * var_dump and die
 * @param $expression
 * @param null $expression2
 */
function var_dump_die ($expression, $expression2 = null) {
    echo '<pre>';
    if (empty($expression2))
        var_dump($expression);
    else
        var_dump($expression,$expression2);
    echo '</pre>';
    die;
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

