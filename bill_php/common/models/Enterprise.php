<?php
namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\components\ActiveRecord;

/**
 * Enterprise Model
 * 企业信息模型
 * ----------------
 * @version 1.0.0
 * @author Verdient。
 */
class Enterprise extends ActiveRecord
{
	/**
	 * @status const STATUS_WAITING_BANK_SUBMIT
	 * 等待提交银行信息
	 * ----------------------------------------
	 * @author Verdient。
	 */
	const STATUS_WAITING_BANK_SUBMIT = '0';

	/**
	 * @status const STATUS_WAITING_ENTERPRISE_SUBMIT
	 * 等待提交企业信息
	 * ----------------------------------------------
	 * @author Verdient。
	 */
	const STATUS_WAITING_ENTERPRISE_SUBMIT = 1;

	/**
	 * @status const STATUS_WAITING_APPLY
	 * 待申请
	 * ----------------------------------
	 * @author Verdient。
	 */
	const STATUS_WAITING_APPLY = 2;

	/**
	 * @status const STATUS_WAITING_AUDIT
	 * 待审核
	 * ----------------------------------
	 * @author Verdient。
	 */
	const STATUS_WAITING_AUDIT = 3;

	/**
	 * @status const STATUS_CONFIRM_FAILED
	 * 审核失败
	 * -----------------------------------
	 * @author Verdient。
	 */
	const STATUS_AUDIT_FAILED = 4;

	/**
	 * @status const STATUS_WAITING_REMIT
	 * 待打款
	 * ----------------------------------
	 * @author Verdient。
	 */
	const STATUS_WAITING_REMIT = 5;

	/**
	 * @status const STATUS_REMIT_FAILED
	 * 打款失败
	 * ---------------------------------
	 * @author Verdient。
	 */
	const STATUS_REMIT_FAILED = 6;

	/**
	 * @status const STATUS_WAITING_AUTHENTICATION
	 * 待认证
	 * -------------------------------------------
	 * @author Verdient。
	 */
	const STATUS_WAITING_AUTHENTICATION = 7;

	/**
	 * @status const STATUS_REGULAR
	 * 正常
	 * ----------------------------
	 * @author Verdient。
	 */
	const STATUS_REGULAR = 8;

	/**
	 * @status const STATUS_AUTHENTICATION_FAILED
	 * 认证失败
	 * ------------------------------------------
	 * @author Verdient。
	 */
	const STATUS_AUTHENTICATION_FAILED = 9;

	/**
	 * @var public $type
	 * 类型
	 * -----------------
	 * @method GET
	 * @author Verdient。
	 */
	public $type;

	/**
	 * @var public $page
	 * 页码
	 * -----------------
	 * @method GET
	 * @author Verdient。
	 */
	public $page;

	/**
	 * @var public $pageSize
	 * 分页大小
	 * ---------------------
	 * @method GET
	 * @author Verdient。
	 */
	public $pageSize;

	/**
	 * validateEnterpriseID(String $attribute, Mixed $params)
	 * 验证企业ID
	 * ------------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateEnterpriseId($attribute, $params){
		if(!$this->hasErrors()){
			$query = self::find()->where(['enterprise_id' => $this->enterprise_id]);
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
				$this->addError($attribute, 26001);
			}
		}
	}

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
			if($this->instance->amount != $this->amount){
				$this->addError($attribute, 26006);
			}
		}
	}

	/**
	 * typeFilter()
	 * 类型过滤器
	 * ------------
	 * @author Verdient。
	 */
	public function typeFilter(){
		return function($value){
			switch ($value) {
				case '0':
					return [self::STATUS_WAITING_REMIT, self::STATUS_WAITING_AUTHENTICATION];
					break;
				case '1':
					return [self::STATUS_WAITING_REMIT];
					break;
				case '2':
					return [self::STATUS_WAITING_AUTHENTICATION];
					break;
			}
		};
	}

	/**
	 * generateAmount()
	 * 生成金额
	 * ----------------
	 * @return Float
	 * @author Verdient。
	 */
	public function generateAmount(){
		return round(rand(1, 20) / 100, 2);
	}

	/**
	 * sendBaseInfo()
	 * 发送企业基本信息
	 * ----------------
	 * @param Array $data 待添加的数据
	 * -------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function sendBaseInfo(Array $data){
		Yii::$app->CUrl->setData($data, 'JSON');
		$result = Yii::$app->CUrl->post('http://120.132.124.88/users/ticket/company/addBaseInfo', 'JSON');
		return isset($result['success']) && $result['success'] == true;
	}

	/**
	 * getTradeCompany()
	 * 关联企业信息
	 * -----------------
	 * @return Object
	 * @author Verdient。
	 */
	public function getTradeCompany(){
		return $this->hasOne(TradeCompany::className(), ['id' => 'enterprise_id']);
	}

	/**
	 * getBankCompany()
	 * 关联银行信息
	 * ----------------
	 * @return Object
	 * @author Verdient。
	 */
	public function getBankCompany(){
		return $this->hasOne(BankCompany::className(), ['companyId' => 'enterprise_id']);
	}

	/**
	 * getCompanyBaseInfo()
	 * 关联企业基本信息
	 * --------------------
	 * @return Object
	 * @author Verdient。
	 */
	public function getCompanyBaseInfo(){
		return $this->hasOne(CompanyBaseInfo::className(), ['enterprise_id' => 'enterprise_id']);
	}

	/**
	 * getUser()
	 * 关联用户
	 * ---------
	 * @return Object
	 * @author Verdient。
	 */
	public function getUser(){
		return $this->hasOne(User::className(), ['enterprise_id' => 'enterprise_id']);
	}
}