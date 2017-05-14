<?php
namespace api\v1\models;

use Yii;
use common\components\ActiveDataProvider;
use common\components\upload\Image;
use common\filters\NumberFilter;
use common\models\Bill as BaseBill;
use common\validators\BillNumberValidator;
use common\validators\EndTimeValidator;
use common\validators\TradePasswordValidator;
use api\v1\components\Exception;

/**
 * Bill Model
 * 票据模型
 * ----------
 * @version 1.0.0
 * @author Verdient。
 */
class Bill extends BaseBill
{
	/**
	 * @var public $field
	 * 验证字段
	 * ------------------
	 * @author yuan
	 */
	public $field;

	/**
	 * @var public $range
	 * 范围
	 * ------------------
	 * @method GET
	 * @author Verdient。
	 */
	public $range;

	/**
	 * @var public $filter
	 * 过滤
	 * -------------------
	 * @method GET
	 * @author Verdient。
	 */
	public $filter;

	/**
	 * @var public $trade_password
	 * 交易密码
	 * ---------------------------
	 * @method POST
	 * @author Verdient。
	 */
	public $trade_password;

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
			[['bill_number', 'type', 'drawer', 'taker', 'acceptor', 'acceptance_at', 'face_amount'], 'filter', 'filter' => 'trim', 'on' => ['create', 'update']],
			['annualized_rate', 'filter', 'filter' => 'trim', 'on' => 'maintenance'],
			['bill_number', 'required', 'message' => 13002, 'on' => ['create', 'update']],
			['type', 'required', 'message' => 13000, 'on' => ['create', 'update']],
			['drawer', 'required', 'message' => 13008, 'on' => ['create', 'update']],
			['taker', 'required', 'message' => 13009, 'on' => ['create', 'update']],
			['acceptor', 'required', 'message' => 13010, 'on' => ['create', 'update']],
			['acceptor_type', 'required', 'message' => 13044, 'on' => ['create', 'update']],
			['issue_at', 'required', 'message' => 13052, 'on' => ['create', 'update']],
			['acceptance_at', 'required', 'message' => 13011, 'on' => ['create', 'update']],
			['face_amount', 'required', 'message' => 13012, 'on' => ['create', 'update']],
			['bill_front_path', 'required', 'message' => 13017, 'on' => ['create', 'update']],
			['bill_back_path', 'required', 'message' => 13018, 'on' => ['create', 'update']],
			['annualized_rate', 'required', 'message' => 13003, 'on' => 'maintenance'],
			['bill_number', BillNumberValidator::className(), 'message' => 13007, 'on' => ['create', 'update']],
			['type', 'in', 'range' => [self::TYPE_BANK_DRAFT, self::TYPE_COMMERCIAL_DRAFT], 'message' => 13004, 'on' => ['create', 'update']],
			['drawer', 'string', 'max' => 50, 'message' => 13013, 'tooLong' => 13013, 'on' => ['create', 'update']],
			['taker', 'string', 'max' => 50, 'message' => 13014, 'tooLong' => 13014, 'on' => ['create', 'update']],
			['acceptor', 'string', 'max' => 50, 'message' => 13015, 'tooLong' => 13015, 'on' => ['create', 'update']],
			['acceptor_type', 'in', 'range' => [self::ACCEPTOR_TYPE_GUO_GU, self::ACCEPTOR_TYPE_CHENG_SHANG, self::ACCEPTOR_TYPE_NONG_SHANG, self::ACCEPTOR_TYPE_WAI_ZI, self::ACCEPTOR_TYPE_NONG_XIN, self::ACCEPTOR_TYPE_FINANCE_COMPANY, self::ACCEPTOR_TYPE_OTHER], 'when' => self::whenBankDraft(), 'message' => 13045, 'on' => ['create', 'update']],
			['acceptor_type', 'in', 'range' => [self::ACCEPTOR_TYPE_ENTERPRISE], 'when' => self::whenCommercialDraft(), 'message' => 13045, 'on' => ['create', 'update']],
			['issue_at', 'date', 'format' => 'yyyy-MM-dd', 'max' => date('Y-m-d'), 'message' => 13053, 'tooBig' => 13054, 'on' => ['create', 'update']],
			['acceptance_at', 'date', 'format' => 'yyyy-MM-dd', 'message' => 13016, 'on' => ['create', 'update']],
			['acceptance_at', 'validateAcceptanceAt', 'on' => ['create', 'update']],
			['bill_number', 'unique', 'filter' => ['<', 'status', self::STATUS_FINISHED], 'message' => 13019, 'on' => 'create'],
			['bill_front_path', 'unique', 'message' => 13042, 'on' => 'create'],
			['bill_back_path', 'unique', 'message' => 13043, 'on' => 'create'],
			['bill_front_path', 'exist', 'targetClass' => Image::className(), 'targetAttribute' => 'path', 'filter' => ['type' => Image::TYPE_BILL_FRONT], 'message' => 13020, 'on' => ['create', 'update']],
			['bill_back_path', 'exist', 'targetClass' => Image::className(), 'targetAttribute' => 'path', 'filter' => ['type' => Image::TYPE_BILL_BACK], 'message' => 13021, 'on' => ['create', 'update']],
			['face_amount', 'filter', 'filter' => NumberFilter::unformat(), 'on' => 'create'],
			['face_amount', 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/', 'message' => 13005, 'on' => ['create', 'update']],
			['id', 'filter', 'filter' => 'trim', 'on' => ['listing', 'delist', 'info', 'possessorAssignment', 'investorConfirm', 'possessorRevoke', 'investorRevoke', 'investorCancelRevoke', 'possessorConfirmRevoke', 'possessorRefuseRevoke', 'investorContractFile', 'possessorContractFile', 'payment', 'collection', 'refund', 'delete']],
			[['signature', 'instruction_id'], 'filter', 'filter' => 'trim', 'on' => ['listing', 'delist', 'possessorAssignment', 'investorConfirm', 'possessorRevoke', 'investorRevoke', 'investorCancelRevoke', 'possessorConfirmRevoke', 'delete']],
			['signature', 'required', 'message' => 13048, 'on' => ['listing', 'delist', 'possessorAssignment', 'investorConfirm', 'possessorRevoke', 'investorRevoke', 'investorCancelRevoke', 'possessorConfirmRevoke', 'delete']],
			['instruction_id', 'required', 'message' => 13049, 'on' => ['listing', 'delist', 'possessorAssignment', 'investorConfirm', 'possessorRevoke', 'investorRevoke', 'investorCancelRevoke', 'possessorConfirmRevoke', 'delete']],
			[['financing_amount', 'possessor'], 'filter', 'filter' => 'trim', 'on' => 'listing'],
			['id', 'required', 'message' => 13038, 'on' => ['listing', 'delist', 'info', 'possessorAssignment', 'investorConfirm', 'possessorRevoke', 'investorRevoke', 'investorCancelRevoke', 'possessorConfirmRevoke', 'possessorRefuseRevoke', 'investorContractFile', 'possessorContractFile', 'payment', 'collection', 'refund', 'delete']],
			// ['trade_password', 'required', 'message' => 13067, 'on' => ['payment', 'collection', 'refund', 'delist', 'possessorConfirmRevoke']],
			['financing_amount', 'required', 'message' => 13057, 'on' => 'listing'],
			['id', 'integer', 'message' => 13040, 'on' => ['listing', 'delist', 'info', 'possessorAssignment', 'investorConfirm', 'possessorRevoke', 'investorRevoke', 'investorCancelRevoke', 'possessorConfirmRevoke', 'possessorRefuseRevoke', 'investorContractFile', 'possessorContractFile', 'payment', 'collection', 'refund', 'delete']],
			['financing_amount', 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/', 'message' => 13063, 'on' => 'listing'],
			['id', 'validateId', 'params' => ['filter' => [['possessor' => $this->possessor, 'status' => self::STATUS_HOLDING], ['>', 'acceptance_at', date('Y-m-d', strtotime('Today +3day'))]]], 'on' => 'listing'],
			['id', 'validateId', 'params' => ['filter' => ['status' => self::STATUS_LISTING]], 'on' => 'delist'],
			['id', 'validateId', 'on' => 'info'],
			['id', 'validateId', 'params' => ['filter' => ['status' => self::STATUS_WAITING_POSSESSOR_ASSIGNMENT, 'possessor' => $this->possessor]], 'on' => 'possessorAssignment'],
			['id', 'validateId', 'params' => ['filter' => ['status' => self::STATUS_WAITING_INVESTOR_CONFIRM, 'investor' => $this->investor]], 'on' => 'investorConfirm'],
			['id', 'validateId', 'params' => ['filter' => ['status' => self::STATUS_LISTING, 'possessor' => $this->possessor]], 'on' => 'possessorRevoke'],
			['id', 'validateId', 'params' => ['filter' => ['status' => [self::STATUS_WAITING_POSSESSOR_ASSIGNMENT, self::STATUS_WAITING_INVESTOR_CONFIRM, 'investor' => $this->investor]]], 'on' => 'investorRevoke'],
			['id', 'validateId', 'params' => ['filter' => ['status' => [self::STATUS_WAITING_POSSESSOR_REVOKE_CONFIRM_BEFORE_ASSIGNMENT, self::STATUS_WAITING_POSSESSOR_REVOKE_CONFIRM_AFTER_ASSIGNMENT, self::STATUS_POSSESSOR_REFUSE_REVOKE_BEFORE_ASSIGNMENT, self::STATUS_POSSESSOR_REFUSE_REVOKE_AFTER_ASSIGNMENT], 'investor' => $this->investor]], 'on' => 'investorCancelRevoke'],
			['id', 'validateId', 'params' => ['filter' => ['status' => [self::STATUS_WAITING_POSSESSOR_REVOKE_CONFIRM_BEFORE_ASSIGNMENT, self::STATUS_WAITING_POSSESSOR_REVOKE_CONFIRM_AFTER_ASSIGNMENT], 'possessor' => $this->possessor]], 'on' => ['possessorConfirmRevoke', 'possessorRefuseRevoke']],
			['id', 'validateId', 'params' => ['filter' => [['between', 'status', self::STATUS_WAITING_POSSESSOR_ASSIGNMENT, self::STATUS_REFUND_FAILED], ['investor' => $this->investor]]], 'on' => 'investorContractFile'],
			['id', 'validateId', 'params' => ['filter' => [['between', 'status', self::STATUS_WAITING_POSSESSOR_ASSIGNMENT, self::STATUS_REFUND_FAILED], ['possessor' => $this->possessor]]], 'on' => 'possessorContractFile'],
			['id', 'validateId', 'params' => ['filter' => ['status' => self::STATUS_PAYMENT_FAILED, 'investor' => $this->investor]], 'on' => 'payment'],
			['id', 'validateId', 'params' => ['filter' => ['status' => self::STATUS_COLLECTION_FAILED, 'possessor' => $this->possessor]], 'on' => 'collection'],
			['id', 'validateId', 'params' => ['filter' => ['status' => self::STATUS_REFUND_FAILED, 'possessor' => $this->possessor]], 'on' => 'refund'],
			['id', 'validateId', 'params' => ['filter' => ['status' => [self::STATUS_CHECK_PENDING, self::STATUS_CHECK_FAILED, self::STATUS_HOLDING, self::STATUS_FINISHED], 'is_traded' => 0]], 'on' => 'delete'],
			['id', 'validateId', 'params' => ['filter' => ['status' => self::STATUS_CHECK_FAILED]], 'on' => 'update'],
			['financing_amount', 'validateFinancingAmount', 'on' => 'listing'],
			['annualized_rate', 'filter', 'filter' => self::annualizedRateFilter(), 'on' => 'listing'],
			['wechselspesen', 'filter', 'filter' => self::wechselspesenFilter(), 'on' => 'listing'],
			['annualized_rate', 'required', 'message' => 13058, 'on' => 'listing'],
			['wechselspesen', 'required', 'message' => 13060, 'on' => 'listing'],
			['annualized_rate', 'validateAnnualizedRate', 'on' => 'listing'],
			['investor', 'validateInvestor', 'on' => 'delist'],
			['trade_password', TradePasswordValidator::className(), 'userIDAttribute' => 'investor', 'message' => 13068, 'on' => ['payment', 'delist']],
			['trade_password', TradePasswordValidator::className(), 'userIDAttribute' => 'possessor', 'message' => 13068, 'on' => ['collection', 'refund', 'possessorConfirmRevoke']],
			['type', 'in', 'range' => [self::TYPE_BANK_DRAFT, self::TYPE_COMMERCIAL_DRAFT, [self::TYPE_BANK_DRAFT, self::TYPE_COMMERCIAL_DRAFT]], 'message' => 13004, 'on' => ['waitingMaintenanceList', 'waitingCheckList', 'waitingSubstituteMaintenanceList', 'listingList', 'myList', 'tradeList']],
			['startTime', 'default', 'value' => '1970-01-01', 'on' => ['waitingCheckList', 'waitingMaintenanceList', 'waitingCheckList', 'waitingSubstituteMaintenanceList', 'listingList', 'myList', 'tradeList', 'creatorBillList']],
			['endTime', 'default', 'value' => '2038-01-19', 'on' => ['waitingCheckList', 'waitingMaintenanceList', 'waitingCheckList', 'waitingSubstituteMaintenanceList', 'listingList', 'myList', 'tradeList', 'creatorBillList']],
			['startTime', 'date', 'format' => 'yyyy-MM-dd', 'message' => 13031, 'on' => ['waitingCheckList', 'waitingMaintenanceList', 'waitingCheckList', 'waitingSubstituteMaintenanceList', 'listingList', 'myList', 'tradeList', 'creatorBillList']],
			['endTime', 'date', 'format' => 'yyyy-MM-dd', 'message' => 13032, 'on' => ['waitingCheckList', 'waitingMaintenanceList', 'waitingCheckList', 'waitingSubstituteMaintenanceList', 'listingList', 'myList', 'tradeList', 'creatorBillList']],
			['endTime', EndTimeValidator::className(), 'message' => 13035, 'on' => ['waitingCheckList', 'waitingMaintenanceList', 'waitingCheckList', 'waitingSubstituteMaintenanceList', 'listingList', 'myList', 'tradeList', 'creatorBillList']],
			['page', 'default', 'value' => 1, 'on' => ['waitingCheckList', 'waitingMaintenanceList', 'waitingCheckList', 'waitingSubstituteMaintenanceList', 'listingList', 'myList', 'tradeList', 'creatorBillList']],
			['pageSize', 'default', 'value' => 5, 'on' => ['waitingCheckList', 'waitingMaintenanceList', 'waitingCheckList', 'waitingSubstituteMaintenanceList', 'listingList', 'myList', 'tradeList', 'creatorBillList']],
			['page', 'integer', 'min' => 1, 'message' => 13022, 'tooSmall' => 13022, 'on' => ['waitingCheckList', 'waitingMaintenanceList', 'waitingCheckList', 'waitingSubstituteMaintenanceList', 'listingList', 'myList', 'tradeList', 'creatorBillList']],
			['pageSize', 'integer', 'min' => 1, 'message' => 13023, 'tooSmall' => 13023, 'on' => ['waitingCheckList', 'waitingMaintenanceList', 'waitingCheckList', 'waitingSubstituteMaintenanceList', 'listingList', 'myList', 'tradeList', 'creatorBillList']],
			['query', 'string', 'message' => 13037, 'on' => ['waitingCheckList', 'waitingMaintenanceList', 'waitingCheckList', 'waitingSubstituteMaintenanceList', 'listingList', 'creatorBillList', 'tradeList']],
			['sort', 'in', 'range' => ['type', '-type', 'face_amount', '-face_amount', 'apply_at', '-apply_at', 'acceptance_at', '-acceptance_at'], 'message' => 13024, 'on' => ['waitingCheckList', 'waitingSubstituteMaintenanceList']],
			['sort', 'in', 'range' => ['type', '-type', 'face_amount', '-face_amount', 'acceptance_at', '-acceptance_at', 'apply_at', '-apply_at', 'block_created_at', '-block_created_at'], 'message' => 13024, 'on' => ['waitingMaintenanceList', 'waitingCheckList', 'creatorBillList']],
			['range', 'in', 'range' => [0, 1, 2, 3, 4, 5, 6], 'message' => 13065, 'on' => ['listingList']],
			['filter', 'default', 'value' => self::FILTER_HOLDING, 'on' => 'tradeList'],
			['filter', 'in', 'range' => [self::FILTER_HOLDING, self::FILTER_TRADING, self::FILTER_FINLISHED], 'message' => 13066, 'on' => 'tradeList']
		];
	}

