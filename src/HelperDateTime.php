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

    /**
     * 时间差计算
     * @param int $timestamp
     * @return string
     */
    static function round_time($timestamp) {

        $now = CURRENT_TIMESTAMP;
        $time = $timestamp - $now;

        if ($time > 0) {
            $suffix = '之后';
        }
        else {
            $suffix = '之前';
        }

        $time = abs($time);
        if ($time < 60) {
            $fix_time = '秒';
            $round_time = $time;
        }
        elseif ($time < 3600) {
            $fix_time = '分钟';
            $round_time = round($time / 60);
        }
        elseif ($time < 3600 * 24) {
            $fix_time = '小时';
            $round_time = round($time / 3600);
        }
        elseif ($time < 3600 * 24 * 7) {
            $fix_time = '天';
            $round_time = round($time / (3600 * 24));
        }
        elseif ($time < 3600 * 24 * 30) {
            $fix_time = '周';
            $round_time = round($time / (3600 * 24 * 7));
        }
        elseif ($time < 3600 * 24 * 365) {
            $fix_time = '个月';
            $round_time = round($time / (3600 * 24 * 30));
        }
        elseif ($time >= 3600 * 24 * 365) {
            $fix_time = '年';
            $round_time = round($time / (3600 * 24 * 365));
        }

        return $round_time . ' ' . $fix_time . $suffix;
    }
}
