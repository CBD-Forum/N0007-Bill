<?php
namespace common\models;

use Yii;
use common\components\ActiveRecord;

/**
 * StatisticsRealTime Model
 * 实时统计模型
 * ------------------------
 * @version 1.0.0
 * @author Verdient。
 */
class StatisticsRealTime extends ActiveRecord
{
	/**
	 * @operation const OPERATION_DISCOUNT
	 * 贴现
	 * -----------------------------------
	 * @author Verdient。
	 */
	const OPERATION_DISCOUNT = 'DISCOUNT';

	/**
	 * @operation const OPERATION_ENDORSE
	 * 背书
	 * ----------------------------------
	 * @author Verdient。
	 */
	const OPERATION_ENDORSE = 'ENDORSE';

	/**
	 * @operation const OPERATION_ENTERING
	 * 录入
	 * -----------------------------------
	 * @author Verdient。
	 */
	const OPERATION_ENTERING = 'ENTERING';

	/**
	 * @operation const OPERATION_DELETE
	 * 删除
	 * ----------------------------------
	 * @author Verdient。
	 */
	const OPERATION_DELETE = 'ERASE';

	/**
	 * @operation const OPERATION_SELL
	 * 卖出票据
	 * -------------------------------
	 * @author Verdient。
	 */
	const OPERATION_SELL = 'SELL';

	/**
	 * @operation const OPERATION_BUY
	 * 买入票据
	 * ------------------------------
	 * @author Verdient。
	 */
	const OPERATION_BUY = 'BUY';

	/**
	 * @operation const OPERATION_PAYMENT
	 * 付款
	 * ----------------------------------
	 * @author Verdient。
	 */
	const OPERATION_PAYMENT = 'PAYMENT';

	/**
	 * @operation const OPERATION_COLLECTION
	 * 收款
	 * -------------------------------------
	 * @author Verdient。
	 */
	const OPERATION_COLLECTION = 'COLLECTION';

	/**
	 * @operation const OPERATION_REFUND
	 * 退款
	 * ---------------------------------
	 * @author Verdient。
	 */
	const OPERATION_REFUND = 'REFUND';

	/**
	 * waitingCheckAmount(Integer / Array $userId)
	 * 待审核金额
	 * -------------------------------------------
	 * @param Integer / Array $userId 用户ID
	 * -------------------------------------
	 * @return String
	 * @author Verdient。
	 */
	public static function waitingCheckAmount($userId){
		$result = (float)Bill::find()->where(['status' => Bill::STATUS_CHECK_PENDING, 'possessor' => $userId])->sum('face_amount');
		return number_format($result, 2);
	}

	/**
	 * holdingAmount(Integer / Array $userId)
	 * 持有中金额
	 * --------------------------------------
	 * @param Integer / Array $userId 用户ID
	 * -------------------------------------
	 * @return String
	 * @author Verdient。
	 */
	public static function holdingAmount($userId){
		$result = (float)Bill::find()->where(['status' => Bill::STATUS_HOLDING, 'possessor' => $userId])->sum('face_amount');
		return number_format($result, 2);
	}

	/**
	 * bankEnteringQuantity(Integer / Array $userId)
	 * 银票录入张数
	 * ---------------------------------------------
	 * @param Integer / Array $userId 用户ID
	 * -------------------------------------
	 * @return Integer
	 * @author Verdient。
	 */
	public static function bankEnteringQuantity($userId){
		return StatisticsRealTime::find()->where(['user_id' => $userId])->sum('bank_entering_quantity') ?: 0;
	}

	/**
	 * commercialEnteringQuantity(Integer / Array $userId)
	 * 商票录入张数
	 * ---------------------------------------------------
	 * @param Integer / Array $userId 用户ID
	 * -------------------------------------
	 * @return Integer
	 * @author Verdient。
	 */
	public static function commercialEnteringQuantity($userId){
		return StatisticsRealTime::find()->where(['user_id' => $userId])->sum('commercial_entering_quantity') ?: 0;
	}

	/**
	 * transactionAmount(Integer / Array $userId)
	 * 交易金额
	 * ------------------------------------------
	 * @param Integer / Array $userId 用户ID
	 * -------------------------------------
	 * @return String
	 * @author Verdient。
	 */
	public static function transactionAmount($userId){
		$result = ((float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('bank_discount_amount') + (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('bank_endorse_amount') + (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('bank_buy_amount') + (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('bank_sell_amount') + (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('commercial_discount_amount') + (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('commercial_endorse_amount') + (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('commercial_buy_amount') + (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('commercial_sell_amount') + (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('prepare_amount'));
		return number_format($result, 2);
	}

	/**
	 * invoiceAmount(Integer / Array $userId)
	 * 收票金额
	 * --------------------------------------
	 * @param Integer / Array $userId 用户ID
	 * -------------------------------------
	 * @return String
	 * @author Verdient。
	 */
	public static function invoiceAmount($userId){
		$result = (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('bank_buy_amount') + (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('commercial_buy_amount');
		return number_format($result, 2);
	}

	/**
	 * expenseAmount(Integer / Array $userId)
	 * 支出金额
	 * --------------------------------------
	 * @param Integer / Array $userId 用户ID
	 * -------------------------------------
	 * @return String
	 * @author Verdient。
	 */
	public static function expenseAmount($userId){
		$result = (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('bank_payment_amount') + (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('commercial_payment_amount');
		return number_format($result, 2);
	}

	/**
	 * assignmentAmount(Integer / Array $userId)
	 * 转让金额
	 * -----------------------------------------
	 * @param Integer / Array $userId 用户ID
	 * -------------------------------------
	 * @return String
	 * @author Verdient。
	 */
	public static function assignmentAmount($userId){
		$result = (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('bank_sell_amount') + (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('commercial_sell_amount');
		return number_format($result, 2);
	}

	/**
	 * earningAmount(Integer / Array $userId)
	 * 收入金额
	 * --------------------------------------
	 * @param Integer / Array $userId 用户ID
	 * -------------------------------------
	 * @return String
	 * @author Verdient。
	 */
	public static function earningAmount($userId){
		$result = (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('bank_buy_amount') + (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('commercial_buy_amount') - (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('bank_payment_amount') - (float)StatisticsRealTime::find()->where(['user_id' => $userId])->sum('commercial_payment_amount');
		return number_format($result, 2);
	}

	/**
	 * frozenAmount(Integer / Array $userId)
	 * 冻结金额
	 * -------------------------------------
	 * @param Integer / Array $userId 用户ID
	 * -------------------------------------
	 * @return String
	 * @author Verdient。
	 */
	public static function frozenAmount($userId){
		$result = (float)Bill::find()->where(['possessor' => $userId])->andFilterWhere(['between', 'status', Bill::STATUS_LISTING, Bill::STATUS_POSSESSOR_REFUSE_REVOKE_AFTER_ASSIGNMENT])->sum('face_amount');
		return number_format($result, 2);
	}
}
