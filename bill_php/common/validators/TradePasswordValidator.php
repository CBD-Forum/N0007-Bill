<?php
namespace common\validators;

use Yii;
use yii\base\InvalidConfigException;
use yii\validators\Validator;
use common\models\User;

/**
 * TradePasswordValidator Model
 * 交易密码验证器 模型
 * ----------------------------
 * @version 1.0.0
 * @author Verdient。
 */
class TradePasswordValidator extends Validator
{
	/**
	 * @var public $userID
	 * 用户ID
	 * -------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $userID = null;

	/**
	 * @var public $userIDAttribute
	 * 用户ID名称
	 * ----------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $userIDAttribute = 'user_id';

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
		$userIDAttribute = $this->userIDAttribute;
		if(!$userID = $this->userID){
			try{
				$userID = $model->$userIDAttribute;
			}catch(\Exception $e){
				throw new InvalidConfigException('Unknown userIDAttribute or userID');
			}
		}
		if(!$enterpriseID = User::find()->select('enterprise_id')->where(['id' => $userID])->asArray()->one()){
			throw new InvalidConfigException('Can not find enterprise ID by user ID : ' . $userID);
		}
		if(!$model->hasErrors() && !Yii::$app->bank->validateTradePassword($enterpriseID['enterprise_id'], $model->$attribute)){
			return $this->addError($model, $attribute, $this->message);
		}
	}
}