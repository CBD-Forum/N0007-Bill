<?php
namespace api\v1\models;

use Yii;
use common\models\StatisticsRealTime as BaseStatisticsRealTime;

/**
 * StatisticsRealTime Model
 * 实时统计模型
 * ------------------------
 * @version 1.0.0
 * @author Verdient。
 */
class StatisticsRealTime extends BaseStatisticsRealTime
{

	/**
	 * updateRecord(String $operation, Integer $userId = 0, Integer $type, Float $amount[, Boolean $isTraded = true])
	 * 更新记录
	 * --------------------------------------------------------------------------------------------------------------
	 * @param String $operation 操作
	 * @param Integer $userId 用户ID
	 * @param Integer $type 票据类型
	 * @param Float $amount 金额
	 * @param Boolean $isTraded 是否交易过
	 * -----------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function updateRecord($operation, $userId, $type, $amount, $isTraded = true){
		if(!$model = self::find()->where(['user_id' => $userId])->one()){
			$model = new self;
			$model->user_id = $userId;
		};
		switch($operation){
			case self::OPERATION_DISCOUNT:
				switch($type){
					case Bill::TYPE_BANK_DRAFT:
						$model->bank_discount_amount = round(((float)$model->bank_discount_amount + (float)$amount) , 2);
						$model->bank_discount_quantity = $model->bank_discount_quantity + 1;
						break;

					case Bill::TYPE_COMMERCIAL_DRAFT:
						$model->commercial_discount_amount = round(((float)$model->commercial_discount_amount + (float)$amount) , 2);
						$model->commercial_discount_quantity = $model->commercial_discount_quantity + 1;
						break;

					default:
						throw new Exception(23001);
						break;
				}
				if($isTraded === false){
					$model->prepare_amount = round(((float)$model->prepare_amount - (float)$amount), 2);
				}
				break;

			case self::OPERATION_ENDORSE:
				switch($type){
					case Bill::TYPE_BANK_DRAFT:
						$model->bank_endorse_amount = round(((float)$model->bank_endorse_amount + (float)$amount) , 2);
						$model->bank_endorse_quantity = $model->bank_endorse_quantity + 1;
						break;

					case Bill::TYPE_COMMERCIAL_DRAFT:
						$model->commercial_endorse_amount = round(((float)$model->commercial_endorse_amount + (float)$amount) , 2);
						$model->commercial_endorse_quantity = $model->commercial_endorse_quantity + 1;
						break;

					default:
						throw new Exception(23001);
						break;
				}
				if($isTraded === false){
					$model->prepare_amount = round(((float)$model->prepare_amount - (float)$amount), 2);
				}
				break;

			case self::OPERATION_ENTERING:
				switch($type){
					case Bill::TYPE_BANK_DRAFT:
						$model->bank_entering_amount = round(((float)$model->bank_entering_amount + (float)$amount) , 2);
						$model->bank_entering_quantity = $model->bank_entering_quantity + 1;
						break;

					case Bill::TYPE_COMMERCIAL_DRAFT:
						$model->commercial_entering_amount = round(((float)$model->commercial_entering_amount + (float)$amount) , 2);
						$model->commercial_entering_quantity = $model->commercial_entering_quantity + 1;
						break;

					default:
						throw new Exception(23001);
						break;
				}
				$model->prepare_amount = round(((float)$model->prepare_amount + (float)$amount) , 2);
				break;

			case self::OPERATION_DELETE:
				switch($type){
					case Bill::TYPE_BANK_DRAFT:
						$model->bank_entering_quantity = $model->bank_entering_quantity - 1;
						break;

					case Bill::TYPE_COMMERCIAL_DRAFT:
						$model->commercial_entering_quantity = $model->commercial_entering_quantity - 1;
						break;

					default:
						throw new Exception(23001);
						break;
				}
				if($isTraded === false){
					$model->prepare_amount = round(((float)$model->prepare_amount - (float)$amount) , 2);
				}
				break;

			case self::OPERATION_SELL:
				switch($type){
					case Bill::TYPE_BANK_DRAFT:
						$model->bank_sell_amount = round(((float)$model->bank_sell_amount + (float)$amount) , 2);
						$model->bank_sell_quantity = $model->bank_sell_quantity + 1;
						break;

					case Bill::TYPE_COMMERCIAL_DRAFT:
						$model->commercial_sell_amount = round(((float)$model->commercial_sell_amount + (float)$amount) , 2);
						$model->commercial_sell_quantity = $model->commercial_sell_quantity + 1;
						break;

					default:
						throw new Exception(23001);
						break;
				}
				if($isTraded === false){
					$model->prepare_amount = round(((float)$model->prepare_amount - (float)$amount), 2);
				}
				break;

			case self::OPERATION_BUY:
				switch($type){
					case Bill::TYPE_BANK_DRAFT:
						$model->bank_buy_amount = round(((float)$model->bank_buy_amount + (float)$amount) , 2);
						$model->bank_buy_quantity = $model->bank_buy_quantity + 1;
						break;

					case Bill::TYPE_COMMERCIAL_DRAFT:
						$model->commercial_buy_amount = round(((float)$model->commercial_buy_amount + (float)$amount) , 2);
						$model->commercial_buy_quantity = $model->commercial_buy_quantity + 1;
						break;

					default:
						throw new Exception(23001);
						break;
				}
				break;

			case self::OPERATION_PAYMENT:
				switch($type){
					case Bill::TYPE_BANK_DRAFT:
						$model->bank_payment_amount = round(((float)$model->bank_payment_amount + (float)$amount) , 2);
						break;

					case Bill::TYPE_COMMERCIAL_DRAFT:
						$model->commercial_payment_amount = round(((float)$model->commercial_payment_amount + (float)$amount) , 2);
						break;

					default:
						throw new Exception(23001);
						break;
				}
				break;

			case self::OPERATION_COLLECTION:
				switch($type){
					case Bill::TYPE_BANK_DRAFT:
						$model->bank_collection_amount = round(((float)$model->bank_collection_amount + (float)$amount) , 2);
						break;

					case Bill::TYPE_COMMERCIAL_DRAFT:
						$model->commercia_collection_amount = round(((float)$model->commercia_collection_amount + (float)$amount) , 2);
						break;

					default:
						throw new Exception(23001);
						break;
				}
				break;


			case self::OPERATION_REFUND:
				switch($type){
					case Bill::TYPE_BANK_DRAFT:
						$model->bank_payment_amount = round(((float)$model->bank_payment_amount - (float)$amount) , 2);
						break;

					case Bill::TYPE_COMMERCIAL_DRAFT:
						$model->commercial_payment_amount = round(((float)$model->commercial_payment_amount - (float)$amount) , 2);
						break;

					default:
						throw new Exception(23001);
						break;
				}
				break;

			default:
				throw new Exception(23000);
				break;
		}
		return $model->save(false);
	}
}