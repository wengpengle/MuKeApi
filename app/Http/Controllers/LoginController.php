<?php
/**
 * Created by PhpStorm.
 * User: [翁鹏乐]
 * Date: 2016/9/20
 * Time: 8:36
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

class LoginController extends CommonController{

    /*
     * 检测 【用户登录】
     */
    public function CheckLogin( Request $request ){
        #接收的数据
        $data = $request -> all();
        #验证用户的手机号
        if( !preg_match("/^1[34578]\d{9}$/", $data['phone']) ){
            $this -> defeat( 1 , '手机号码格式不正确' );
        }
        #查询用户的数据信息
        $userInfo = DB::table('username') -> where( 'phone', $data['phone'] ) -> first();
        #判断用户名是否存在
        if( $userInfo ){
            #检测用户跟密码是否匹配
            if( $data['password'] == $userInfo['password'] ){
                #成功放回用户的信息
                $this -> success( $userInfo );
            }else{
                $this -> defeat( 1, '用户名和密码不匹配' );
            }
        }else{
            $this -> defeat( 1, '用户名不存在' );
        }
    }

    /*
     * 检测 【用户注册】
     */
    public function CheckUser( Request $request ){
        #接收用户数据
        $data = $request -> all();
        #检测用户的手机号
        if( !preg_match("/^1[34578]\d{9}$/", $data['phone']) ){
            $this -> defeat( 1 , '手机号码格式不正确' );
        }
        #检测用户的唯一性
        $userInfo = DB::table('username') -> where( 'phone', $data['phone'] ) -> first();
        if( empty( $userInfo ) ){
            #随机生成一个用户名
            $data['nickname'] = '慕粉儿'.'-'.time();
            $data['password'] = md5( $data['password'] );
            #随机给用户一张头像
            $data['user_head'] = 'public/heads/'.rand(1,15).'.jpg';
            #执行入库操作
            $result = DB::table('username') -> insert( $data );
            #判断入库是否成功
            if( $result ){
                #注册成功返回的用户ID
                $this -> success( $result );
            }else{
                $this -> defeat( 1 , '注册失败、出现异常' );
            }
        }else{
            $this -> defeat( 1 , '该手机号已存在、不可以重复注册' );
        }
    }


    /*
     *  登录成功则生成一个 login_token【1】
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

}