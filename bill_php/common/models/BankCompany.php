<?php
namespace common\models;

use Yii;
use common\behaviors\ExceptionBehavior;
use common\components\ActiveRecord;

/**
 * BankCompany Model
 * 银行模型
 * -----------------
 * @version 1.0.0
 * @author Verdient。
 */
class BankCompany extends ActiveRecord
{
	/**
	 * @var public $name
	 * 账户名
	 * -----------------
	 * @author Verdient。
	 */
	public $name;

	/**
	 * @var public $bank_card_number
	 * 银行帐号
	 * -----------------------------
	 * @author Verdient。
	 */
	public $bank_card_number;

	/**
	 * @var public $bank_code
	 * 银行行号
	 * ----------------------
	 * @author Verdient。
	 */
	public $bank_code;

	/**
	 * tableName()
	 * 设置表名
	 * -----------
	 * @inheritdoc
	 * -----------
	 * @return String
	 * @author Verdient。
	 */
	public static function tableName(){
		return '{{t_scf_bank_company}}';
	}

	/**
	 * getDb()
	 * 获取数据库配置
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	public static function getDb(){
		return Yii::$app->db2;
	}

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
	 * validateCompanyID(String $attribute, Mixed $params)
	 * 验证公司ID
	 * ---------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateCompanyID($attribute, $params){
		if(!$this->hasErrors()){
			$query = self::find()->where(['companyId' => $this->companyId]);
			if(isset($params['filter']) && is_array($params['filter'])){
				if(ArrayHelper::isAssociative($params['filter'])){
					$query->andFilterWhere($params['filter']);
				}else{
					foreach($params['filter'] as $key => $value){
						$query->andFilterWhere($value);
					}
				}
			}
			if(!$this->instance = $query->one()){
				$this->addError($attribute, 22042);
			}
		}
	}

	/**
	 * validateEnterprise(String $attribute, Mixed $params)
	 * 验证企业
	 * ---------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateEnterprise($attribute, $params){
		if(!$this->hasErrors()){
			if($this->instance = Enterprise::findOne(['enterprise_id' => $this->companyId])){
				if(!in_array($this->instance->status, [Enterprise::STATUS_WAITING_BANK_SUBMIT, Enterprise::STATUS_WAITING_ENTERPRISE_SUBMIT, Enterprise::STATUS_WAITING_APPLY, Enterprise::STATUS_AUDIT_FAILED, Enterprise::STATUS_REMIT_FAILED])){
					$this->addError($attribute, 22032);
				}
			}
		}
	}

	/**
	 * sendBankInfo()
	 * 发送银行信息
	 * --------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function sendBankInfo(){
		$data = $this->attributes;
		foreach($data as $key => $value){
			if(empty($value)){
				unset($data[$key]);
			}
		}
		$result = Yii::$app->haipiaohui->sendBankInfo($data);
		if(isset($result['success']) && $result['success'] == true){
			return true;
		}
		throw new Exception($result['msg'], 22033);
	}
}
