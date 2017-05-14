<?php
namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\components\ActiveRecord;

/**
 * Bill Model
 * 票据模型
 * ----------
 * @version 1.0.0
 * @author Verdient。
 */
class Bill extends ActiveRecord
{
	/**
	 * @status const STATUS_CHECK_PENDING
	 * 待审核
	 * ----------------------------------
	 * @author Verdient。
	 */
	const STATUS_CHECK_PENDING = '0';

	/**
	 * @status const STATUS_CHECK_FAILED
	 * 审核失败
	 * ---------------------------------
	 * @author Verdient。
	 */
	const STATUS_CHECK_FAILED = 1;

	/**
	 * @status const STATUS_HOLDING
	 * 持有中
	 * ----------------------------
	 * @author Verdient。
	 */
	const STATUS_HOLDING = 2;

	/**
	 * @status const STATUS_LISTING
	 * 挂牌中
	 * ----------------------------
	 * @author Verdient。
	 */
	const STATUS_LISTING = 3;

	/**
	 * @status const STATUS_PAYMENT_FAILED
	 * 付款失败
	 * -----------------------------------
	 * @author Verdient。
	 */
	const STATUS_PAYMENT_FAILED = 4;

	/**
	 * @status const STATUS_WAITING_POSSESSOR_ASSIGNMENT
	 * 等待持有人转让
	 * -------------------------------------------------
	 * @author Verdient。
	 */
	const STATUS_WAITING_POSSESSOR_ASSIGNMENT = 5;

	/**
	 * @status const STATUS_WAITING_INVESTOR_CONFIRM
	 * 等待投资人确认
	 * ---------------------------------------------
	 * @author Verdient。
	 */
	const STATUS_WAITING_INVESTOR_CONFIRM = 6;

	/**
	 * @status const STATUS_COLLECTION_FAILED
	 * 收款失败
	 * --------------------------------------
	 * @author Verdient。
	 */
	const STATUS_COLLECTION_FAILED = 7;

	/**
	 * @status const STATUS_WAITING_POSSESSOR_REVOKE_CONFIRM_BEFORE_ASSIGNMENT
	 * 等待拥有者确认转让前撤单
	 * -----------------------------------------------------------------------
	 * @author Verdient。
	 */
	const STATUS_WAITING_POSSESSOR_REVOKE_CONFIRM_BEFORE_ASSIGNMENT = 8;

	/**
	 * @status const STATUS_WAITING_POSSESSOR_REVOKE_CONFIRM_AFTER_ASSIGNMENT
	 * 等待拥有者确认转让后撤单
	 * ----------------------------------------------------------------------
	 * @author Verdient。
	 */
	const STATUS_WAITING_POSSESSOR_REVOKE_CONFIRM_AFTER_ASSIGNMENT = 9;

	/**
	 * @status const STATUS_REFUND_FAILED
	 * 退款失败
	 * ----------------------------------
	 * @author Verdient。
	 */
	const STATUS_REFUND_FAILED = 10;

	/**
	 * @status const STATUS_POSSESSOR_REFUSE_REVOKE_BEFORE_ASSIGNMENT
	 * 拥有者拒绝转让前撤单
	 * --------------------------------------------------------------
	 * @author Verdient。
	 */
	const STATUS_POSSESSOR_REFUSE_REVOKE_BEFORE_ASSIGNMENT = 11;

	/**
	 * @status const STATUS_POSSESSOR_REFUSE_REVOKE_AFTER_ASSIGNMENT
	 * 拥有者拒绝转让后撤单
	 * -------------------------------------------------------------
	 * @author Verdient。
	 */
	const STATUS_POSSESSOR_REFUSE_REVOKE_AFTER_ASSIGNMENT = 12;

	/**
	 * @status const STATUS_FINISHED
	 * 已完成
	 * -----------------------------
	 * @author Verdient。
	 */
	const STATUS_FINISHED = 99;

	/**
	 * @status const STATUS_DELETED
	 * 已删除
	 * ----------------------------
	 * @author Verdient。
	 */
	const STATUS_DELETED = 100;

	/**
	 * @type const TYPE_All
	 * 所有
	 * --------------------
	 * @author Verdient。
	 */
	const TYPE_All = '0';

	/**
	 * @type const TYPE_BANK_DRAFT
	 * 银行汇票
	 * ---------------------------
	 * @author Verdient。
	 */
	const TYPE_BANK_DRAFT = 1;

	/**
	 * @type const TYPE_COMMERCIAL_DRAFT
	 * 商业汇票
	 * ---------------------------------
	 * @author Verdient。
	 */
	const TYPE_COMMERCIAL_DRAFT = 2;

