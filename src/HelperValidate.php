<?php
/**
 * 验证助手类
 * @author : weiyi <294287600@qq.com>
 * Licensed ( http://www.wycto.com )
 * Copyright (c) 2016~2099 http://www.wycto.com All rights reserved.
 */
namespace wycto\helper;

class HelperValidate
{

    /**
     * 检查邮箱是否正确
     *
     * @return boolean
     */
    static function checkEmail($email)
    {
        $num = preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $email, $match);
        
        if($num == 0){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 检查手机号码是否正确
     *
     * @return boolean
     */
    static function checkMobile($mobile)
    {
        $num = preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/", $mobile, $match);
        if($num == 0){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 校验身份证
     */
    static function checkIdCard($idc)
    {
        if(empty($idc)){
            return false;
        }
        
        $idcard = $idc;
        $City = array(
            11 => "北京",
            12 => "天津",
            13 => "河北",
            14 => "山西",
            15 => "内蒙古",
            21 => "辽宁",
            22 => "吉林",
            23 => "黑龙江",
            31 => "上海",
            32 => "江苏",
            33 => "浙江",
            34 => "安徽",
            35 => "福建",
            36 => "江西",
            37 => "山东",
            41 => "河南",
            42 => "湖北",
            43 => "湖南",
            44 => "广东",
            45 => "广西",
            46 => "海南",
            50 => "重庆",
            51 => "四川",
            52 => "贵州",
            53 => "云南",
            54 => "西藏",
            61 => "陕西",
            62 => "甘肃",
            63 => "青海",
            64 => "宁夏",
            65 => "新疆",
            71 => "台湾",
            81 => "香港",
            82 => "澳门",
            91 => "国外"
        );
        
        $iSum = 0;
        $idCardLength = strlen($idcard);
        // 长度验证
        
        if(!preg_match('/^\d{17}(\d|x)$/i', $idcard) and !preg_match('/^\d{15}$/i', $idcard)){
            return false;
        }
        
        // 地区验证
        if(!array_key_exists(intval(substr($idcard, 0, 2)), $City)){
            return false;
        }
        
        // 15位身份证验证生日，转换为18位
        if($idCardLength == 15){
            $sBirthday = '19' . substr($idcard, 6, 2) . '-' . substr($idcard, 8, 2) . '-' . substr($idcard, 10, 2);
            if($sBirthday != $sBirthday){
                return false;
            }
            $idcard = substr($idcard, 0, 6) . "19" . substr($idcard, 6, 9); // 15to18
            $Bit18 = self::getVerifyBit($idcard); // 算出第18位校验码
            $idcard = $idcard . $Bit18;
        }
        
        // 判断是否大于2078年，小于1900年
        $year = substr($idcard, 6, 4);
        if($year < 1900 || $year > 2078){
            return false;
        }
        
        // 18位身份证处理
        $sBirthday = substr($idcard, 6, 4) . '-' . substr($idcard, 10, 2) . '-' . substr($idcard, 12, 2);
        if($sBirthday != $sBirthday){
            return false;
        }
        
        // 身份证编码规范验证
        $idcard_base = substr($idcard, 0, 17);
        if(strtoupper(substr($idcard, 17, 1)) != self::getVerifyBit($idcard_base)){
            return false;
        }
        
        return true;
    }
    
    // 计算身份证校验码，根据国家标准GB 11643-1999
    static function getVerifyBit($idcard_base)
    {
        if(strlen($idcard_base) != 17){
            return false;
        }
        // 加权因子
        $factor = array(
            7,
            9,
            10,
            5,
            8,
            4,
            2,
            1,
            6,
            3,
            7,
            9,
            10,
            5,
            8,
            4,
            2
        );
        // 校验码对应值
        $verify_number_list = array(
            '1',
            '0',
            'X',
            '9',
            '8',
            '7',
            '6',
            '5',
            '4',
            '3',
            '2'
        );
        $checksum = 0;
        for($i = 0; $i < strlen($idcard_base); $i++){
            $checksum += substr($idcard_base, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];
        return $verify_number;
    }
}
