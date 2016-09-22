<?php
/**
 * Created by PhpStorm.
 * User: [赵家彬]
 * Date: 2016/9/20
 * Time: 8:36
 */

namespace App\Http\Controllers;
use Request;
use DB;
/*
	加薪利器模块API
 */
class RaisesController extends CommonController{
	/*
		加薪利器首页
	 */
	public function index(){
		// type 0全部 1前端 2后端 3移动 4整站
		$type=Request::input('type');
		// 查询课程体系
		if(!isset($type)||$type>5){
			return $this->defeat(1,'缺少参数或参数无效');
		}else{
			if($type==0){
				$arr=DB::table('raises_class_one')
				->get();
			}else{
				$arr=DB::table('raises_class_one')
				->where('type_name','=',$type)
				->get();
			}
			return $this->defeat(0,'Request is OK',$arr);
		}
	}
}