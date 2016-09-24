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
			return $this->defeat(1,'A lack of parameters or invalid');
		}else{
			if($type==0){
				$arr=DB::table('raises_class_three')
				->join('raises_class_two','raises_class_three.rct_id','=','raises_class_two.rct_id')
				->join('raises_class_one','raises_class_two.rco_id','=','raises_class_one.rco_id')
				->select('raises_class_one.rco_id')
				->get();
				foreach($arr as $k=>$v){
					$new_arr[]=$v['rco_id'];
				}
				$count=array_count_values($new_arr);

				$res=DB::table('raises_class_one')
					->get();
				foreach($res as $k=>$v){
					$rco_id=$v['rco_id'];
					if(array_key_exists($rco_id,$count)){
						$res[$k]['count']=$count[$rco_id];
					}else{
						$res[$k]['count']=0;
					}
				}
			}else{
				$res=DB::table('raises_class_one')
				->where('type_name','=',$type)
				->get();
			}
			return $this->success($res,0,'Request is OK');
		}
	}
	/*
		进入课程体系的页面
	 */
	public function second_page(){
		$rco_id=Request::input('rco_id');
		if(isset($rco_id)){
	    	$rct_arr=DB::table('raises_class_two')->get();
	    	$rco_title=DB::table('raises_class_one')->select('rco_title')->where('rco_id',$rco_id)->get();
	    	foreach($rct_arr as $k=>$v){
	    		$rct_id=$v['rct_id'];
	    		$th_arr=DB::table('raises_class_three')->where('rct_id','=',$rct_id)->get();
	    		$rct_arr[$k]['th_list']=$th_arr;
	    	}
	    	return $this->success($rct_arr,0,'Request is OK',$rco_title[0]);
		}else{
			return $this->defeat(1,'A lack of parameters or invalid');
		}
	}
	/*
		点击课程进入章节  播放页面
	 */
	public function play(){
		$th_id=Request::input('th_id');
		if(isset($th_id)){
			$res=DB::table('raises_part')
				->leftjoin('raises','raises_part.cp_id','=','raises.cp_id')
				->where('raises_part.th_id','=',$th_id)
				->select('raises_part.*','raises.rai_pic','raises.rai_video','rai_time')
				->get();
		$arr=$this->get_tree($res);
			$data=DB::table('raises_class_three')
				->leftjoin('raises_class_two','raises_class_three.rct_id','=','raises_class_two.rct_id')
				->where('raises_class_three.th_id','=',$th_id)
				->select('raises_class_two.rct_desc','raises_class_three.th_title')
				->first();
		$result['chapter']=$arr;
		$result['details']=$data;
		return $this->success($result,0,'success');
		}else{
			return $this->defeat(1,'A lack of parameters or invalid');
		}
	}
	/*
     * 实现 课程分类递归
     */
    public function get_tree( $array,$parent_id=0){
        $new_array = array();
        foreach( $array as $key => $value ){
            if( $value['parent_id'] == $parent_id ){
                $new_array[$key] = $value;
                $new_array[$key]['son'] = $this->get_tree( $array, $value['cp_id']);
            }
        }
        return $new_array;
    }
}