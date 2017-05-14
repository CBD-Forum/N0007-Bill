<?php
namespace api\v1\models;

use Yii;
use yii\base\Model;
use common\behaviors\ExceptionBehavior;
use common\validators\MobileValidator;
use api\v1\components\Exception;

/**
 * Message Model
 * 消息模型
 * -------------
 * @version 1.0.0
 * @author Verdient。
 */
class Message extends Model
{
	/**
	 * @var public $mobile
	 * 手机号码
	 * -------------------
	 * @method POST
	 * @author Verdient。
	 */
	public $mobile;

	/**
	 * @var public $enterprise_id
	 * 企业ID
	 * --------------------------
	 * @method POST
	 * @author Verdient。
	 */
	public $enterprise_id;

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
			['mobile', 'filter', 'filter' => self::mobileFilter(), 'on' => 'captcha'],
			['mobile', 'required', 'message' => 15002, 'on' => 'captcha'],
			['mobile', MobileValidator::className(), 'message' => 15003, 'on' => 'captcha']
		];
	}

	/**
	 * mobileFilter()
	 * 手机号码过滤器
	 * --------------
	 * @return function
	 * @author Verdient。
	 */
	public function mobileFilter(){
		return function($value){
			if(!$value){
				$companyBaseInfo = Yii::$app->OAuth2->userInfo->companyBaseInfo;
				return isset($companyBaseInfo) ? $companyBaseInfo->contact_number : null;
			}
			return $value;
		};
	}
}