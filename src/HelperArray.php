<?php
/**
 * 数组助手类
 * @author : weiyi <294287600@qq.com>
 * Licensed ( http://www.wycto.cn )
 * Copyright (c) 2016~2099 http://www.wycto.cn All rights reserved.
 */
namespace wycto\helper;

class HelperArray
{
    /**
    *移除数组中值为空的数据
    */
    static function removeEmpty(& $arr, $trim = true) {

        foreach ( $arr as $key => $value ) {
            if (is_array ( $value )) {
                self::removeEmpty ( $arr [$key] );
            } else {
                $value = trim ( $value );
                if ($value == '') {
                    unset ( $arr [$key] );
                } elseif ($trim) {
                    $arr [$key] = $value;
                }
            }
        }
    }

    /**
    *移除数组中指定键的数据
    */
    static function removeKey(&$array, $keys) {

        if (! is_array ( $keys )) {
            $keys = array (
                $keys
            );
        }
        return array_diff_key ( $array, array_flip ( $keys ) );
    }

    /**
     * 将一个平面的二维数组按照指定的字段转换为树状结构
     * @param array $arr 数据源
     * @param string $key_node_id 节点ID字段名
     * @param string $key_parent_id 节点父ID字段名
     * @param string $key_childrens 保存子节点的字段名
     * @param boolean $refs 是否在返回结果中包含节点引用
     * return array 树形结构的数组
     */
    static function toTree($arr, $key_node_id, $key_parent_id = 'parent_id', $key_childrens = 'children', $treeIndex = false, & $refs = null) {

    	$refs = array();
    	foreach ($arr as $offset => $row) {
    		$arr[$offset][$key_childrens] = array();
    		$refs[$row[$key_node_id]] = & $arr[$offset];
    	}

    	$tree = array();
    	foreach ($arr as $offset => $row) {
    		$parent_id = $row[$key_parent_id];
    		if ($parent_id) {
    			if (!isset($refs[$parent_id])) {
    				if ($treeIndex) {
    					$tree[$offset] = & $arr[$offset];
    				}
    				else {
    					$tree[] = & $arr[$offset];
    				}
    				continue;
    			}
    			$parent = & $refs[$parent_id];
    			if ($treeIndex) {
    				$parent[$key_childrens][$offset] = & $arr[$offset];
    			}
    			else {
    				$parent[$key_childrens][] = & $arr[$offset];
    			}
    		}
    		else {
    			if ($treeIndex) {
    				$tree[$offset] = & $arr[$offset];
    			}
    			else {
    				$tree[] = & $arr[$offset];
    			}
    		}
    	}

    	return $tree;
    }

    /**
    * 将数组按照键值转换成数组
    */
    static function toHashmap($arr, $key_field, $value_field = null) {

    	$ret = array ();
    	if (empty ( $arr )) {
    		return $ret;
    	}
    	if ($value_field) {
    		foreach ( $arr as $row ) {
    			if (isset ( $row [$key_field] )) {
    				$ret [$row [$key_field]] = isset($row [$value_field])?$row [$value_field]:'NULL';
    			}
    		}
    	} else {
    		foreach ( $arr as $row ) {
    			$ret [$row [$key_field]] = $row;
    		}
    	}
    	return $ret;
    }

    /**
     * 将数组用分隔符连接并输出
     * @param $array
     * @param $separator
     * @param $find
     * @return string
     */
    static function toString($array, $separator = ',', $find = '') {

    	$str = '';
    	$separator_temp = '';

    	if (! empty ( $find )) {
    		if (! is_array ( $find )) {
    			$find = self::toArray ( $find );
    		}
    		foreach ( $find as $key ) {
    			$str .= $separator_temp . $array [$key];
    			$separator_temp = $separator;
    		}
    	} else {
    		foreach ( $array as $value ) {
    			$str .= $separator_temp . $value;
    			$separator_temp = $separator;
    		}
    	}
    	return $str;
    }

    /**
     * 从一个二维数组中返回指定键的所有值
     * @param array $arr 数据源
     * @param string $col 要查询的键
     * @return array 包含指定键所有值的数组
     */
    static function getCols($arr, $col) {

    	$ret = array ();
    	foreach ( $arr as $row ) {
    		if (isset ( $row [$col] )) {
    			$ret [] = $row [$col];
    		}
    	}
    	return $ret;
    }
}
