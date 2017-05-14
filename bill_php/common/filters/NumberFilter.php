<?php
namespace common\filters;

/**
 * NumberFilter
 * 数字过滤器
 * ------------
 * @version 1.0.0
 * @author Verdient。
 */
class NumberFilter
{
	/**
	 * unformat()
	 * 去除格式化
	 * ----------
	 * @return function
	 * @author Verdient。
	 */
	public static function unformat(){
		return function($value){
			try{
				return str_replace(',', '', $value);
			}catch(\Exception $e){
				return null;
			}
		};
	}
}