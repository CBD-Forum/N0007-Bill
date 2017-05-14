<?php
namespace common\validators;

use Yii;
use yii\validators\Validator;

/**
 * BusinessLicenseNumberValidator Model
 * 营业执照号验证器 模型
 * ------------------------------------
 * @version 1.0.0
 * @author Verdient。
 */
class BusinessLicenseNumberValidator extends Validator
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
		if(!Yii::$app->validate->check($value, 'businessLicenseNumber')){
			return [$this->message, []];
		}
		return null;
	}
}