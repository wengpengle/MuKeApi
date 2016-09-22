<?php
/**
 * Created by PhpStorm.
 * User: [翁鹏乐]
 * Date: 2016/9/20
 * Time: 8:38
 */

namespace App\Http\Controllers;

class CommonController extends Controller{
    /*
     * 失败输出
     */
    public function defeat( $errorStatus=1,$errorMsg='ERROR',$errorData=array(),$otherData=array() ){

        #拼装数据
        $errorArray = array();

        #失败的状态吗
        $errorArray['status'] = $errorStatus;

        #失败的提示信息
        $errorArray['msg'] = $errorMsg;

        #失败时返回的错误数据
        $errorArray['data'] = $errorData;

        $this -> JsonOutPut( $errorArray , $otherData);

    }

    /*
     *成功输出
     */
    public function success( $data=array(),  $successStatus=0, $successMsg='SUCCESS', $otherData=array() ){

        #拼装数据
        $successArray = array();

        #失败的状态吗
        $successArray['status'] = $successStatus;

        #失败的提示信息
        $successArray['msg'] = $successMsg;

        #失败时返回的错误数据
        $successArray['data'] = $data;

        $this -> JsonOutPut( $successArray , $otherData );

    }


    /*
     * 处理数据为JSON格式
     *
     */
    public function JsonOutPut( $array = array(), $otherData ){

        #判断数据是否为一个数组
        if( !is_array( $array ) ){
            #不是一个数组则转换为数组
            $array = (array)$array;
        }

        #合并要返回的数据
        $array = array_merge($array, (array)$otherData);

        #返回的JSON数据
        $jsonData = json_encode($array,true);

        echo $jsonData;
        exit();
    }

}