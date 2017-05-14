<?php
namespace api\v1\models;

use Yii;
use common\components\ActiveDataProvider;
use common\components\upload\Upload;
use common\models\MaintenanceRecord as BaseMaintenanceRecord;
use common\validators\BillNumberValidator;
use common\validators\EndTimeValidator;

/**
 * MaintenanceRecord Model
 * 维护记录模型
 * -----------------------
 * @version 1.0.0
 * @author Verdient。
 */
class MaintenanceRecord extends BaseMaintenanceRecord
{
	/**
	 * @var public $bill_type
	 * 票据类型
	 * ----------------------
	 * @author yuan
	 */
	public $bill_type;

	/**
	 * @var public $address
	 * 公钥地址
	 * --------------------
	 * @author Verdient。
	 */
	public $address;

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

			//history
			[['page', 'pageSize'], 'filter', 'filter' => 'trim', 'on' => 'history'],
			['page', 'default', 'value' => 1, 'on' => 'history'],
			['pageSize', 'default', 'value' => 10, 'on' => 'history'],
			['page', 'integer', 'message' => 21000, 'on' => 'history'],
			['pageSize', 'integer', 'message' => 21001, 'on' => 'history'],
			['page', 'integer', 'min' => 1, 'message' => 21036, 'tooSmall' => 21036, 'on' => ['history']],
			['pageSize', 'integer', 'min' => 1, 'message' => 21037, 'tooSmall' => 21037, 'on' => ['history']],
			['bill_type', 'in', 'range' => [Bill::TYPE_BANK_DRAFT, Bill::TYPE_COMMERCIAL_DRAFT, [Bill::TYPE_BANK_DRAFT, Bill::TYPE_COMMERCIAL_DRAFT]], 'message' => 21038, 'on' => ['history']],
			['type', 'in', 'range' => [self::TYPE_DISCOUNT_IN_SYSTEM, self::TYPE_DISCOUNT_OUT_SYSTEM, self::TYPE_ENDORSEMENT_IN_SYSTEM, self::TYPE_ENDORSEMENT_OUT_SYSTEM, [self::TYPE_DISCOUNT_IN_SYSTEM, self::TYPE_DISCOUNT_OUT_SYSTEM], [self::TYPE_ENDORSEMENT_IN_SYSTEM, self::TYPE_ENDORSEMENT_OUT_SYSTEM]], 'message' => 21039, 'on' => ['history']],
			['startTime', 'default', 'value' => '1970-01-01', 'on' => ['history']],
			['endTime', 'default', 'value' => '2038-01-01', 'on' => ['history']],
			['startTime', 'date', 'format' => 'yyyy-MM-dd', 'message' => 21040, 'on' => ['history']],
			['endTime', 'date', 'format' => 'yyyy-MM-dd', 'message' => 21041, 'on' => ['history']],
			['endTime', EndTimeValidator::className(), 'message' => 21042, 'on' => ['history']],
			['sort', 'in', 'range' => ['bill_type', '-bill_type', 'bearing_days', '-bearing_days', 'transfer_amount', '-transfer_amount', 'annualized_rate', '-annualized_rate', 'issue_at', '-issue_at', 'acceptance_at', '-acceptance_at', 'face_amount', '-face_amount', 'transfer_at', '-transfer_at'], 'message' => 21043, 'on' => ['history']],

			['id', 'required', 'message' => 21044, 'on' => ['info']],
			['id', 'integer', 'message' => 21045, 'on' => ['info']],
			['id', 'validateId', 'on' => ['info']],

