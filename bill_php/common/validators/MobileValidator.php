<?php
namespace common\validators;

use Yii;
use yii\validators\Validator;

/**
 * MobileValidator Model
 * 手机号验证器 模型
 * ---------------------
 * @version 1.0.0
 * @author Verdient。
 */
class MobileValidator extends Validator
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
		if(!Yii::$app->validate->check($value, 'mobile')){
			return [$this->message, []];
		}
		return null;
	}
}