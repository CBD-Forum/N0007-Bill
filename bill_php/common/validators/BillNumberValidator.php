<?php
namespace common\validators;

use Yii;
use yii\validators\Validator;

/**
 * BillNumberValidator Model
 * 票据编号验证器 模型
 * -------------------------
 * @version 1.0.0
 * @author Verdient。
 */
class BillNumberValidator extends Validator
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
		if(!Yii::$app->validate->check($value, 'billNumber')){
			return [$this->message, []];
		}
		return null;
	}
}