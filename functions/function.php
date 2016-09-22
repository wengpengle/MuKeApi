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

    echo '<pre> debug output:【提 示 信 息 参 考 ↓】<hr/>';
    $func($val);
    echo '</pre>';

    if($exit) exit();
}

/**
 * 检测用户登录的方法
 */
function CheckUser( $status, $error_msg, $data = array()){
    $arr = [
        'status' => $status,
        'msg' => $error_msg,
        'data' => $data,
    ];
    echo json_encode($arr , JSON_UNESCAPED_UNICODE );
    exit;
}

/*
 * 判断请求接口是否有传递参数
 */
function CheckParam( $param ){
    #判断参数是否为空
    if( empty($param) ){
        $msg = [
            'msg' => '友情提示------->请求接口 请您传递接口所需要的对应参数 O(∩_∩)O~',
        ];
        return json_encode($msg , JSON_UNESCAPED_UNICODE );
        exit;
    }else{
        return true;
    }
}


/*
 *  登录成功则生成一个 token
 */
function login_token($length){
    $str = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $login_token = '';
    //当前字符串长度减去 1
    $len = strlen($str)-1;
    //根据传递过来的数据循环
    for($i=0;$i<$length;$i++){
        //在这个范围之内随机抽取
        $num = mt_rand(0,$len);
        $login_token .= $str[$num];
    }
    //返回值
    return $login_token ;
}