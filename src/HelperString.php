<?php
/**
 * 字符串助手类
 * @author : weiyi <294287600@qq.com>
 * Licensed ( http://www.wycto.com )
 * Copyright (c) 2016~2099 http://www.wycto.com All rights reserved.
 */
namespace wycto\helper;

class HelperString
{

    /**
     * 字符串截取
     * @param unknown $string
     * @param unknown $length
     * @param string $dot
     * @param string $charset
     * @return string
     */
    static function c($string, $length, $dot = '...', $charset = 'utf-8') {

        $string = str_replace('&nbsp;', ' ', strip_tags($string));
        $string = str_replace(array(
            '&',
            '"',
            '<',
            '>',
            '\''
        ), array(
            '＆;',
            '＂',
            '＜',
            '＞',
            '＇'
        ), $string);
        return mb_substr($string, 0, $length, $charset) . (mb_strlen($string, $charset) > $length ? $dot : '');
    }

    /**
     * 获得唯一字符串
     *
     * @return string
     */
    static function unique_id() {
        srand((double) microtime() * 1000000);
        return md5(uniqid(rand()));
    }

}
