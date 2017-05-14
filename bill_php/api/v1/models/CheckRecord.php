<?php
namespace api\v1\models;

use Yii;
use yii\helpers\Json;
use common\components\ActiveDataProvider;
use common\filters\JsonFilter;
use common\models\CheckRecord as BaseCheckRecord;
use common\validators\BillNumberValidator;
use common\validators\EndTimeValidator;

/**
 * CheckRecord Model
 * 审核记录 模型
 * -----------------
 * @version 1.0.0
 * @author Verdient。
 */
class CheckRecord extends BaseCheckRecord
{
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
			['user_id', 'required', 'message' => 20000, 'on' => 'create'],
			['status', 'required', 'message' => 20001, 'on' => 'create'],
			['apply_at', 'required', 'message' => 20002, 'on' => 'create'],
			['face_amount', 'required', 'message' => 20003, 'on' => 'create'],
			['acceptor', 'required', 'message' => 20004, 'on' => 'create'],
			['bill_number', 'required', 'message' => 20005, 'on' => 'create'],
			['bill_type', 'required', 'message' => 20006, 'on' => 'create'],
			['acceptance_at', 'required', 'message' => 20007, 'on' => 'create'],
			['bill_info', 'required', 'message' => 20008, 'on' => 'create'],
			['user_id', 'integer', 'message' => 20009, 'on' => 'create'],
			['status', 'in', 'range' => [self::STATUS_PASSED, self::STATUS_FAILED], 'message' => 20010, 'on' => 'create'],
			['apply_at', 'datetime', 'format' => 'yyyy-MM-dd HH:mm:ss', 'message' => 20011, 'on' => 'create'],
			['face_amount', 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/', 'message' => 20012, 'on' => 'create'],
			['acceptor', 'string', 'max' => 50, 'message' => 20013, 'tooLong' => 20013, 'on' => 'create'],
			['bill_number', BillNumberValidator::className(), 'message' => 20014, 'on' => 'create'],
			['bill_type', 'in', 'range' => [Bill::TYPE_BANK_DRAFT, Bill::TYPE_COMMERCIAL_DRAFT], 'message' => 20015, 'on' => 'create'],
			['acceptance_at', 'date', 'format' => 'yyyy-MM-dd', 'message' => 20016, 'on' => 'create'],
			['bill_info', 'filter', 'filter' => JsonFilter::encode(), 'on' => 'create'],
			[['page', 'pageSize'], 'filter', 'filter' => 'trim', 'on' => 'checkRecordList'],
			['page', 'default', 'value' => 1, 'on' => 'checkRecordList'],
			['pageSize', 'default', 'value' => 10, 'on' => 'checkRecordList'],
			['page', 'integer', 'message' => 20017, 'on' => 'checkRecordList'],
			['pageSize', 'integer', 'message' => 20018, 'on' => 'checkRecordList'],
			['page', 'integer', 'min' => 1, 'message' => 20017, 'tooSmall' => 20017, 'on' => ['checkRecordList']],
			['pageSize', 'integer', 'min' => 1, 'message' => 20018, 'tooSmall' => 20018, 'on' => ['checkRecordList']],
			['query', 'string', 'message' => 20019,'on'=>['checkRecordList']],
			['bill_type', 'in', 'range' => ['银行承兑', '商业承兑'], 'message' => 20015, 'on' => ['checkRecordList']],
			['status', 'in', 'range' => [self::STATUS_FAILED, self::STATUS_PASSED],'message' => 20020,'on' => ['checkRecordList']],
			['startTime', 'default', 'value' => '1970-01-01', 'on' => ['checkRecordList']],
			['endTime', 'default', 'value' => '2038-01-01', 'on' => ['checkRecordList']],
			['startTime', 'date', 'format' => 'yyyy-MM-dd', 'message' => 20021, 'on' => ['checkRecordList']],
			['endTime', 'date', 'format' => 'yyyy-MM-dd', 'message' => 20022, 'on' => ['checkRecordList']],
			['endTime', EndTimeValidator::className(), 'message' => 20023, 'on' => ['checkRecordList']],
			['sort', 'in', 'range' => ['bill_type', '-bill_type', 'acceptance_at', '-acceptance_at', 'face_amount', '-face_amount', 'apply_at', '-apply_at', 'created_at', '-created_at'], 'message' => 20024, 'on' => ['checkRecordList']],
			['id', 'filter', 'filter' => 'trim', 'on' => 'billHistoryInfo'],
			['id', 'required', 'message' => 20025, 'on' => 'billHistoryInfo'],
			['id', 'integer', 'message' => 20026, 'on' => 'billHistoryInfo'],
			['id', 'validateId', 'on' => 'billHistoryInfo'],
		];
	}

	/**
	 * checkRecordList(Object $query)
	 * 审核记录列表
	 * ------------------------------
	 * @param Object $query 查询对象
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function checkRecordList($query) {
		$provider = new ActiveDataProvider([
			'query' => $query->asArray(),
			'sort' => [
				'defaultOrder' => [
					'created_at' => SORT_DESC
				]
			]
		]);
		$data = $provider->models;
		$bill_type_map = [
			Bill::TYPE_BANK_DRAFT => '银行承兑',
			Bill::TYPE_COMMERCIAL_DRAFT => '商业承兑',
		];
		$statusMap = eval(self::STATUS_MAP);
		foreach($data as $key => $value) {
			$bill_info = json_decode($value['bill_info']);
			$data[$key]['face_amount'] = number_format($value['face_amount'], 2);
			$data[$key]['applicant'] = User::find()->where(['id' => $bill_info->creator])->one()->name;
			$data[$key]['acceptance_at'] = $bill_info->acceptance_at;
			$data[$key]['annualized_rate_suggest'] = $bill_info->annualized_rate_suggest;
			$data[$key]['bill_type'] = $bill_type_map[$value['bill_type']];
			$data[$key]['status'] = $statusMap[$value['status']];
			$data[$key]['created_at'] = date('Y-m-d H:i:s',$value['created_at']);
			unset($data[$key]['bill_info']);
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}

	/**
	 * addCheckRecord(Integer $userId, Obejct $bill)
	 * 添加审核记录
	 * ---------------------------------------------
	 * @param Integer $userId 审核者ID
	 * @param Object $bill 票据对象
	 * -------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function addCheckRecord($userId, $bill){
		$model = new self;
		$model->setScenario('create');
		$model->user_id = $userId;
		$model->status = $bill->status == Bill::STATUS_HOLDING ? self::STATUS_PASSED : self::STATUS_FAILED;
		$model->apply_at = date('Y-m-d H:i:s', $bill->created_at);
		$model->face_amount = $bill->face_amount;
		$model->acceptor = $bill->acceptor;
		$model->bill_number = $bill->bill_number;
		$model->bill_type = $bill->type;
		$model->acceptance_at = $bill->acceptance_at;
		$model->bill_info = Json::encode($bill);
		if($model->validate()){
			return $model->save(false);
		}
		$model->handleError();
	}
}