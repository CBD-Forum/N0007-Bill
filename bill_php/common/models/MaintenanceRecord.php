<?php
namespace common\models;

use Yii;
use common\models\Bill;
use common\models\User;
use common\components\ActiveRecord;

/**
 * MaintenanceRecord Model
 * 维护记录模型
 * -----------------------
 * @version 1.0.0
 * @author Verdient。
 */
class MaintenanceRecord extends ActiveRecord
{
	/**
	 * @type const TYPE_DISCOUNT_IN_SYSTEM
	 * 系统内贴现
	 * -----------------------------------
	 * @author Verdient。
	 */
	const TYPE_DISCOUNT_IN_SYSTEM = 1;

	/**
	 * @type const TYPE_DISCOUNT_OUT_SYSTEM
	 * 系统外贴现
	 * ------------------------------------
	 * @author Verdient。
	 */
	const TYPE_DISCOUNT_OUT_SYSTEM = 2;

	/**
	 * @type const TYPE_ENDORSEMENT_IN_SYSTEM
	 * 系统内背书
	 * --------------------------------------
	 * @author Verdient。
	 */
	const TYPE_ENDORSEMENT_IN_SYSTEM = 3;

	/**
	 * @type const TYPE_ENDORSEMENT_OUT_SYSTEM
	 * 系统外背书
	 * ---------------------------------------
	 * @author Verdient。
	 */
	const TYPE_ENDORSEMENT_OUT_SYSTEM = 4;

	/**
	 * @type const TYPE_DELETE_BY_SELF
	 * 自己删除票据
	 * -------------------------------
	 * @author Verdient。
	 */
	const TYPE_DELETE_BY_SELF = 5;

	/**
	 * @type const TYPE_DELETE_BY_FINANCE
	 * 财务公司删除票据
	 * ----------------------------------
	 * @author Verdient。
	 */
	const TYPE_DELETE_BY_FINANCE = 6;

	/**
	 * @map const TYPE_MAP
	 * 类型映射关系
	 * -------------------
	 * @author Verdient。
	 */
	const TYPE_MAP = "return [
		common\models\MaintenanceRecord::TYPE_DISCOUNT_IN_SYSTEM => '已贴现',
		common\models\MaintenanceRecord::TYPE_DISCOUNT_OUT_SYSTEM => '已贴现',
		common\models\MaintenanceRecord::TYPE_ENDORSEMENT_IN_SYSTEM => '已背书',
		common\models\MaintenanceRecord::TYPE_ENDORSEMENT_OUT_SYSTEM => '已背书',
		common\models\MaintenanceRecord::TYPE_DELETE_BY_SELF => '已删除',
		common\models\MaintenanceRecord::TYPE_DELETE_BY_FINANCE => '已删除',
	];";

	public $record;

	/**
	 * validateId(String $attribute, Mixed $params)
	 * 验证交易id
	 * --------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author yuan
	 */
	public function validateId($attribute, $params){
		if(!$this->hasErrors()){
			switch($this->scenario){
				case 'secondaryMaintenance':
					if(!$this->record = self::find()->where(['id' => $this->id])->one()){
						return $this->addError($attribute, 21047);
					}
					if(!in_array($this->record->type, [self::TYPE_DISCOUNT_IN_SYSTEM, self::TYPE_DISCOUNT_OUT_SYSTEM])){
						return $this->addError($attribute,21058);
					}
					$this->instance = json_decode($this->record->bill_info);
					break;

				case 'info':
					if(!$this->record = self::findOne(['id' => $this->id])){
						return $this->addError($attribute, 21047);
					}
					break;
			}

		}
	}

	/**
	 * validateSecondaryEndorsedId(String $attribute, Mixed $params)
	 * 验证交易id ，再维护
	 * -------------------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author yuan
	 */
	public function validateSecondaryEndorsedId($attribute, $param){
		if (!$this->hasErrors()) {
			if (!$this->record = self::find()->where(['id' => $this->id])->one()) {
				return $this->addError($attribute, 21047);
			}

			if(!in_array($this->record->type,array(self::TYPE_ENDORSEMENT_IN_SYSTEM,self::TYPE_ENDORSEMENT_OUT_SYSTEM))) {
				return $this->addError($attribute,21057);
			}
			$this->instance = json_decode($this->record->bill_info);

			/*if ($this->record->type != self::TYPE_ENDORSEMENT_OUT_SYSTEM) {
				$this->addError($attribute, 21048);
			}*/
		}
	}

	/**
	 * validateBillId(String $attribute, Mixed $params)
	 * 验证票据ID
	 * ------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateBillId($attribute, $param){
		if(!$this->hasErrors()){
			if(!$this->instance = Bill::find()->where(['id' => $this->bill_id, 'possessor' => $this->user_id, 'status' => Bill::STATUS_HOLDING])->one()){
				$this->addError($attribute, 21029);
			}
		}
	}

	/**
	 * validateWechselspesen(String $attribute, Mixed $params)
	 * 验证贴现费
	 * -------------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateWechselspesen($attribute, $param){
		if(!$this->hasErrors()){
			if($this->instance->face_amount < $this->wechselspesen){
				$this->addError($attribute, 21031);
			}
		}
	}

	/**
	 * validateTransferAmount(String $attribute, Mixed $params)
	 * 验证转让金额
	 * --------------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateTransferAmount($attribute, $param){
		if(!$this->hasErrors()){
			if($this->instance->face_amount < $this->transfer_amount){
				$this->addError($attribute, 21068);
			}
		}
	}

	/**
	 * validateTransferAt(String $attribute, Mixed $params)
	 * 验证贴现日期
	 * ----------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateTransferAt($attribute, $param){
		if(!$this->hasErrors()){
			if(strtotime($this->instance->acceptance_at) < strtotime($this->transfer_at)){
				$this->addError($attribute, 21035);
			}else if(strtotime($this->transfer_at) < strtotime($this->instance->issue_at)){
				$this->addError($attribute, 21067);
			}
		}
	}

	/**
	 * getTypeMap()
	 * 获取类型映射关系
	 * ----------------
	 * @return Array
	 * @author yuan
	 */
	public function getTypeMap(){
		return eval(self::TYPE_MAP);
	}

	/**
	 * getBill()
	 * 获取票据
	 * ---------
	 * @return String / Array
	 * @author yuan
	 */
	public function getBill(){
		return $this->hasOne(Bill::className(), ['id' => 'bill_id']);
	}

	/**
	 * getUser()
	 * 获取用户
	 * ---------
	 * @return String / Array
	 * @author yuan
	 */
	public function getUser(){
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
}