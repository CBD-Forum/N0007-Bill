<?php
namespace common\helpers;

use Yii;
use yii\helpers\StringHelper as baseStringHelper;

/**
 * StringHelper Model
 * 字符串助手模型
 * ------------------
 * @version 1.0.0
 * @author Verdient。
 */
class StringHelper extends baseStringHelper
{
	/**
	 * generateRandomString([Integer $length])
	 * 获取随机字符串
	 * --------------------------------------------------
	 * @param Integer $length 字符串长度
	 * ------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function generateRandomString($length = 32){
		$str = '';
		for($i = 0; $i < $length; $i++){
			$random = mt_rand(0, 1);
			$str .= $random === 0 ? chr(mt_rand(65, 90)) : chr(mt_rand(97, 122));
		}
		return $str;
	}

	/**
	 * numberToChinese(String $num)
	 * 数字转为汉字
	 * ----------------------------
	 * @param String $num 数字
	 * -----------------------
	 * @return String
	 * @author Verdient。
	 */
	public static function numberToChinese($num){
		$num = (string)$num;
		$char = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九'];
		$dw = ['', '十', '百', '千', '万', '亿', '兆'];
		$retval = '';
		$proZero = false;
		for($i = 0; $i < strlen($num); $i++){
			if($i > 0){
				$temp = (int)(($num % pow(10, $i + 1)) / pow(10, $i));
			} else {
				$temp = (int)($num % pow(10, 1));
			}
			if($proZero == true && $temp == 0){
				continue;
			}
			if($temp == 0){
				$proZero = true;
			}else{
				$proZero = false;
			}
			if($proZero){
				if($retval == ''){
					continue;
				}
				$retval = $char[$temp] . $retval;
			}else{
				$retval = $char[$temp] . $dw[$i] . $retval;
			}
		}
		if($retval == "一十"){
			$retval = "十";
		}
		return $retval;
	}
}