	/**
	 * @type const ACCEPTOR_TYPE_ENTERPRISE
	 * 企业
	 * ------------------------------------
	 * @author Verdient。
	 */
	const ACCEPTOR_TYPE_ENTERPRISE = 1;

	/**
	 * @type const ACCEPTOR_TYPE_GUO_GU
	 * 国股
	 * --------------------------------
	 * @author Verdient。
	 */
	const ACCEPTOR_TYPE_GUO_GU = 2;

	/**
	 * @type const ACCEPTOR_TYPE_CHENG_SHANG
	 * 城商
	 * -------------------------------------
	 * @author Verdient。
	 */
	const ACCEPTOR_TYPE_CHENG_SHANG = 3;

	/**
	 * @type const ACCEPTOR_TYPE_NONG_SHANG
	 * 农商
	 * -------------------------------------
	 * @author Verdient。
	 */
	const ACCEPTOR_TYPE_NONG_SHANG = 4;

	/**
	 * @type const ACCEPTOR_TYPE_WAI_ZI
	 * 外资
	 * ---------------------------------
	 * @author Verdient。
	 */
	const ACCEPTOR_TYPE_WAI_ZI = 5;

	/**
	 * @type const ACCEPTOR_TYPE_NONG_XIN
	 * 农信
	 * ----------------------------------
	 * @author Verdient。
	 */
	const ACCEPTOR_TYPE_NONG_XIN = 6;

	/**
	 * @type const ACCEPTOR_TYPE_FINANCE_COMPANY
	 * 财务公司
	 * -----------------------------------------
	 * @author Verdient。
	 */
	const ACCEPTOR_TYPE_FINANCE_COMPANY = 7;

	/**
	 * @type const ACCEPTOR_TYPE_OTHER
	 * 其他
	 * -------------------------------
	 * @author Verdient。
	 */
	const ACCEPTOR_TYPE_OTHER = 8;

	/**
	 * @fieldStatus const FIELD_STATUS_DEFAULT
	 * 默认字段默认值
	 * ---------------------------------------
	 * @author Verdient。
	 */
	const FIELD_STATUS_DEFAULT = '0';

	/**
	 * @filter const FILTER_HOLDING
	 * 持有中
	 * ----------------------------
	 * @author Verdient。
	 */
	const FILTER_HOLDING = 1;

	/**
	 * @filter const FILTER_TRADING
	 * 交易中
	 * ----------------------------
	 * @author Verdient。
	 */
	const FILTER_TRADING = 2;

	/**
	 * @filter const FILTER_FINLISHED
	 * 已结束
	 * ------------------------------
	 * @author Verdient。
	 */
	const FILTER_FINLISHED = 3;

