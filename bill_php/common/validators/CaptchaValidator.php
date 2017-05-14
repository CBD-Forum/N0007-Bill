<?php
namespace common\validators;

use Yii;
use yii\base\InvalidConfigException;
use yii\validators\Validator;

/**
 * CaptchaValidator Model
 * 验证码验证器 模型
 * ----------------------
 * @version 1.0.0
 * @author Verdient。
 */
class CaptchaValidator extends Validator
{
	/**
	 * @var public $mobile
	 * 手机号码
	 * -------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $mobile = null;

	/**
	 * @var public $mobileAttribute
	 * 手机字段名称
	 * ----------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $mobileAttribute = 'mobile';

	/**
	 * @var public $type
	 * 类型
	 * -----------------
	 * @method Config
	 * @author Verdient。
	 */
	public $type = 'captcha';

	/**
	 * @var public $country
	 * 国家
	 * --------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $country = 'CN';

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
		$mobileAttribute = $this->mobileAttribute;
		if(!$mobile = $this->mobile){
			try{
				$mobile = $model->$mobileAttribute;
			}catch(\Exception $e){
				throw new InvalidConfigException('Unknown mobileAttribute or mobile');
			}
		}
		if(!$model->hasErrors() && !Yii::$app->message->validateCaptcha($mobile, $this->type, $model->$attribute, $this->country)){
			return $this->addError($model, $attribute, $this->message);
		}
	}
}