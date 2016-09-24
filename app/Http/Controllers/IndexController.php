<?php
/**
 * Created by PhpStorm.
 * User: [翁鹏乐]
 * Date: 2016/9/20
 * Time: 19:39
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends CommonController{

    /*
     * 首页
     */
    public function index(Request $request){
        $param = $request -> all();
        # 取出数组中的键名 将数组转化为字符串
        $params = implode('',array_keys($param));
        switch($params)
        {
            case 'type':
                #首页课程信息列表
                return $this -> getAllCourse($param);
                break;
            case 'param':
                #首页  分类数据 和 课程数据
                return $this -> getIndex($param);
                break;
            case 'grade':
                #首页  分类数据 和 课程数据
                return $this -> getGradeCourse($param);
                break;
            case 'detail':
                return $this -> getCourseDetails($param);
                break;
            default:
                return $this -> defeat(1,'请传递参数');
        }
    }

    /*
     * 【封装 查询首页  分类、课程数据的方法】
     */
    public function getIndex($param){
        #首先判断是否是一个数组
        if(array_key_exists('param',$param)){
            switch ($param['param'])
            {
                case '1':
                    #课程分类
                    $typeData = $this -> course_type();
                    #首页课程信息列表
                    $courseAll = $this -> course_all();
                    #调用函数转换为JSON格式的数据
                    $array['course_type'] = $typeData;
                    $array['course'] = $courseAll;
                    #掉用函数转换格式
                    return $this -> success($array);
                    break;
                case '2':
                    #首页课程信息列表
                    $courseAll = $this -> course_all();
                    #掉用函数转换格式
                    return $this -> success($courseAll);
                    break;
                default:
                    return $this -> defeat(1,'没有传递参数(⊙o⊙)哦');
            }
        }else{
            return $this -> defeat(1,'传递的数据类型不正确');
        }
    }


    /*
     * 【首页】 首页课程的列表
     */
    public function course_all(){
        #根据添加的时间来 排序
        $course = DB::table('course') ->orderBy('cou_time', 'desc')
            -> get();
        return $course;
    }

    /*
     * 【首页】 查询课程分类的方法
     */
    public function course_type(){
        #查询课程分类
        $course_type = DB::table('course_type') -> get();
        #调用函数  处理课程分类的层级关系
        $typeData = $this -> get_tree($course_type);
        return $typeData;
    }

    /*
     * 【首页】实现课程分类递归
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


    /*
     * 【分类页面】 单个分类 所对应的课程数据
     */
    public function getAllCourse( $param ){
        if(!empty($param['type'])){
            $courseType = DB::table('course') -> where('type_id',$param['type']) -> get();
            #掉用函数转换格式
            return $this -> success($courseType);
        }else{
            return $this -> defeat(1,'没有传递参数(⊙o⊙)哦');
        }
    }

    /*
     * 【点击单个分类名称进入页面之后】  通过课程等级所查询的数据信息
     */
    public function getGradeCourse( $param ){
        if(!empty($param['grade'])){
            $courseType = DB::table('course') -> where('cou_grade',$param['grade']) -> get();
            #掉用函数转换格式
            return $this -> success($courseType);
        }else{
            return $this -> defeat(1,'没有传递课程等级 cou_grade 参数(⊙o⊙)哦');
        }
    }

    /*
     * 【点击 单个分类名称进入页面之后、再点击 单个 课程名称 按钮】  所查询该课程的详情【1】
     */
    public function getCourseDetails($param){
        if(!empty($param['detail'])){
            # 第一步 查询单个课程下 包括 具体的 小课程
            $course['course'] = DB::table('course_class')  -> where('cc_id',$param['detail']) -> get();
            # 第二步 根据小课程的ID 查询出该可查 所有的章节
            $coursePart = DB::table('course_class') -> where('cc_id',$param['detail'])
                -> lists('cou_id','cou_id');
            $courseParts = DB::table('course_part') -> where('cou_id',$coursePart) -> get();
            #调用函数 实现章节的递归
            $course['part'] = $this -> course_class_part( $courseParts );
            # 第三步 查询单个课程的详情
            $course['detail'] = DB::table('course')
                ->select(DB::raw('cou_name,cou_desc'))
                ->where('cou_id',$coursePart)
                ->get();
            #掉用函数转换格式
            return $this -> success($course);
        }else{
            return $this -> defeat(1,'没有课程的ID cou_id 参数(⊙o⊙)哦');
        }
    }

    /*
     * 【点击 单个分类名称进入页面之后、再点击 单个 课程名称 按钮】  所查询该课程的详情 【2】
     */
    public function course_class_part( $array,$parent_id=0){
        $new_array = array();
        foreach( $array as $key => $value ){
            if( $value['parent_id'] == $parent_id ){
                $new_array[$key] = $value;
                $new_array[$key]['son'] = $this->course_class_part( $array, $value['cp_id']);
            }
        }
        return $new_array;
    }
}