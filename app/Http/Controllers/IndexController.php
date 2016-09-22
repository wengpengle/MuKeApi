<?php
/**
 * Created by PhpStorm.
 * User: [翁鹏乐]
 * Date: 2016/9/20
 * Time: 19:39
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

class IndexController extends CommonController{

    /*
     * 首页 【首页的列表页】
     */
    public function indexList(){
        
    }

    /*
     * 首页 【课程分类】
     */
    public function index(){
        $course_type = DB::table('course_type') -> get();
        $typeData = $this -> get_tree($course_type);
        debug($typeData);
        #调用函数转换为JSON格式的数据
        //$this -> success(1,$typeData);
    }

    /*
     * 实现 课程章节递归
     */
    public function get_tree( $array,$parent_id=0){
        $new_array = array();
        foreach( $array as $key => $value ){
            if( $value['parent_id'] == $parent_id ){
                $new_array[$key] = $value;
                $new_array[$key]['son'] = $this->get_tree( $array, $value['type_id']);
            }
        }
        return $new_array;
    }


}