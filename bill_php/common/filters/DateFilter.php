<?php
namespace common\filters;

/**
 * DateFilter
 * 日期过滤器
 * ----------
 * @version 1.0.0
 * @author Verdient。
 */
class DateFilter
{
	/**
	 * timestamp([Boolean $end = true])
	 * 将字符串转换为时间戳
	 * ----------------------------------
	 * @param Boolean $end 是否是当天结束时刻的时间戳
	 * ----------------------------------------------
	 * @return function
	 * @author Verdient。
	 */
	public static function timestamp($end = false){
		return function($value) use ($end){
			try{
				$timestamp = strtotime($value);
				return $timestamp !== false && $timestamp === strtotime(date('Y-m-d', $timestamp)) ? ($end ? $timestamp + 86399 : $timestamp) : false;
			}catch(\Exception $e){
				return null;
			}
		};
	}
}