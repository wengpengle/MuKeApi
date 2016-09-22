<?php
/**
 * Created by PhpStorm.
 * User: [翁鹏乐]
 * Date: 2016/9/20
 * Time: 8:36
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends CommonController{

    /*
     * 检测 【用户登录】
     */
    public function CheckLogin( Request $request ){
        #接收的数据
        $data = $request -> all();

        $result = CheckParam($data);
        if( $result ){
            #验证用户的手机号
            if( !preg_match("/^1[34578]\d{9}$/", $data['phone']) ){
               return $this -> defeat( 1 , '手机号码格式不正确' );
            }
            #查询用户的数据信息
            $userInfo = DB::table('username') -> where( 'phone', $data['phone'] ) -> first();
            #判断用户名是否存在
            if( $userInfo ){
                #检测用户跟密码是否匹配
                if( md5($data['password']) == md5($userInfo['password'])){
                    #成功放回用户的信息
                   return $this -> success( $userInfo );
                }else{
                   return $this -> defeat( 1, '用户名和密码不匹配' );
                }
            }else{
               return $this -> defeat( 1, '用户名不存在' );
            }
        }else{
            debug($result);
        }

    }

    /*
     * 检测 【用户注册】
     */
    public function CheckUser( Request $request ){
        #接收用户数据
        $data = $request -> all();
        CheckParam($data);
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

}