			['signature', 'required', 'message' => 21062, 'on' => ['discount', 'endorsed']],
			['instruction_id', 'required', 'message' => 21063, 'on' => ['discount', 'endorsed']],
			['user_id', 'required', 'message' => 21030, 'on' => ['discount', 'endorsed']],
			['bill_id', 'required', 'message' => 21023, 'on' => ['discount', 'endorsed']],
			['type', 'required', 'message' => 21050, 'on' => ['discount', 'endorsed']],
			['type', 'in', 'range' => [self::TYPE_DISCOUNT_IN_SYSTEM, self::TYPE_DISCOUNT_OUT_SYSTEM], 'message' => 21051, 'on' => ['discount']],
			['type', 'in', 'range' => [self::TYPE_ENDORSEMENT_IN_SYSTEM, self::TYPE_ENDORSEMENT_OUT_SYSTEM], 'message' => 21051, 'on' => ['endorsed']],
			['discount_applicant', 'required', 'message' => 21022, 'on' => ['discount']],
			['endorsor', 'required', 'message' => 21032, 'on' => ['endorsed']],
			['annualized_rate', 'required', 'message' => 21011, 'on' => ['discount']],
			['transfer_amount', 'required', 'message' => 21012, 'on' => ['discount']],
			['transfer_at', 'required', 'message' => 21010, 'on' => ['discount']],
			['contract_number', 'required', 'message' => 21013, 'on' => ['discount']],
			// ['address', 'required', 'message' => 21065, 'when' => function($model){return $model->type == self::TYPE_ENDORSEMENT_IN_SYSTEM;}, 'on' => ['endorsed']],
			['discount_applicant', 'string', 'max' => 50, 'message' => 21024, 'tooLong' => 21024, 'on' => ['discount']],
			['endorsor', 'string', 'max' => 50, 'message' => 21033, 'tooLong' => 21033, 'on' => ['endorsed']],
			['annualized_rate', 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/', 'message' => 21025, 'on' => ['discount', 'endorsed']],
			['annualized_rate', 'number', 'min' => 0, 'max' => 99, 'message' => 21025, 'tooSmall' => 21025, 'tooBig' => 21025, 'on' => ['discount', 'endorsed']],
			['transfer_amount', 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/', 'message' => 21026, 'on' => ['discount', 'endorsed']],
			['transfer_at', 'date', 'format' => 'yyyy-MM-dd', 'message' => 21021, 'on' => ['discount', 'endorsed']],
			['contract_number', 'string', 'max' => 50, 'message' => 21027, 'tooLong' => 21027, 'on' => ['discount', 'endorsed']],
			['bill_id', 'validateBillId', 'on' => ['discount', 'endorsed']],
			['address', 'exist', 'targetClass' => PublicKey::className(), 'targetAttribute' => 'public_key', 'filter' => ['status' => PublicKey::STATUS_REGULAR], 'message' => 21066, 'when' => function($model){return $model->type == self::TYPE_ENDORSEMENT_IN_SYSTEM;}, 'on' => 'endorsed'],
			['transfer_at', 'validateTransferAt', 'on' => ['discount', 'endorsed']],
			['transfer_amount', 'validateTransferAmount', 'on' => ['discount', 'endorsed']],
			['contract_file_path', 'exist', 'targetClass' => Upload::className(), 'targetAttribute' => 'path', 'filter' => ['type' => Upload::TYPE_DISCOUNT_CONTRACT], 'message' => 21028, 'on' => 'discount'],
			['contract_file_path', 'exist', 'targetClass' => Upload::className(), 'targetAttribute' => 'path', 'filter' => ['type' => Upload::TYPE_ENDORSED_CONTRACT], 'message' => 21028, 'on' => 'endorsed']
		];
	}

	/**
	 * findDiscountApplicant(String $name)
	 * 查找贴现人
	 * -----------------------------------
	 * @param String $name 贴现人名称
	 * ------------------------------
	 * @return Integer / Boolean
	 * @author Verdient。
	 */
	public function findDiscountApplicant($name){
		$result = User::find()->where(['name' => $name, 'userTag' => User::USERTAG_FINANCE])->select(['id'])->asArray()->One();
		return $result ? $result['id'] : false;
	}

	/**
	 * findEndorsor(String $name)
	 * 查找背书人
	 * --------------------------
	 * @param String $name 背书人名称
	 * ------------------------------
	 * @return Integer / Boolean
	 * @author Verdient。
	 */
	public function findEndorsor($name){
		$result = User::find()->where(['name' => $name])->select(['id'])->asArray()->One();
		return $result ? $result['id'] : false;
	}

