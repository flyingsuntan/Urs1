<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/28
 * Time: 15:05
 */
//批量实体转义
function deepspecialchars($data){
    if(empty($data)){
        return $data;
    }
    return is_array($data) ?  array_map('deepspecialchars',$data) :  htmlspecialchars($data);

}
function deepaddslashes($data){
    if(empty($data)){
        return $data;
    }
    return is_array($data) ?  array_map('deepaddslashes',$data) :  addslashes($data);
}