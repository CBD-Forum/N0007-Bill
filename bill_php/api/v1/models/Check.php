<?php
namespace api\v1\models;

use Yii;
use yii\base\Model;
use common\behaviors\ExceptionBehavior;

/**
 * Check Model
 * 审核 模型
 * -----------
 * @version 1.0.0
 * @author Verdient。
 */
class Check extends Model
{
	/**
	 * @var public $signature
	 * 签名字符串
	 * ----------------------
	 * @author Verdient。
	 */
	public $signature;

	/**
	 * @var public $instruction_id
	 * 任务ID
	 * ---------------------------
	 * @author Verdient。
	 */
	public $instruction_id;

	/**
	 * @var public $bill_id
	 * 票据ID
	 * --------------------
	 * @method POST
	 * @author Verdient。
	 */
	public $bill_id;

	/**
	 * @var public $field_status
	 * 字段状态
	 * -------------------------
	 * @method POST
	 * @author Verdient。
	 */
	public $field_status;

	/**
	 * @var public $annualized_rate_suggest
	 * 审核利率
	 * ------------------------------------
	 * @method POST
	 * @author Verdient。
	 */
	public $annualized_rate_suggest;

	/**
	 * @var public $instance
	 * 类的实例
	 * ---------------------
	 * @method GET
	 * @author Verdient。
	 */
	public $instance;

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
			[['bill_id', 'annualized_rate_suggest', 'signature', 'instruction_id'], 'filter', 'filter' => 'trim', 'on' => 'check'],
			['bill_id', 'required', 'message' => 19000, 'on' => 'check'],
			['field_status', 'required', 'message' => 19001, 'on' => 'check'],
			['signature', 'required', 'message' => 19011, 'on' => 'check'],
			['instruction_id', 'required', 'message' => 19012, 'on' => 'check'],
			['annualized_rate_suggest', 'required', 'message' => 19006, 'when' => self::whenFinanceOrReview(), 'on' => 'check'],
			['annualized_rate_suggest', 'validateAnnualizedRateSuggest', 'when' => self::whenFinanceOrReview(), 'on' => 'check'],
			['bill_id', 'integer', 'message' => 19002, 'on' => 'check'],
			['bill_id', 'validateBillId', 'on' => 'check'],
			['field_status', 'validateFieldStatus', 'on' => 'check']
		];
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
	public function validateBillId($attribute, $params){
		if(!$this->hasErrors()){
			if(!$this->instance = Bill::findOne(['id' => $this->bill_id, 'status' => Bill::STATUS_CHECK_PENDING])){
				$this->addError($attribute, 19003);
			}
		}
	}

	/**
	 * validateFieldStatus(String $attribute, Mixed $params)
	 * 验证字段状态
	 * -----------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateFieldStatus($attribute, $params){
		if(!$this->hasErrors()){
			if(!is_array($this->field_status)){
				return $this->addError($attribute, 19004);
			}
			$field_status_map = eval(Bill::FIELD_STATUS_MAP);
			if(count($this->field_status) != count($field_status_map)){
				return $this->addError($attribute, 19005);
			}
			foreach($this->field_status as $key => $value) {
				if(($value !== 0 && $value !== 1) || !isset($field_status_map)){
					return $this->addError($attribute, 19005);
				}
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
				$this->addError($attribute, 19007);
			}
		}
	}

	/**
	 * isFinanceOrReview(Object $userInfo = null)
	 * 是否是财务账户或复核账户
	 * ------------------------------------------
	 * @param Object $userInfo = null 用户信息对象
	 * -------------------------------------------
	 * @return function
	 * @author Verdient。
	 */
	public function isFinanceOrReview($userInfo = null){
		$userInfo = $userInfo ?: Yii::$app->OAuth2->userInfo;
		return in_array($userInfo->type, [User::TYPE_FINANCE, User::TYPE_REVIEW]);
	}

	/**
	 * whenFinanceOrReview()
	 * 是否是财务账户或复核账户
	 * ------------------------
	 * @return function
	 * @author Verdient。
	 */
	public function whenFinanceOrReview(){
		return function($model){
			return self::isFinanceOrReview();
		};
	}

	/**
	 * createBill(String $owner, Object $userInfo)
	 * 创建票据
	 * -------------------------------------------
	 * @param String $owner 拥有者公钥
	 * @param Object $userInfo 用户信息
	 * --------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function createBill($owner, $userInfo){
		return Yii::$app->blockChain->createBill(
			$this->signature,
			$this->instruction_id,
			$userInfo->address,
			$this->instance->bill_number,
			$this->instance->type,
			$this->instance->drawer,
			$this->instance->taker,
			$this->instance->acceptor,
			$this->instance->acceptance_at,
			$this->instance->face_amount,
			$this->instance->possessor,
			$owner,
			$this->instance->issue_at,
			$userInfo->name
		);
	}
}