	/**
	 * waitingCheckList(Object $query)
	 * 获取待审核列表
	 * -------------------------------
	 * @param Object $query 查询对象
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function waitingCheckList($query){
		$provider = new ActiveDataProvider([
			'query' => $query
				->select(['id', 'possessor', 'acceptor', 'acceptance_at', 'status', 'type', 'bill_number', 'face_amount', 'apply_at', 'agent_id'])
				->asArray()
				->with('userWithTradeCompany', 'possessorAddress')
				->with('agent'),
			'sort' => [
				'defaultOrder' => [
					'apply_at' => SORT_ASC
				]
			]
		]);
		$data = $provider->models;
		$typeMap = eval(self::TYPE_MAP);
		foreach ($data as $key => $value) {
			$data[$key]['status'] = '待审核';
			$data[$key]['type'] = $typeMap[$value['type']];
			$data[$key]['face_amount'] = number_format($value['face_amount'], 2);
			$data[$key]['enterprise_name'] = isset($value['userWithTradeCompany']['tradeCompany']['orgName']) ? $value['userWithTradeCompany']['tradeCompany']['orgName'] : null;
			$data[$key]['agent_name'] = isset($value['agent']['name']) ? $value['agent']['name'] : null;
			$data[$key]['agent_mobile'] = isset($value['agent']['mobile']) ? $value['agent']['mobile'] : null;
			$data[$key]['possessor_address'] = isset($value['possessorAddress']['public_key']) ? $value['possessorAddress']['public_key'] : null;
			unset($data[$key]['userWithTradeCompany'], $data[$key]['possessor'], $data[$key]['agent'], $data[$key]['possessorAddress']);
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}

	/**
	 * getSelfWaitingCheckList(Object $query)
	 * 获取自己的待审核列表
	 * --------------------------------------
	 * @param Object $query 查询对象
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function getSelfWaitingCheckList($query){
		$provider = new ActiveDataProvider([
			'query' => $query
				->select(['id', 'possessor', 'acceptor', 'acceptance_at', 'status', 'type', 'bill_number', 'face_amount', 'apply_at', 'is_traded'])
				->asArray()
				->with('userWithTradeCompany', 'possessorAddress'),
			'sort' => [
				'defaultOrder' => [
					'apply_at' => SORT_ASC
				]
			]
		]);
		$data = $provider->models;
		$typeMap = eval(self::TYPE_MAP);
		foreach ($data as $key => $value) {
			$data[$key]['status'] = $this->statusMap[$value['status']];
			$data[$key]['type'] = $typeMap[$value['type']];
			$data[$key]['face_amount'] = number_format($value['face_amount'], 2);
			$data[$key]['enterprise_name'] = isset($value['userWithTradeCompany']['tradeCompany']['orgName']) ? $value['userWithTradeCompany']['tradeCompany']['orgName'] : null;
			$data[$key]['possessor_address'] = isset($value['possessorAddress']['public_key']) ? $value['possessorAddress']['public_key'] : null;
			unset($data[$key]['userWithTradeCompany'], $data[$key]['possessor'], $data[$key]['possessorAddress']);
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}

	/**
	 * waitingMaintenanceList(Object $query)
	 * 获取待维护列表
	 * -------------------------------------
	 * @param Object $query 查询对象
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function waitingMaintenanceList($query){
		$provider = new ActiveDataProvider([
			'query' => $query
				->select(['id', 'status', 'type', 'possessor', 'bill_number', 'drawer', 'acceptor', 'face_amount', 'issue_at', 'acceptance_at', 'annualized_rate_suggest', 'DATEDIFF(acceptance_at, NOW()) as left_day', 'bill_front_path', 'bill_back_path', 'is_traded'])
				->asArray()
				->with('possessorAddress'),
			'sort' => [
				'defaultOrder' => [
					'updated_at' => SORT_DESC
				]
			]
		]);
		$data = $provider->models;
		$typeMap = eval(self::TYPE_MAP);
		foreach($data as $key => $value){
			$data[$key]['left_day'] = $value['left_day'] < 0 ? 0 : $value['left_day'];
			$data[$key]['status'] = '待维护';
			$data[$key]['type'] = $typeMap[$value['type']];
			$data[$key]['face_amount'] = number_format($value['face_amount'], 2);
			$data[$key]['bill_front_path'] = Yii::$app->staticResource->path2url($value['bill_front_path']);
			$data[$key]['bill_back_path'] = Yii::$app->staticResource->path2url($value['bill_back_path']);
			$data[$key]['possessor_address'] = isset($value['possessorAddress']['public_key']) ? $value['possessorAddress']['public_key'] : null;
			unset($data[$key]['possessor'], $data[$key]['possessorAddress']);
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}

	/**
	 * waitingSubstituteMaintenanceList(Object $query)
	 * 获取代维护列表
	 * -----------------------------------------------
	 * @param Object $query 查询对象
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function waitingSubstituteMaintenanceList($query){
		$provider = new ActiveDataProvider([
			'query' => $query
				->select(['id', 'possessor', 'status', 'type', 'bill_number', 'drawer', 'acceptor', 'issue_at', 'acceptance_at', 'FORMAT(face_amount, 2) as face_amount', 'annualized_rate_suggest', 'DATEDIFF(acceptance_at, NOW()) as left_day', 'bill_front_path', 'bill_back_path', 'is_traded'])
				->asArray()
				->with('userWithTradeCompany', 'possessorAddress'),
			'sort' => [
				'defaultOrder' => [
					'updated_at' => SORT_DESC
				]
			]
		]);
		$data = $provider->models;
		$typeMap = eval(self::TYPE_MAP);
		foreach($data as $key => $value){
			$data[$key]['status'] = '待维护';
			$data[$key]['type'] = $typeMap[$value['type']];
			$data[$key]['left_day'] = $value['left_day'] < 0 ? 0 : $value['left_day'];
			$data[$key]['bill_front_path'] = Yii::$app->staticResource->path2url($value['bill_front_path']);
			$data[$key]['bill_back_path'] = Yii::$app->staticResource->path2url($value['bill_back_path']);
			$data[$key]['enterprise_name'] = isset($value['userWithTradeCompany']['tradeCompany']['orgName']) ? $value['userWithTradeCompany']['tradeCompany']['orgName'] : null;
			$data[$key]['possessor_address'] = isset($value['possessorAddress']['public_key']) ? $value['possessorAddress']['public_key'] : null;
			unset($data[$key]['userWithTradeCompany'], $data[$key]['possessor'], $data[$key]['possessorAddress']);
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}

	/**
	 * creatorBillList(Object $query)
	 * 获取创建的票据列表列表
	 * ------------------------------
	 * @param Object $query 查询对象
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function creatorBillList($query){
		$provider = new ActiveDataProvider([
			'query' => $query->select(['id', 'status', 'type', 'creator', 'possessor', 'acceptor', 'bill_number', 'annualized_rate_suggest', 'apply_at', 'block_created_at', 'FORMAT(face_amount, 2) as face_amount', 'acceptance_at', 'bill_front_path', 'bill_back_path'])->asArray(),
			'sort' => [
				'defaultOrder' => [
					'updated_at' => SORT_DESC
				]
			]
		]);
		$data = $provider->models;
		$typeMap = eval(self::TYPE_MAP);
		foreach($data as $key => $value){
			if($value['creator'] === $value['possessor']){
				$data[$key]['status'] = isset($this->statusMap[$value['status']]) ? $this->statusMap[$value['status']] : 'Unknown';
			}else{
				$data[$key]['status'] = '已完成';
			}
			$instruction = TradeRecord::find()->select('instruction')->where(['bill_id' => $value['id'], 'type' => TradeRecord::TYPE_TYPE_IN])->orderBy(['created_at' => SORT_DESC])->asArray()->one();
			$data[$key]['instruction'] = $instruction ? $instruction['instruction'] : null;
			$data[$key]['type'] = $typeMap[$value['type']];
			$data[$key]['bill_front_path'] = Yii::$app->staticResource->path2url($value['bill_front_path']);
			$data[$key]['bill_back_path'] = Yii::$app->staticResource->path2url($value['bill_back_path']);
			unset($data[$key]['creator'], $data[$key]['possessor']);
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}

	/**
	 * listingList()
	 * 挂牌票据列表
	 * -------------
	 * @return Array
	 * @author Verdient。
	 */
	public function listingList($query){
		$provider = new ActiveDataProvider([
			'query' => $query->select(['id', 'face_amount', 'bill_number', 'possessor', 'drawer', 'acceptor', 'type', 'acceptor_type', 'financing_amount', 'wechselspesen', 'acceptance_at', 'annualized_rate', 'DATEDIFF(acceptance_at, NOW()) as left_day'])->asArray(),
			'sort' => [
				'defaultOrder' => [
					'updated_at' => SORT_DESC
				]
			]
		]);
		$data = $provider->models;
		foreach($data as $key => $value){
			$user = User::find()->select('name')->where(['id' => $value['possessor']])->asArray()->one();
			$data[$key]['type'] = $this->typeMap[$value['type']];
			$data[$key]['acceptor_type'] = $value['acceptor_type'] == self::ACCEPTOR_TYPE_ENTERPRISE ? $value['acceptor'] : $this->acceptorTypeMap[$value['acceptor_type']];
			$data[$key]['face_amount'] = number_format($value['face_amount'], 2);
			$data[$key]['financing_amount'] = number_format($value['financing_amount'], 2);
			$data[$key]['wechselspesen'] = number_format($value['wechselspesen'], 2);
			$data[$key]['annualized_rate'] = number_format($value['annualized_rate'], 2);
			$data[$key]['possessor_name'] = isset($user['name']) ? $user['name'] : null;
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}

	/**
	 * myList()
	 * 我的票据列表
	 * ------------
	 * @return Array
	 * @author Verdient。
	 */
	public function myList($query){
		$provider = new ActiveDataProvider([
			'query' => $query->select(['id', 'type', 'status', 'possessor', 'investor', 'bill_number', 'drawer', 'face_amount', 'acceptor', 'acceptor_type', 'acceptance_at', 'DATEDIFF(acceptance_at, NOW()) as left_day', 'financing_amount', 'wechselspesen', 'annualized_rate', 'investor_contract_path', 'possessor_contract_path'])->asArray(),
			'sort' => [
				'defaultOrder' => [
					'updated_at' => SORT_DESC
				]
			]
		]);
		$data = $provider->models;
		foreach($data as $key => $value){
			$data[$key]['type'] = $this->typeMap[$value['type']];
			$data[$key]['acceptor_type'] = $value['acceptor_type'] == self::ACCEPTOR_TYPE_ENTERPRISE ? $value['acceptor'] : $this->acceptorTypeMap[$value['acceptor_type']];;
			$data[$key]['possessor_contract_url'] = Yii::$app->staticResource->path2url($value['possessor_contract_path']);
			$data[$key]['investor_contract_url'] = Yii::$app->staticResource->path2url($value['investor_contract_path']);
			$data[$key]['face_amount'] = number_format($value['face_amount'], 2);
			$data[$key]['financing_amount'] = number_format($value['financing_amount'], 2);
			$data[$key]['wechselspesen'] = number_format($value['wechselspesen'], 2);
			$data[$key]['annualized_rate'] = number_format($value['annualized_rate'], 2);
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}

	/**
	 * tradeList()
	 * 交易列表
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public function tradeList($query){
		if($type = new $query->modelClass instanceof TradeRecord){
			$query->select([TradeRecord::tableName() . '.id', TradeRecord::tableName() . '.type', 'bill_id', 'amount as financing_amount', TradeRecord::tableName() . '.annualized_rate', 'bill_info']);
			$query->joinWith('bill');
			$query->asArray();
			$attributes = [
				'updated_at' => [
					'asc' => ['hph_trade_record.updated_at' => SORT_ASC],
					'desc' => ['hph_trade_record.updated_at' => SORT_DESC]
				]
			];
		}else{
			$query->select(['id', 'type', 'status', 'bill_number', 'possessor', 'investor', 'drawer', 'face_amount', 'acceptor', 'acceptor_type', 'acceptance_at', 'DATEDIFF(acceptance_at, NOW()) as left_day', 'financing_amount', 'wechselspesen', 'annualized_rate', 'investor_contract_path', 'possessor_contract_path', 'is_traded'])->asArray();
			$attributes = [];
		}
		$provider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'updated_at' => SORT_DESC
				],
				'attributes' => $attributes
			]
		]);
		$data = $provider->models;
		$typeMap = eval(TradeRecord::TYPE_MAP2);
		$acceptorTypeMap = eval(Bill::ACCEPTOR_TYPE_MAP);
		foreach($data as $key => $value){
			if($type === true){
				$data[$key]['bill_info'] = json_decode($value['bill_info'], true);
				$data[$key]['status'] = isset($typeMap[$value['type']]) ? $typeMap[$value['type']] : 'Unknown';
				$data[$key]['drawer'] = isset($value['bill']['drawer']) ? $value['bill']['drawer'] : null;
				$data[$key]['acceptance_at'] = isset($value['bill']['acceptance_at']) ? $value['bill']['acceptance_at'] : null;
				$data[$key]['acceptor_type'] = isset($value['bill']['acceptor_type']) && isset($acceptorTypeMap[$value['bill']['acceptor_type']]) ? ($value['bill']['acceptor_type'] == self::ACCEPTOR_TYPE_ENTERPRISE ? $value['bill']['acceptor'] : $acceptorTypeMap[$value['bill']['acceptor_type']]) : 'Unknown';
				$data[$key]['wechselspesen'] = isset($value['bill']['face_amount']) ? number_format($value['bill']['face_amount'] - $value['financing_amount'], 2) : '0.00';
				$data[$key]['face_amount'] = isset($value['bill']['face_amount']) ? number_format($value['bill']['face_amount'], 2) : null;
				$data[$key]['annualized_rate'] = $value['annualized_rate'] ? number_format($value['annualized_rate'], 2) : null;
				switch ($value['type']) {
					case TradeRecord::TYPE_HOLD: case TradeRecord::TYPE_REIMBURSE:
						$data[$key]['contract_url'] = Yii::$app->staticResource->path2url($data[$key]['bill_info']['possessor_contract_path']);
						break;

					case TradeRecord::TYPE_REFUND: case TradeRecord::TYPE_COLLECTION:
						$data[$key]['contract_url'] = Yii::$app->staticResource->path2url($data[$key]['bill_info']['investor_contract_path']);
						break;
					default:
						$data[$key]['contract_url'] = null;
						break;
				}
				unset($data[$key]['type'], $data[$key]['bill'], $data[$key]['bill_info'], $data[$key]['opposite_id'], $data[$key]['financing_amount'], $data[$key]['id'], $data[$key]['bill_id']);
			}else{
				$data[$key]['type'] = $this->typeMap[$value['type']];
				$data[$key]['acceptor_type'] = $value['acceptor_type'] == self::ACCEPTOR_TYPE_ENTERPRISE ? $value['acceptor'] : $this->acceptorTypeMap[$value['acceptor_type']];
				$data[$key]['face_amount'] = number_format($value['face_amount'], 2);
				$data[$key]['financing_amount'] = number_format($value['financing_amount'], 2);
				$data[$key]['wechselspesen'] = number_format($value['wechselspesen'], 2);
				$data[$key]['annualized_rate'] = number_format($value['annualized_rate'], 2);
				$data[$key]['possessor_contract_url'] = Yii::$app->staticResource->path2url($value['possessor_contract_path']);
				$data[$key]['investor_contract_url'] = Yii::$app->staticResource->path2url($value['investor_contract_path']);
			}
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}

	/**
	 * getRange()
	 * 获取范围
	 * ----------
	 * @return Array
	 * @author Verident。
	 */
	public function getRange(){
		$range = [1 => [0, 500000], 2 => [500000, 1000000], 3 => [1000000, 2000000], 4 => [2000000, 5000000], 5 => [5000000, 10000000], 6 => [10000000, 1000000000000]];
		if($this->range){
			$this->range = [$range[$this->range][0], $range[$this->range][1]];
		}
		return $this->range ?: null;
	}
}