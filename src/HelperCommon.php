<?php
/**
 * 通用助手类
 * @author : weiyi <294287600@qq.com>
 * Licensed ( http://www.wycto.com )
 * Copyright (c) 2016~2099 http://www.wycto.com All rights reserved.
 */
namespace wycto\helper;

class HelperCommon
{
    /**
     * 返回客户端IP地址
     *
     * @return string
     */
    static function getip(){

        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')){
            $onlineip = getenv('HTTP_CLIENT_IP');
        }elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')){
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        }elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')){
            $onlineip = getenv('REMOTE_ADDR');
        }elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')){
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
        return $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
    }

    /**
     * [图片显示方法，如果图片不存在显示默认图]
     * @param  [string] $src     [原地址]
     * @param  string $default [默认地址]
     * @param  string $thumb   [缩略图文件夹名称]
     * @return [string]          [返回图片地址]
     */
    static function showImg($src, $default = '', $thumb = '') {
        if (!(stripos($src, 'http://') === false && stripos($src, 'https://') === false)) {
            return $src;
        }
        if (!trim($src)) {
            return $default ? $default : "";
        }
        if (!preg_match('/^[\/]/', $src)) {
            $src = '/' . $src;
        }

        $exists_src = $src_thumb = $src;
        $exists_src_thumb = "";

        // 是否显示缩略图
        if ($thumb) {
            /*$path = $thumb == 2 ? "/_large/" : "/_thumb/";*/
            $path = $thumb;
            $p = strripos($exists_src, '/');
            $b = substr($exists_src, 0, $p);
            $e = substr($exists_src, $p + 1);
            $exists_src_thumb = $b . $path . $e;
            $src_thumb = $exists_src_thumb;

            $first_cahr = substr($exists_src_thumb, 0, 1);
            if ($first_cahr != '.') {
                $exists_src_thumb = "." . $exists_src_thumb;
            }
        }

        if (!file_exists($exists_src_thumb)) {
            // 缩略图不存在，则显示大图
            $first_cahr = substr($exists_src, 0, 1);
            if ($first_cahr != '.') {
                $exists_src = "." . $exists_src;
            }

            if (!file_exists($exists_src)) {
                $src = $default ? $default : "";
            }
        } else {
            // 缩略图存在显示缩略图
            $src = $src_thumb;
        }

        $first_cahr = substr($src, 0, 1);
        if ($first_cahr == '.') {
            $src = substr($src, 1);
        }

        return $src;
    }

    /**
     * 数据显示方法
     * @param  string $data    原始数据
     * @param  string $default 默认数据
     * @return string          处理后的数据
     */
    static function showdata($data, $default="") {
        return $data ? $data : $default;
    }

    /**
     * 获取随机数
     * @param  integer  $length  长度
     * @param  integer $numeric 是否包含字母
     * @return string           随机数
     */
    static function random($length, $numeric = 0) {
        PHP_VERSION < '4.2.0' ? mt_srand((double) microtime() * 1000000) : mt_srand();
        $seed = base_convert(md5(print_r($_SERVER, 1) . microtime()), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        $hash = '';
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i ++) {
            $hash .= $seed[mt_rand(0, $max)];
        }
        return $hash;
    }
}
