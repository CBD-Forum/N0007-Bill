<?php
namespace common\validators;

use Yii;
use yii\validators\Validator;

/**
 * EndTimeValidator Model
 * 结束时间验证器 模型
 * -----------------------
 * @version 1.0.0
 * @author Verdient。
 */
class EndTimeValidator extends Validator
{
	/**
	 * @var public $startAttribute
	 * 开始时间字段名称
	 * ---------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $startAttribute = 'startTime';

	/**
	 * validateAttribute(Object $model, String $attribute)
	 * 验证属性
	 * ---------------------------------------------------
	 * @param Object $model 模型实例对象
	 * @param String $attribute 字段名称
	 * ---------------------------------
	 * @author Verdient。
	 */
	public function validateAttribute($model, $attribute){
		$startAttribute = $this->startAttribute;
		if(strtotime($model->$startAttribute) > strtotime($model->$attribute)){
			return $this->addError($model, $attribute, $this->message);
		}
	}
}