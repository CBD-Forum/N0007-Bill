<?php
namespace api\v1\models;

use Yii;
use yii\base\Model;
use common\behaviors\ExceptionBehavior;
use common\validators\BankCardValidator;
use common\validators\CaptchaValidator;
use common\validators\IDCardValidator;
use common\validators\MobileValidator;

/**
 * Auth Model
 * 认证模型
 * ----------
 * @version 1.0.0
 * @author Verdient。
 */
class Auth extends Model
{
	/**
	 * @var public $name
	 * 姓名
	 * -----------------
	 * @method POST
	 * @author Verdient。
	 */
	public $name;

	/**
	 * @var public $id_number
	 * 身份证号
	 * -----------------------
	 * @method POST
	 * @author Verdient。
	 */
	public $id_number;

	/**
	 * @var public $bank_card_number
	 * 银行卡号
	 * -----------------------------
	 * @method POST
	 * @author Verdient。
	 */
	public $bank_card_number;

	/**
	 * @var public $mobile
	 * 手机号码
	 * -------------------
	 * @method POST
	 * @author Verdient。
	 */
	public $mobile;

	/**
	 * @var public $captcha
	 * 验证码
	 * --------------------
	 * @method POST
	 * @author Verdient。
	 */
	public $captcha;

	/**
	 * behaviors()
	 * 行为设置
	 * -----------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public function behaviors(){
		return [
			'ExceptionBehavior' => ExceptionBehavior::className()
		];
	}

	/**
	 * rules()
	 * 数据验证规则
	 * ------------
	 * @inheritdoc
	 * ------------
	 * @return Array
	 * @author Verdient。
	 */
	public function rules(){
		return [
			[['name', 'id_number', 'bank_card_number', 'mobile', 'captcha'], 'filter', 'filter' => 'trim', 'on' => 'certification'],
			['name', 'required', 'message' => 14000, 'on' => 'certification'],
			['id_number', 'required', 'message' => 14001, 'on' => 'certification'],
			['bank_card_number', 'required', 'message' => 14002, 'on' => 'certification'],
			['mobile', 'required', 'message' => 14003, 'on' => 'certification'],
			['captcha', 'required', 'message' => 14004, 'on' => 'certification'],
			['name', 'string', 'max' => 20, 'message' => 14005, 'tooLong' => 14005, 'on' => 'certification'],
			['id_number', IDCardValidator::className(), 'message' => 14006, 'on' => 'certification'],
			['bank_card_number', BankCardValidator::className(), 'message' => 14007, 'on' => 'certification'],
			['mobile', MobileValidator::className(), 'message' => 14008, 'on' => 'certification'],
			['captcha', 'string', 'min' => 6, 'max' => 6, 'message' => 14009, 'tooLong' => 14009, 'tooShort' => 14009, 'on' => 'certification'],
			['captcha', 'integer',  'message' => 14009, 'on' => 'certification'],
			['captcha', CaptchaValidator::className(), 'message' => 14011, 'on' => 'certification']
		];
	}
}