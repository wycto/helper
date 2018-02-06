<?php
/**
 * 时间助手类
 * @author : weiyi <294287600@qq.com>
 * Licensed ( http://www.wycto.com )
 * Copyright (c) 2016~2099 http://www.wycto.com All rights reserved.
 */
namespace wycto\helper;

class HelperDateTime
{
    /**
     * 时间显示函数t
     * @param int or string $unixtime 时间戳或者时间字符串
     * @param int $limit 相差时间间隔
     * @param string $format 超出时间间隔的日期显示格式
     * @return string 返回需要的时间格式
     */
    static function showtime($unixtime, $limit = 18000, $format = "Y-m-d"){

        $nowtime = time();
        $showtime = "";
        if(!is_int($unixtime)){
            $unixtime = strtotime($unixtime);
        }
        $differ = $nowtime - $unixtime;
        if($differ >= 0){
            if($differ > $limit){
                $showtime = date($format, $unixtime);
            }else{
                $showtime = $differ > 86400 ? floor($differ / 86400) . "天前" : ($differ > 3600 ? floor($differ / 3600) . "小时前" : floor($differ / 60) . "分钟前");
            }
        }else{
            if(-$differ > $limit){
                $showtime = date($format, $unixtime);
            }else{
                $showtime = -$differ > 86400 ? floor(-$differ / 86400) . "天" : (-$differ > 3600 ? floor(-$differ / 3600) . "小时" : floor(-$differ / 60) . "分钟");
            }
        }
        return $showtime;
    }

    /**
     * 获取当前时间和参数时间相差的天数
     * @param unknown $timestamp 参数时间戳
     */
    static function getDay($timestamp){

        //当前时间  年月日
        $nowday = date("Y-m-d");

        //系统时间  年月日
        $sysday = date("Y-m-d",$timestamp);

        //时间差
        $day = strtotime($nowday) - strtotime($sysday);

        //转换天数
        $day = $day/86400;
        return $day;
    }
}
