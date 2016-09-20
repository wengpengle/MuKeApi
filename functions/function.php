<?php
/**
 * Created by PhpStorm.
 * User: [翁鹏乐]
 * Date: 2016/9/20
 * Time: 10:24
 */

/**
 *  自定义打印函数
 */
function debug($val,$dump = false, $exit = true){
    //自动获取调试函数名称
    if( $dump ){
        $func = 'var_dump';
    }else{
        $func = ( is_array($val) || is_object($val) ) ? 'print_r' : 'print_r';
    }

    //输出到html页面
    header('content-type:text/html;charset=UTF-8');

    echo '<pre> debug output:<hr/>';
    $func($val);
    echo '</pre>';

    if($exit) exit();
}