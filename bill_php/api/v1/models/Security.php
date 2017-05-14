<?php
namespace api\v1\models;

use Yii;
use yii\base\Model;
use common\behaviors\ExceptionBehavior;
use common\validators\TradePasswordValidator;

/**
 * Security Model
 * 安全 模型
 * --------------
 * @version 1.0.0
 * @author Verdient。
 */
class Security extends Model
{
	/**
	 * @var public $trade_password
	 * 交易密码
	 * ---------------------------
	 * @author Verdient。
	 */
	public $trade_password;

	/**
	 * @var public $new_trade_password
	 * 新交易密码
	 * -------------------------------
	 * @author Verdient。
	 */
	public $new_trade_password;

	/**
	 * @var public $repeat_trade_password
	 * 重复交易密码
	 * ----------------------------------
	 * @author Verdient。
	 */
	public $repeat_trade_password;

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
			[['trade_password', 'new_trade_password', 'repeat_trade_password'], 'filter', 'filter' => 'trim', 'on' => 'changeTradePassword'],
			[['new_trade_password', 'repeat_trade_password'], 'filter', 'filter' => 'trim', 'on' => 'setTradePassword'],
			['trade_password', 'required', 'message' => 32000, 'on' => 'changeTradePassword'],
			['new_trade_password', 'required', 'message' => 32001, 'on' => ['setTradePassword', 'changeTradePassword']],
			['repeat_trade_password', 'required', 'message' => 32002, 'on' => ['setTradePassword', 'changeTradePassword']],
			['new_trade_password', 'compare', 'compareAttribute' => 'repeat_trade_password', 'message' => 32003, 'on' => ['setTradePassword', 'changeTradePassword']],
			['new_trade_password', 'compare', 'compareAttribute' => 'trade_password', 'operator' => '!=', 'message' => 32004, 'on' => ['setTradePassword', 'changeTradePassword']],
			['trade_password', TradePasswordValidator::className(), 'userID' => Yii::$app->OAuth2->userInfo->id, 'message' => 32005, 'on' => 'changeTradePassword']
		];
	}
}