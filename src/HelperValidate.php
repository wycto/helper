<?php
/**
 * 验证助手类
 * @author : weiyi <294287600@qq.com>
 * Licensed ( http://www.wycto.com )
 * Copyright (c) 2016~2099 http://www.wycto.com All rights reserved.
 */
namespace wycto\helper;

class HelperValidate{

    /**
     * 检查邮箱是否正确
     *
     * @return boolean
     */
    static function checkEmail($email){

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
    static function checkMobile($mobile){

        $num = preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/", $mobile, $match);
        if($num == 0){
            return false;
        }else{
            return true;
        }
    }

}