	/**
	 * endorse(String $from[, String $to = null])
	 * 背书
	 * ------------------------------------------
	 * @param String $from 拥有者公钥
	 * @param String $to 对方公钥
	 * ------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function endorse($from, $to = null){
		return Yii::$app->blockChain->endorseBill(
			$this->signature,
			$this->instruction_id,
			$this->operator,
			$this->instance->bill_number,
			$from,
			$to
		);
	}

	/**
	 * maintenanceRecordList(Object $query)
	 * 维护记录列表
	 * ------------------------------------
	 * @param Object $query 查询对象
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function maintenanceRecordList($query){
		$provider = new ActiveDataProvider([
			'query' => $query
				->select([self::tableName() . '.id', 'user_id', 'bill_id', self::tableName() . '.type', 'discount_applicant', 'endorsor', self::tableName() . '.annualized_rate', 'transfer_at', 'transfer_amount', 'contract_number', 'contract_file_path', self::tableName() . '.wechselspesen', 'bearing_days', 'bill_info', self::tableName() . '.created_at', self::tableName() . '.updated_at'])
				->joinWith('bill')
				->with('user')
				->asArray(),
			'sort' => [
				'defaultOrder' => [
					'created_at' => SORT_DESC
				],
				'attributes' => [
					'bill_type' => [
						'asc' => [Bill::tableName() . '.type' => SORT_ASC],
						'desc' => [Bill::tableName() . '.type' => SORT_DESC]
					],
					'bearing_days' => [
						'asc' => [self::tableName() . '.bearing_days' => SORT_ASC],
						'desc' => [self::tableName() . '.bearing_days' => SORT_DESC]
					],
					'transfer_amount' => [
						'asc' => [self::tableName() . '.transfer_amount' => SORT_ASC],
						'desc' => [self::tableName() . '.transfer_amount' => SORT_DESC]
					],
					'annualized_rate' => [
						'asc' => [self::tableName() . '.annualized_rate' => SORT_ASC],
						'desc' => [self::tableName() . '.annualized_rate' => SORT_DESC]
					],
					'issue_at' => [
						'asc' => [Bill::tableName() . '.issue_at' => SORT_ASC],
						'desc' => [Bill::tableName() . '.issue_at' => SORT_DESC]
					],
					'acceptance_at' => [
						'asc' => [Bill::tableName() . '.acceptance_at' => SORT_ASC],
						'desc' => [Bill::tableName() . '.acceptance_at' => SORT_DESC]
					],
					'face_amount' => [
						'asc' => [Bill::tableName() . '.face_amount' => SORT_ASC],
						'desc' => [Bill::tableName() . '.face_amount' => SORT_DESC]
					],
					'transfer_at' => [
						'asc' => [self::tableName() . '.transfer_at' => SORT_ASC],
						'desc' => [self::tableName() . '.transfer_at' => SORT_DESC]
					],
					'created_at' => [
						'asc' => [self::tableName() . '.created_at' => SORT_ASC],
						'desc' => [self::tableName() . '.created_at' => SORT_DESC]
					]
				]
			]
		]);
		$data = $provider->models;
		foreach($data as $key => $value){
			$billTypeMap = eval(Bill::TYPE_MAP);
			$data[$key]['bill_type'] = $billTypeMap[$value['bill']['type']];
			$data[$key]['type'] = $this->typeMap[$value['type']];
			$data[$key]['issue_at'] = $value['bill']['issue_at'];
			$data[$key]['acceptance_at'] = $value['bill']['acceptance_at'];
			$data[$key]['bill_number'] = $value['bill']['bill_number'];
			$data[$key]['face_amount'] = number_format($value['bill']['face_amount'], 2);
			$data[$key]['drawer'] = $value['bill']['drawer'];
			$data[$key]['taker'] = $value['bill']['taker'];
			$data[$key]['acceptor'] = $value['bill']['acceptor'];
			$data[$key]['bill_front_url'] = Yii::$app->staticResource->path2url($value['bill']['bill_front_path']);
			$data[$key]['bill_back_url'] = Yii::$app->staticResource->path2url($value['bill']['bill_back_path']);
			$data[$key]['contract_file_url'] = Yii::$app->staticResource->path2url($value['contract_file_path']);
			$data[$key]['user_name'] = $value['user']['name'];
			$data[$key]['wechselspesen'] = number_format($value['wechselspesen'], 2);
			$data[$key]['created_at'] = date('Y-m-d H:i:s', $value['created_at']);
			$data[$key]['updated_at'] = date('Y-m-d H:i:s', $value['updated_at']);
			unset($data[$key]['bill_info'], $data[$key]['bill'], $data[$key]['user_id'], $data[$key]['bill_id'], $data[$key]['user']);
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}

	/**
	 * exportMaintenanceRecord(Object $query)
	 * 导出维护记录列表
	 * --------------------------------------
	 * @param Object $query 查询对象
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function exportMaintenanceRecord($query){
		$provider = new ActiveDataProvider([
			'query' => $query
				->select([self::tableName() . '.id', 'user_id', 'bill_id', self::tableName() . '.type', 'discount_applicant', 'endorsor', self::tableName() . '.annualized_rate', 'transfer_at', 'transfer_amount', 'contract_number', self::tableName() . '.wechselspesen', 'bearing_days', 'bill_info', self::tableName() . '.created_at', self::tableName() . '.updated_at'])
				->joinWith('bill', true, 'INNER JOIN')
				->with('user')
				->asArray(),
			'sort' => [
				'defaultOrder' => [
					'created_at' => SORT_ASC
				],
				'attributes' => [
					'bill_type' => [
						'asc' => [Bill::tableName() . '.type' => SORT_ASC],
						'desc' => [Bill::tableName() . '.type' => SORT_DESC]
					],
					'bearing_days' => [
						'asc' => [self::tableName() . '.bearing_days' => SORT_ASC],
						'desc' => [self::tableName() . '.bearing_days' => SORT_DESC]
					],
					'wechselspesen' => [
						'asc' => [self::tableName() . '.wechselspesen' => SORT_ASC],
						'desc' => [self::tableName() . '.wechselspesen' => SORT_DESC]
					],
					'annualized_rate' => [
						'asc' => [self::tableName() . '.annualized_rate' => SORT_ASC],
						'desc' => [self::tableName() . '.annualized_rate' => SORT_DESC]
					],
					'issue_at' => [
						'asc' => [Bill::tableName() . '.issue_at' => SORT_ASC],
						'desc' => [Bill::tableName() . '.issue_at' => SORT_DESC]
					],
					'acceptance_at' => [
						'asc' => [Bill::tableName() . '.acceptance_at' => SORT_ASC],
						'desc' => [Bill::tableName() . '.acceptance_at' => SORT_DESC]
					],
					'face_amount' => [
						'asc' => [Bill::tableName() . '.face_amount' => SORT_ASC],
						'desc' => [Bill::tableName() . '.face_amount' => SORT_DESC]
					],
					'transfer_at' => [
						'asc' => [self::tableName() . '.transfer_at' => SORT_ASC],
						'desc' => [self::tableName() . '.transfer_at' => SORT_DESC]
					],
					'created_at' => [
						'asc' => [self::tableName() . '.created_at' => SORT_ASC],
						'desc' => [self::tableName() . '.created_at' => SORT_DESC]
					]
				]
			]
		]);
		$billTypeMap = eval(Bill::TYPE_MAP);
		foreach($provider->models as $key => $value){
			$data[$key]['id'] = $value['id'];
			$data[$key]['user_name'] = $value['user']['name'];
			$data[$key]['bill_number'] = $value['bill']['bill_number'];
			$data[$key]['drawer'] = $value['bill']['drawer'];
			$data[$key]['taker'] = $value['bill']['taker'];
			$data[$key]['contract_number'] = $value['contract_number'];
			$data[$key]['acceptor'] = $value['bill']['acceptor'];
			$data[$key]['bill_type'] = $billTypeMap[$value['bill']['type']];
			$data[$key]['face_amount'] = number_format($value['bill']['face_amount'], 2);
			$data[$key]['issue_at'] = $value['bill']['issue_at'];
			$data[$key]['acceptance_at'] = $value['bill']['acceptance_at'];
			$data[$key]['transfer_at'] = $value['transfer_at'];
			$data[$key]['bearing_days'] = $value['bearing_days'];
			$data[$key]['discount_applicant'] = $value['discount_applicant'];
			$data[$key]['endorsor'] = $value['endorsor'];
			$data[$key]['annualized_rate'] = $value['annualized_rate'];
			$data[$key]['wechselspesen'] = number_format($value['wechselspesen'], 2);
		}
		return $data;
	}
}