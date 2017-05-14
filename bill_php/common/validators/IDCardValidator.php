<?php
namespace common\validators;

use Yii;
use yii\validators\Validator;

/**
 * IDCardValidator Model
 * 身份证号验证器 模型
 * ---------------------
 * @version 1.0.0
 * @author Verdient。
 */
class IDCardValidator extends Validator
{
	/**
	 * validateValue(Mixed $value)
	 * 验证属性
	 * ---------------------------
	 * @param Mixed $value 验证值
	 * --------------------------
	 * @author Verdient。
	 */
	public function validateValue($value){
		if(!Yii::$app->validate->check($value, 'IDCard')){
			return [$this->message, []];
		}
		return null;
	}
}