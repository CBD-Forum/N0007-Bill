<?php
namespace common\models;

use Yii;
use yii\helpers\Json;
use common\components\ActiveRecord;

/**
 * TradeRecord Model
 * 交易记录模型
 * -----------------
 * @version 1.0.0
 * @author Verdient。
 */
class TradeRecord extends ActiveRecord
{
	/**
	 * @type const TYPE_TYPE_IN
	 * 录入
	 * ------------------------
	 * @author Verdient。
	 */
	const TYPE_TYPE_IN = 1;

	/**
	 * @type const TYPE_LISTING
	 * 挂牌
	 * ------------------------
	 * @author Verdient。
	 */
	const TYPE_LISTING = 2;

	/**
	 * @type const TYPE_DELIST
	 * 摘牌
	 * -----------------------
	 * @author Verdient。
	 */
	const TYPE_DELIST = 3;

	/**
	 * @type const TYPE_PAYMENT
	 * 付款
	 * ------------------------
	 * @author Verdient。
	 */
	const TYPE_PAYMENT = 4;

	/**
	 * @type const TYPE_ASSIGNMENT
	 * ECDS转让
	 * ---------------------------
	 * @author Verdient。
	 */
	const TYPE_ASSIGNMENT = 5;

	/**
	 * @type const TYPE_REVOKE
	 * 撤销挂单
	 * -----------------------
	 * @author Verdient。
	 */
	const TYPE_REVOKE = 6;

	/**
	 * @type const TYPE_APPLY_CANCEL_ORDER
	 * 申请撤销订单
	 * -----------------------------------
	 * @author Verdient。
	 */
	const TYPE_APPLY_CANCEL_ORDER = 7;

	/**
	 * @type const TYPE_REVOKE_CANCEL_ORDER
	 * 撤回撤销订单
	 * ------------------------------------
	 * @author Verdient。
	 */
	const TYPE_REVOKE_CANCEL_ORDER = 8;

	/**
	 * @type const TYPE_AGREE_CANCEL_ORDER
	 * 同意撤销订单
	 * -----------------------------------
	 * @author Verdient。
	 */
	const TYPE_AGREE_CANCEL_ORDER = 9;

	/**
	 * @type const TYPE_REFUSE_CANCEL_ORDER
	 * 拒绝撤销订单
	 * ------------------------------------
	 * @author Verdient。
	 */
	const TYPE_REFUSE_CANCEL_ORDER = 10;

	/**
	 * @type const TYPE_CANCEL_ORDER
	 * 撤销订单
	 * -----------------------------
	 * @author Verdient。
	 */
	const TYPE_CANCEL_ORDER = 11;

	/**
	 * @type const TYPE_HOLD
	 * 收票
	 * ---------------------
	 * @author Verdient。
	 */
	const TYPE_HOLD = 12;

	/**
	 * @type const TYPE_COLLECTION
	 * 收款
	 * ---------------------------
	 * @author Verdient。
	 */
	const TYPE_COLLECTION = 13;

	/**
	 * @type const TYPE_REFUND
	 * 退款
	 * -----------------------
	 * @author Verdient。
	 */
	const TYPE_REFUND = 14;

	/**
	 * @type const TYPE_REIMBURSE
	 * 收到退款
	 * --------------------------
	 * @author Verdient。
	 */
	const TYPE_REIMBURSE = 15;

	/**
	 * @type const TYPE_MAP
	 * 类型对应关系
	 * --------------------
	 * @author Verdient。
	 */
	const TYPE_MAP = "return [
		common\models\TradeRecord::TYPE_TYPE_IN => '录入',
		common\models\TradeRecord::TYPE_LISTING => '挂牌',
		common\models\TradeRecord::TYPE_DELIST => '摘牌',
		common\models\TradeRecord::TYPE_PAYMENT => '付款',
		common\models\TradeRecord::TYPE_ASSIGNMENT => 'ECDS转让',
		common\models\TradeRecord::TYPE_REVOKE => '撤销挂单',
		common\models\TradeRecord::TYPE_APPLY_CANCEL_ORDER => '申请撤单',
		common\models\TradeRecord::TYPE_REVOKE_CANCEL_ORDER => '撤回撤单',
		common\models\TradeRecord::TYPE_AGREE_CANCEL_ORDER => '同意撤单',
		common\models\TradeRecord::TYPE_REFUSE_CANCEL_ORDER => '拒绝撤单',
		common\models\TradeRecord::TYPE_CANCEL_ORDER => '撤销订单',
		common\models\TradeRecord::TYPE_HOLD => '收票',
		common\models\TradeRecord::TYPE_COLLECTION => '收款',
		common\models\TradeRecord::TYPE_REFUND => '退款',
		common\models\TradeRecord::TYPE_REIMBURSE => '收到退款'
	];";

	/**
	 * @type const TYPE_MAP2
	 * 类型对应关系2
	 * ---------------------
	 * @author Verdient。
	 */
	const TYPE_MAP2 = "return [
		common\models\TradeRecord::TYPE_COLLECTION => '已回款',
		common\models\TradeRecord::TYPE_REIMBURSE => '已撤销'

	];";

	/**
	 * validateType(String $attribute, Mixed $params)
	 * 验证类型
	 * ----------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateType($attribute, $params){
		if(!$this->hasErrors()){
			$billInfo = json_decode($this->bill_info, true);
			$this->bill_type = $billInfo['type'];
			$this->bill_id = $billInfo['id'];
			switch($this->type){
				case self::TYPE_TYPE_IN:
					$this->user_id = $billInfo['creator'];
					$this->amount = $billInfo['face_amount'];
					break;

				case self::TYPE_LISTING: case self::TYPE_REVOKE:
					$this->user_id = $billInfo['possessor'];
					$this->annualized_rate = $billInfo['annualized_rate'];
					$this->amount = $billInfo['financing_amount'];
					break;

				case self::TYPE_DELIST: case self::TYPE_PAYMENT: case self::TYPE_APPLY_CANCEL_ORDER: case self::TYPE_REVOKE_CANCEL_ORDER: case self::TYPE_CANCEL_ORDER: case self::TYPE_HOLD: case self::TYPE_REIMBURSE:
					$this->user_id = $billInfo['investor'];
					$this->annualized_rate = $billInfo['annualized_rate'];
					$this->opposite_id = $billInfo['possessor'];
					$this->amount = $billInfo['financing_amount'];
					break;

				case self::TYPE_ASSIGNMENT: case self::TYPE_AGREE_CANCEL_ORDER: case self::TYPE_REFUSE_CANCEL_ORDER: case self::TYPE_COLLECTION: case self::TYPE_REFUND:
					$this->user_id = $billInfo['possessor'];
					$this->annualized_rate = $billInfo['annualized_rate'];
					$this->opposite_id = $billInfo['investor'];
					$this->amount = $billInfo['financing_amount'];
					break;

				default:
					$this->addError($attribute, 24019);
					break;
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

	/**
	 * getTypeMap()
	 * 获取类型对应关系
	 * ----------------
	 * @return function
	 * @author Verdient。
	 */
	public function getTypeMap(){
		return eval(self::TYPE_MAP);
	}

	/**
	 * getBill()
	 * 关联票据表
	 * ---------
	 * @return function
	 * @author Verdient。
	 */
	public function getBill(){
		return $this->hasOne(Bill::className(), ['id' => 'bill_id'])->asArray();
	}

	/**
	 * getOpposite()
	 * 关联对方信息
	 * -------------
	 * @return function
	 * @author Verdient。
	 */
	public function getOpposite(){
		return $this->hasOne(User::className(), ['id' => 'opposite_id'])->asArray();
	}
}