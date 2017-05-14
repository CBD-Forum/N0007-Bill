<?php
namespace common\components\bank;

use common\components\ActiveRecord;

/**
 * UnfreezeCode Model
 * 解冻码模型
 * -------------------------
 * @version 1.0.0
 * @author CGA
 */
class UnfreezeCode extends ActiveRecord
{

	/**
	 * 获取所有解冻码
	 * @author CGA
	 */
	public static function getCodes($custNo, $tranAmt){
		$rows = self::find()->select(['unfreeze_code'])->where(['custNo' => $custNo, 'tranAmt' => $tranAmt])->asArray()->All();
		$codes = [];
		foreach ($rows as $key => $value) {
			$codes[] = $value['unfreeze_code'];
		}
		return $codes;
	}

	/**
	 * 获取票据解冻码
	 * @author CGA
	 */
	public static function getBillCode($bill){
		return self::findAll(['bill_id' => $bill]);
	}

	/**
	 * 添加解冻码
	 * @author CGA
	 */
	public static function addCode($custNo, $bill, $tranAmt, $code){
		$models = new self;
		$models->custNo = $custNo;
		$models->bill_id = $bill;
		$models->tranAmt = $tranAmt;
		$models->unfreeze_code = $code;
		return $models->save();
	}

	/**
	 * 获取冻结码
	 * @author CGA
	 */
	public static function getCode($custNo, $bill){
		$row = self::find()->select(['unfreeze_code', 'tranAmt'])->where(['custNo' => $custNo, 'bill_id' => $bill])->asArray()->one();
		if ($row) {
			return $row;
		} else {
			return false;
		}
	}

	/**
	 * 使用冻结码
	 * @author CGA
	 */
	public static function useCode($custNo, $bill, $tranAmt, $code){
		self::deleteAll(['custNo' => $custNo, 'bill_id' => $bill, 'tranAmt' => $tranAmt, 'unfreeze_code' => $code]);
		return true;
	}
}
