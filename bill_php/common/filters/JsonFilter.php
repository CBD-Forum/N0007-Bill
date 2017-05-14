<?php
namespace common\filters;

use yii\helpers\Json;

/**
 * JsonFilter
 * Json过滤器
 * ------------
 * @version 1.0.0
 * @author Verdient。
 */
class JsonFilter
{
	/**
	 * encode()
	 * 编码
	 * ----------
	 * @return function
	 * @author Verdient。
	 */
	public static function encode(){
		return function($value) {
			try{
				return is_object($value) ? Json::encode($value) : (is_array($value) ? json_encode($value) : $value);
			}catch(\Exception $e){
				return null;
			}
		};
	}
}