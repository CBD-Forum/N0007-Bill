<?php
namespace common\models;

use Yii;
use common\components\ActiveRecord;
use common\models\bankSystem\Trade;

/**
 * Daybook Model
 * 资金流水模型
 * -------------
 * @version 1.0.0
 * @author Verdient。
 */
class Daybook extends ActiveRecord
{
	/**
	 * @status const STATUS_BANK_FAILED
	 * 银行操作失败
	 * --------------------------------
	 * @author Verdient。
	 */
	const STATUS_BANK_FAILED = 1;

	/**
	 * @status const STATUS_REGULAR
	 * 正常
	 * ----------------------------
	 * @author Verdient。
	 */
	const STATUS_REGULAR = 2;

	/**
	 * @status const STATUS_BLOCK_FAILED
	 * 区块链操作失败
	 * ---------------------------------
	 * @author Verdient。
	 */
	const STATUS_BLOCK_FAILED = 3;

	/**
	 * @status const TYPE_CASH_IN
	 * 入金
	 * --------------------------
	 * @author Verdient。
	 */
	const TYPE_CASH_IN = 1;

	/**
	 * @status const TYPE_CASH_OUT
	 * 出金
	 * ---------------------------
	 * @author Verdient。
	 */
	const TYPE_CASH_OUT = 2;

	/**
	 * @status const TYPE_PAYMENT
	 * 付款
	 * --------------------------
	 * @author Verdient。
	 */
	const TYPE_PAYMENT = 3;

	/**
	 * @status const TYPE_REFUND
	 * 卖方退款
	 * -------------------------
	 * @author Verdient。
	 */
	const TYPE_REFUND = 4;

	/**
	 * @status const TYPE_DRAWBACK
	 * 买方退款
	 * ---------------------------
	 * @author Verdient。
	 */
	const TYPE_DRAWBACK = 5;

	/**
	 * @status const TYPE_COLLECTION
	 * 收款
	 * -----------------------------
	 * @author Verdient。
	 */
	const TYPE_COLLECTION = 6;

	/**
	 * @map const TYPE_MAP
	 * 类型映射关系
	 * -------------------
	 * @author Verdient。
	 */
	const TYPE_MAP = "return [
		common\models\Daybook::TYPE_CASH_IN => '充值',
		common\models\Daybook::TYPE_CASH_OUT => '提款',
		common\models\Daybook::TYPE_PAYMENT => '付款',
		common\models\Daybook::TYPE_REFUND => '退款',
		common\models\Daybook::TYPE_DRAWBACK => '收到退款',
		common\models\Daybook::TYPE_COLLECTION => '收款'
	];";

	/**
	 * @map const STATUS_MAP
	 * 状态映射关系
	 * ---------------------
	 * @author Verdient。
	 */
	const STATUS_MAP = "return [
		common\models\Daybook::STATUS_BANK_FAILED => '失败',
		common\models\Daybook::STATUS_REGULAR => '正常',
		common\models\Daybook::STATUS_BLOCK_FAILED => '失败'
	];";

	/**
	 * validateAmount(String $attribute, Mixed $params)
	 * 验证金额
	 * ------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateAmount($attribute, $params){
		if(!$this->hasErrors()){
			if(!$result = Yii::$app->blockChain->getBalance(Yii::$app->OAuth2->userInfo->address)){
				$this->addError($attribute, 30053);
			}else if($this->amount > $result['available']){
				$this->addError($attribute, 30054);
			}
		}
	}

	/**
	 * startTimeFilter()
	 * 开始时间过滤器
	 * -----------------
	 * @return function
	 * @author Verdient。
	 */
	public function startTimeFilter(){
		return function($value){
			return $value ? strtotime($value) : 0;
		};
	}

	/**
	 * endTimeFilter()
	 * 结束时间过滤器
	 * ---------------
	 * @return function
	 * @author Verdient。
	 */
	public function endTimeFilter(){
		return function($value){
			return $value ? (strtotime($value . '+1 day') -1) : strtotime('tomorrow +5 year') -1;
		};
	}
}