	/**
	 * @map const FIELD_STATUS_MAP
	 * 字段状态对应关系
	 * ---------------------------
	 * @author Verdient。
	 */
	const FIELD_STATUS_MAP = "return [
		'bill_number' => 1, //2的0次方，票据编号
		'drawer' => 2, //2的1次方，出票人
		'acceptor' => 4, //2的2次方，承兑人
		'face_amount' => 8, //2的3次方，票据金额
		'acceptance_at' => 16, //2的4次方，承兑日期
		'type' => 32, //2的5次方，票据类型
		'taker' => 64, //2的6次方，出票人
		'acceptor_type' => 128, //2的7次方，承兑人类型
		'issue_at' => 256, //2的8次方，出票日期
	];";

	/**
	 * @map const ACCEPTOR_TYPE_MAP
	 * 承兑人类型对应关系
	 * ----------------------------
	 * @author Verdient。
	 */
	const ACCEPTOR_TYPE_MAP = "return [
		common\models\Bill::ACCEPTOR_TYPE_ENTERPRISE => '企业',
		common\models\Bill::ACCEPTOR_TYPE_GUO_GU => '国股',
		common\models\Bill::ACCEPTOR_TYPE_CHENG_SHANG => '城商',
		common\models\Bill::ACCEPTOR_TYPE_NONG_SHANG => '农商',
		common\models\Bill::ACCEPTOR_TYPE_WAI_ZI => '外资',
		common\models\Bill::ACCEPTOR_TYPE_NONG_XIN => '农信',
		common\models\Bill::ACCEPTOR_TYPE_FINANCE_COMPANY => '财务公司',
		common\models\Bill::ACCEPTOR_TYPE_OTHER => '其他'
	];";

	/**
	 * @map const TYPE_MAP
	 * 票据类型对应关系
	 * -------------------
	 * @author Verdient。
	 */
	const TYPE_MAP = "return [
		common\models\Bill::TYPE_BANK_DRAFT => '银行汇票',
		common\models\Bill::TYPE_COMMERCIAL_DRAFT => '商业汇票'
	];";

	/**
	 * @map const STATUS_MAP
	 * 票据状态对应关系
	 * ---------------------
	 * @author Verdient。
	 */
	const STATUS_MAP = "return [
		common\models\Bill::STATUS_CHECK_PENDING => '待审核',
		common\models\Bill::STATUS_CHECK_FAILED => '审核失败',
		common\models\Bill::STATUS_HOLDING => '持有中',
		common\models\Bill::STATUS_LISTING => '挂牌中',
		common\models\Bill::STATUS_PAYMENT_FAILED => '待付款',
		common\models\Bill::STATUS_WAITING_POSSESSOR_ASSIGNMENT => '待转让',
		common\models\Bill::STATUS_WAITING_INVESTOR_CONFIRM => '待确认',
		common\models\Bill::STATUS_COLLECTION_FAILED => '待收款',
		common\models\Bill::STATUS_WAITING_POSSESSOR_REVOKE_CONFIRM_BEFORE_ASSIGNMENT => '撤单中',
		common\models\Bill::STATUS_WAITING_POSSESSOR_REVOKE_CONFIRM_AFTER_ASSIGNMENT => '撤单中',
		common\models\Bill::STATUS_REFUND_FAILED => '待退款',
		common\models\Bill::STATUS_POSSESSOR_REFUSE_REVOKE_BEFORE_ASSIGNMENT => '拥有者拒绝撤单',
		common\models\Bill::STATUS_POSSESSOR_REFUSE_REVOKE_AFTER_ASSIGNMENT => '拥有者拒绝撤单',
		common\models\Bill::STATUS_FINISHED => '已完成',
		common\models\Bill::STATUS_DELETED => '已删除'
	];";

	/**
	 * validateAcceptanceAt(String $attribute, Mixed $params)
	 * 验证承兑日期
	 * ------------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateAcceptanceAt($attribute, $params){
		if(!$this->hasErrors()){
			if(strtotime($this->acceptance_at) < strtotime($this->issue_at)){
				$this->addError($attribute, 13055);
			}
		}
	}

	/**
	 * validateAnnualizedRate(String $attribute, Mixed $params)
	 * 验证年化利率
	 * --------------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateAnnualizedRate($attribute, $params){
		if(!$this->hasErrors()){
			if(!is_numeric($this->annualized_rate) || $this->annualized_rate < 0 || $this->annualized_rate > 100){
				$this->addError($attribute, 13006);
			}
		}
	}

	/**
	 * validateAnnualizedRateSuggest(String $attribute, Mixed $params)
	 * 验证建议年化利率
	 * ---------------------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateAnnualizedRateSuggest($attribute, $params){
		if(!$this->hasErrors()){
			if(!is_numeric($this->annualized_rate_suggest) || $this->annualized_rate_suggest < 0 || $this->annualized_rate_suggest > 100){
				$this->addError($attribute, 13036);
			}
		}
	}

	/**
	 * validateId(String $attribute, Mixed $params)
	 * 验证票据ID
	 * --------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateId($attribute, $params){
		if(!$this->hasErrors()){
			$query = self::find()->where(['id' => $this->id]);
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
				$this->addError($attribute, 13039);
			}
		}
	}

	/**
	 * validateFinancingAmount(String $attribute, Mixed $params)
	 * 验证融资金额
	 * ---------------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateFinancingAmount($attribute, $params){
		if(!$this->hasErrors()){
			if($this->instance->face_amount < $this->financing_amount){
				$this->addError($attribute, 13056);
			}
		}
	}

	/**
	 * validateInvestor(String $attribute, Mixed $params)
	 * 验证投资人
	 * ---------------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateInvestor($attribute, $params){
		if(!$this->hasErrors()){
			if($this->instance->possessor == $this->investor){
				$this->addError($attribute, 13064);
			}
		}
	}

	/**
	 * typeFilter()
	 * 类型过滤器
	 * ------------
	 * @return String
	 * @author Verdient。
	 */
	public function typeFilter(){
		return function ($value) {
			$map = ['银行承兑' => self::TYPE_BANK_DRAFT, '商业承兑' => self::TYPE_COMMERCIAL_DRAFT];
			return empty($value) ? [self::TYPE_BANK_DRAFT, self::TYPE_COMMERCIAL_DRAFT] : $map[$value];
		};
	}

	/**
	 * annualizedRateFilter()
	 * 年化利率过滤器
	 * ----------------------
	 * @return function
	 * @author Verdient。
	 */
	public function annualizedRateFilter(){
		return function($value){
			return $this->hasErrors() ? null : InterestRate::annualizedRate($this->financing_amount, $this->instance->face_amount, ((strtotime($this->instance->acceptance_at) - strtotime('Today')) / 86400));
		};
	}

	/**
	 * wechselspesenFilter()
	 * 贴现费过滤器
	 * ---------------------
	 * @return function
	 * @author Verdient。
	 */
	public function wechselspesenFilter(){
		return function($value){
			return $this->hasErrors() ? null : round(($this->instance->face_amount - $this->financing_amount), 2);
		};
	}

	/**
	 * financingAmountFilter()
	 * 融资金额过滤器
	 * -----------------------
	 * @return function
	 * @author Verdient。
	 */
	public function financingAmountFilter(){
		return function($value){
			return $this->hasErrors() ? null : round(($this->instance->amount - $this->wechselspesen), 2);
		};
	}

	/**
	 * checkInfoDecode(Object / Array $value)
	 * 解码字段审核信息
	 * --------------------------------------
	 * @param Object / Array $value 属性集合
	 * -------------------------------------
	 * @return Object / Array
	 * @author yuan
	 */
	public function checkInfoDecode(&$value){
		foreach ($this->fieldStatusMap as $key => $field) {
			if ($value['field_status'] & $field) {
				$value[$key . '_status'] = 1;
			} else {
				$value[$key . '_status'] = 0;
			}
		}
		unset($value['field_status']);
	}

	/**
	 * getStatusMap()
	 * 获取状态对应关系
	 * ----------------
	 * @return Array
	 * @author Verdient。
	 */
	public function getStatusMap(){
		return eval(self::STATUS_MAP);
	}

	/**
	 * getFieldStatusMap()
	 * 获取字段状态对应关系
	 * --------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function getFieldStatusMap(){
		return eval(self::FIELD_STATUS_MAP);
	}

	/**
	 * getAcceptorTypeMap()
	 * 获取承兑人类型对应关系
	 * ----------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function getAcceptorTypeMap(){
		return eval(self::ACCEPTOR_TYPE_MAP);
	}

	/**
	 * getTypeMap()
	 * 获取票据类型对应关系
	 * --------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function getTypeMap(){
		return eval(self::TYPE_MAP);
	}

	/**
	 * getPossessorEnterpriseId()
	 * 获取拥有者企业ID
	 * --------------------------
	 * @return Integer
	 * @author Verdient。
	 */
	public function getPossessorEnterpriseId(){
		$result = User::find()->select('enterprise_id')->where(['id' => $this->possessor])->asArray()->one();
		return isset($result['enterprise_id']) ? $result['enterprise_id'] : null;
	}

	/**
 	 * getPossessorAddress()
 	 * 获取拥有者公钥
 	 * ---------------------
 	 * @return Object
 	 * @author Verdient。
 	 */
	public function getPossessorAddress(){
		return $this->hasOne(PublicKey::className(), ['user_id' => 'possessor'])->select(['user_id', 'hph_public_key.status', 'public_key', 'random']);
	}

	/**
	 * getUser()
	 * 关联用户信息
	 * ------------
	 * @return Object
	 * @author Verident。
	 */
	public function getUser(){
		return $this->hasOne(User::className(), ['id' => 'possessor']);
	}

	/**
	 * getUserWithTradeCompany()
	 * 关联用户和交易公司信息
	 * -------------------------
	 * @return Object
	 * @author Verident。
	 */
	public function getUserWithTradeCompany(){
		return $this->hasOne(User::className(), ['id' => 'possessor'])->with('tradeCompany');
	}

	/**
	 * getAgent()
	 * 关联经办人信息
	 * --------------
	 * @return Object
	 * @author Verident。
	 */
	public function getAgent(){
		return $this->hasOne(Agent::className(), ['id' => 'agent_id']);
	}

	/**
	 * whenBankDraft()
	 * 是否是银行承兑汇票
	 * ------------------
	 * @return function
	 * @author Verdient。
	 */
	public function whenBankDraft(){
		return function($model){
			return $model->type == self::TYPE_BANK_DRAFT;
		};
	}

	/**
	 * whenCommercialDraft()
	 * 是否是商业承兑汇票
	 * ---------------------
	 * @return function
	 * @author Verdient。
	 */
	public function whenCommercialDraft(){
		return function($model){
			return $model->type == self::TYPE_COMMERCIAL_DRAFT;
		};
	}
}