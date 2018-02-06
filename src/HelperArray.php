<?php
/**
 * 数组助手类
 * @author : weiyi <294287600@qq.com>
 * Licensed ( http://www.wycto.com )
 * Copyright (c) 2016~2099 http://www.wycto.com All rights reserved.
 */
namespace wycto\helper;

class HelperArray
{

    /**
     * 从数组中删除空白的元素（包括只有空白字符的元素）
     *
     * 用法：
     * @code php
     * $arr = array('', 'test', ' ');
     * Helper_Array::removeEmpty($arr);
     *
     * dump($arr);
     * // 输出结果中将只有 'test'
     * @endcode
     *
     * @param array $arr
     *        	要处理的数组
     * @param boolean $trim
     *        	是否对数组元素调用 trim 函数
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
     * 去掉指定的项
     *
     * @author sqlhost
     * @version 1.0.0
     *          2012-4-11
     *
     *          同时兼容字符串和数组
     *
     * @author sqlhost
     * @version 1.0.1
     *          2012-4-19
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
     *
     * 用法：
     * @code php
     * $rows = array(
     * array('id' => 1, 'value' => '1-1', 'parent' => 0),
     * array('id' => 2, 'value' => '2-1', 'parent' => 0),
     * array('id' => 3, 'value' => '3-1', 'parent' => 0),
     *
     * array('id' => 7, 'value' => '2-1-1', 'parent' => 2),
     * array('id' => 8, 'value' => '2-1-2', 'parent' => 2),
     * array('id' => 9, 'value' => '3-1-1', 'parent' => 3),
     * array('id' => 10, 'value' => '3-1-1-1', 'parent' => 9),
     * );
     *
     * $tree = Helper_Array::tree($rows, 'id', 'parent', 'nodes');
     *
     * dump($tree);
     * // 输出结果为：
     * // array(
     * // array('id' => 1, ..., 'nodes' => array()),
     * // array('id' => 2, ..., 'nodes' => array(
     * // array(..., 'parent' => 2, 'nodes' => array()),
     * // array(..., 'parent' => 2, 'nodes' => array()),
     * // ),
     * // array('id' => 3, ..., 'nodes' => array(
     * // array('id' => 9, ..., 'parent' => 3, 'nodes' => array(
     * // array(..., , 'parent' => 9, 'nodes' => array(),
     * // ),
     * // ),
     * // )
     * @endcode
     *
     * 如果要获得任意节点为根的子树，可以使用 $refs 参数：
     * @code php
     * $refs = null;
     * $tree = Helper_Array::tree($rows, 'id', 'parent', 'nodes', $refs);
     *
     * // 输出 id 为 3 的节点及其所有子节点
     * $id = 3;
     * dump($refs[$id]);
     * @endcode
     *
     * @param array $arr
     *        	数据源
     * @param string $key_node_id
     *        	节点ID字段名
     * @param string $key_parent_id
     *        	节点父ID字段名
     * @param string $key_childrens
     *        	保存子节点的字段名
     * @param boolean $refs
     *        	是否在返回结果中包含节点引用
     *
     *        	return array 树形结构的数组
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

    static function toHashmap($arr, $key_field, $value_field = null) {

    	$ret = array ();
    	if (empty ( $arr )) {
    		return $ret;
    	}
    	if ($value_field) {
    		foreach ( $arr as $row ) {
    			if (isset ( $row [$key_field] )) {
    				$ret [$row [$key_field]] = $row [$value_field];
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
     *
     * @param
     *        	$array
     * @param
     *        	$comma
     * @param
     *        	$find
     * @return string
     */
    static function toString($array, $comma = ',', $find = '') {

    	$str = '';
    	$comma_temp = '';

    	if (! empty ( $find )) {
    		if (! is_array ( $find )) {
    			$find = self::toArray ( $find );
    		}
    		foreach ( $find as $key ) {
    			$str .= $comma_temp . $array [$key];
    			$comma_temp = $comma;
    		}
    	} else {
    		foreach ( $array as $value ) {
    			$str .= $comma_temp . $value;
    			$comma_temp = $comma;
    		}
    	}
    	return $str;
    }

    /**
     * 从一个二维数组中返回指定键的所有值
     *
     * 用法：
     * @code php
     * $rows = array(
     * array('id' => 1, 'value' => '1-1'),
     * array('id' => 2, 'value' => '2-1'),
     * );
     * $values = Helper_Array::cols($rows, 'value');
     *
     * dump($values);
     * // 输出结果为
     * // array(
     * // '1-1',
     * // '2-1',
     * // )
     * @endcode
     *
     * @param array $arr
     *        	数据源
     * @param string $col
     *        	要查询的键
     *
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

    /**
     * 数组转换为字符串
     *
     * @param unknown $arr
     * @param string $glue
     * @param string $key
     * @param string $field
     * @return string
     */
    static function implode(&$arr, $glue = ',', $key = 'id', $field = 'id') {

    	if (empty ( $arr ) || ! count ( $arr )) {
    		return '';
    	}
    	$arr = self::toHashmap ( $arr, $key, $field );

    	return implode ( $glue, $arr );
    }
}
