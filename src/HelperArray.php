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

    /**
     * 根据字段分组
     * @param  string $arr       处理的数组
     * @param  string $key_field 分组的字段
     * @return array            返回的数组
     */
    static function groupBy($arr, $key_field) {

        $ret = array ();
        foreach ( $arr as $row ) {
            $key = $row [$key_field];
            $ret [$key] [] = $row;
        }
        return $ret;
    }

    /**
     * 将树转换为数组
     * @param  tree $tree          一棵树
     * @param  string $key_childrens 孩子的名称
     * @return array                返回的结果数组
     */
    static function treeToArray($tree, $key_childrens = 'childrens') {

        $ret = array ();
        if (isset ( $tree [$key_childrens] ) && is_array ( $tree [$key_childrens] )) {
            $childrens = $tree [$key_childrens];
            unset ( $tree [$key_childrens] );
            $ret [] = $tree;
            foreach ( $childrens as $node ) {
                $ret = array_merge ( $ret, self::treeToArray ( $node, $key_childrens ) );
            }
        } else {
            unset ( $tree [$key_childrens] );
            $ret [] = $tree;
        }
        return $ret;
    }

    /**
     * 根据指定的键对数组进行排序
     * @param  array $array   要排序的数组
     * @param  string $keyname 要排序的键值
     * @param  sort $dir     升序还是降序
     * @return array          返回的数组
     */
    static function sortByCol($array, $keyname, $dir = SORT_ASC) {

        return self::sortByMultiCols ( $array, array (
                $keyname => $dir
        ) );
    }

    /**
     * 获得无限分类的所有孩子
     * @param  array  $array     原数据
     * @param  integer $parent_id 分类的父级键
     * @return array             结果
     */
    static function getChildren($array, $parent_id = 0) {

        $ret = array ();

        foreach ( $array as $k => $v ) {
            if ($v ['parent_id'] == $parent_id) {
                $ret [$k] = $v;
            }
        }
        return $ret;
    }

    /**
     * 获得无限分类的所有同辈兄弟姐妹
     *
     * @return array
     */
    static function getSiblings($array, $self) {

        $ret = array ();
        $current = $array [$self];
        if (empty ( $current )) {
            return $ret;
        }

        $parent_id = $current ['parent_id'];

        foreach ( $array as $key => $value ) {
            if ($value ['parent_id'] == $parent_id && $value ['id'] != $self) {
                $ret [$key] = $value;
            }
        }
        return $ret;
    }

    /**
     * 获取后代的id 返回id的数组
     * @param unknown $tree
     * @param string $key_node_id
     * @param string $key_childrens
     * @param string $self
     */
    static function getDescendants($tree, $key_node_id = 'id', $key_childrens = 'children', $self = false) {

        //加入传入对象也可以
        if (empty ( $tree ) || ! (is_array ( $tree )||is_object ( $tree ))) {
            return;
        }

        $array = array ();
        foreach ( $tree [$key_childrens] as $val ) {
            $array [] = $val [$key_node_id];
            if ($val [$key_childrens]) {
                $array = array_merge ( $array, self::getDescendants ( $val, $key_node_id, $key_childrens ) );
            }
        }
        if ($self) {
            array_unshift($array, $tree[$key_node_id]);
        }
        return $array;
    }

    /**
     * 将数组转换成SQL语句
     *
     * @return string
     */
    static function toSQL($array, $key = 0) {

        if (! count ( $array )) {
            return false;
        }
        $sql = $comma = '';

        foreach ( $array as $k => $v ) {
            $sql .= $comma . "'" . ($key ? $k : $v) . "'";
            $comma = ',';
        }

        return $sql;
    }

    /**
     * 从二维数组中查找结果
     *
     * @param $ref 按某个字段来查找
     * @param $value 查找的值，即$ref字段的值，如果不存在$ref，即二维数组的键就是记录的ID
     * @param $return 要返回的字段
     */
    static function find(&$array, $ref = null, $value = 'id', $return = null, $single = false) {

        $found = null;
        if ($ref) {
            if (! is_array ( $value )) {
                $value = self::toArray ( $value );
            }
            foreach ( $array as $key => $val ) {
                if (in_array ( $val [$ref], $value )) {
                    if ($single) {
                        $found = $return ? $val [$return] : $val;
                        break;
                    }
                    $found [$key] = $return ? $val [$return] : $val;
                }
            }
        } else {
            if (is_array ( $value )) {
                foreach ( $value as $val ) {
                    $found [] = $return ? $array [$val] [$return] : $array [$val];
                }
            } else {
                $found = $return ? $array [$val] [$return] : $array [$val];
            }
        }
        return $found;
    }

    /**
     * 替换数组中的某个值
     */
    static function replace(&$array, $arr) {

        $return = $array;
        foreach ( $arr as $key => $val ) {
            if (isset ( $return [$key] )) {
                $return [$key] = $val;
            }
        }
        return $return;
    }

    /**
     * 将数组中的每个元素的头或尾填充字符串
     */
    static function fill(& $array, $string, $pos = 'left') {

        foreach ( $array as $k => $v ) {
            $array [$k] = $pos == 'left' ? "*." . $v : $v . "*.";
        }
    }
}
