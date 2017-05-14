<?php
namespace api\v1\models;

use Yii;
use common\components\ActiveDataProvider;
use common\filters\DateFilter;
use common\filters\JsonFilter;
use common\models\TradeRecord as BaseTradeRecord;
use common\validators\EndTimeValidator;

/**
 * TradeRecord Model
 * 交易记录模型
 * -----------------
 * @version 1.0.0
 * @author Verdient。
 */
class TradeRecord extends BaseTradeRecord
{
	/**
	 * rules()
	 * 数据验证规则
	 * ------------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public function rules(){
		return [
			[['type', 'instruction'], 'filter', 'filter' => 'trim', 'skipOnEmpty' => true, 'on' => 'create'],
			[['type', 'bill_type', 'page', 'pageSize', 'startTime', 'endTime'], 'filter', 'filter' => 'trim', 'on' => 'tradeHistory'],
			['type', 'required', 'message' => 24001, 'on' => 'create'],
			['bill_info', 'required', 'message' => 24021, 'on' => 'create'],
			['bill_info', 'filter', 'filter' => JsonFilter::encode(), 'on' => 'create'],
			['type', 'validateType', 'on' => 'create'],
			['user_id', 'required', 'message' => 24000, 'on' => 'create'],
			['bill_id', 'required', 'message' => 24002, 'on' => 'create'],
			['bill_type', 'required', 'message' => 24022, 'on' => 'create'],
			['user_id', 'integer', 'message' => 24006, 'on' => 'create'],
			['type', 'integer', 'message' => 24007, 'on' => 'create'],
			['bill_id', 'integer', 'message' => 24008, 'on' => 'create'],
			['opposite_id', 'integer', 'message' => 24009, 'on' => 'create'],
			['instruction', 'string', 'message' => 24025, 'on' => 'create'],
			['hash', 'string', 'max' => 255, 'message' => 24018, 'tooLong' => 24018, 'on' => 'create'],
			['amount', 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/', 'message' => 24010, 'on' => 'create'],
			['annualized_rate', 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,20})?$/', 'message' => 24011, 'on' => 'create'],
			['bill_type', 'integer', 'message' => 24023, 'on' => ['create', 'tradeHistory']],
			['bill_type', 'in', 'range' => [Bill::TYPE_All, Bill::TYPE_BANK_DRAFT, Bill::TYPE_COMMERCIAL_DRAFT], 'message' => 24024, 'on' => ['create', 'tradeHistory']],
			['type', 'in', 'range' => [self::TYPE_TYPE_IN, self::TYPE_LISTING, self::TYPE_DELIST, self::TYPE_ASSIGNMENT, self::TYPE_REVOKE, self::TYPE_AGREE_CANCEL_ORDER], 'message' => 24017, 'on' => 'tradeHistory'],
			['startTime', 'default', 'value' => '1970-01-01', 'on' => 'tradeHistory'],
			['endTime', 'default', 'value' => '2038-01-19', 'on' => 'tradeHistory'],
			['startTime', 'date', 'format' => 'yyyy-MM-dd', 'message' => 24014, 'on' => 'tradeHistory'],
			['endTime', 'date', 'format' => 'yyyy-MM-dd', 'message' => 24015, 'on' => 'tradeHistory'],
			['endTime', EndTimeValidator::className(), 'message' => 24016, 'on' => 'tradeHistory'],
			['startTime', 'filter', 'filter' => DateFilter::timestamp(), 'on' => 'tradeHistory'],
			['endTime', 'filter', 'filter' => DateFilter::timestamp(true), 'on' => 'tradeHistory'],
			['page', 'default', 'value' => 1, 'on' => 'tradeHistory'],
			['pageSize', 'default', 'value' => 10, 'on' => 'tradeHistory'],
			['page', 'integer', 'message' => 24012, 'on' => 'tradeHistory'],
			['pageSize', 'integer', 'message' => 24013, 'on' => 'tradeHistory'],
		];
	}

	/**
	 * addTradeRecord(Integer $type, Object $billInfo[, String $instruction = null])
	 * 添加交易记录
	 * -----------------------------------------------------------------------------
	 * @param Integer $type 类型
	 * @param Object $billInfo 票据信息
	 * @param String $instruction 任务ID
	 * ---------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function addTradeRecord($type, $billInfo, $instruction = null){
		$model = new self;
		$model->setScenario('create');
		$model->load(['TradeRecord' => [
			'type' => $type,
			'bill_info' => $billInfo,
			'instruction' => $instruction
		]]);
		if($model->validate() && $model->save(false)){
			if($model->type == self::TYPE_AGREE_CANCEL_ORDER){
				$model->isNewRecord = true;
				$model->type = self::TYPE_CANCEL_ORDER;
				$model->instruction = null;
				unset($model->id);
				list($model->user_id, $model->opposite_id) = [$model->opposite_id, $model->user_id];
				$model->save(false);
			}
			return true;
		}
		$model->handleError();
	}

	/**
	 * tradeHistory()
	 * 交易历史
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	public function tradeHistory($query){
		$provider = new ActiveDataProvider([
			'query' => $query
				->select(['id', 'type', 'amount', 'annualized_rate', 'created_at', 'opposite_id', 'bill_info', 'instruction'])
				->with('opposite')
				->asArray(),
			'sort' => [
				'defaultOrder' => [
					'created_at' => SORT_DESC
				]
			]
		]);
		$data = $provider->models;
		$acceptorTypeMap = eval(Bill::ACCEPTOR_TYPE_MAP);
		foreach($data as $key => $value){
			$value['bill_info'] = json_decode($value['bill_info'], true);
			$data[$key]['type'] = isset($this->typeMap[$value['type']]) ? $this->typeMap[$value['type']] : 'Unknown';
			$data[$key]['annualized_rate'] = number_format($value['annualized_rate'], 2);
			$data[$key]['wechselspesen'] = number_format($value['bill_info']['face_amount'] - $value['amount'], 2);
			$data[$key]['bill_amount'] = number_format($value['bill_info']['face_amount'], 2);
			$data[$key]['acceptance_at'] = $value['bill_info']['acceptance_at'];
			$data[$key]['opposite_name'] = isset($value['opposite']) ? $value['opposite']['name'] : null;
			$data[$key]['created_at'] = date('Y-m-d H:i:s', $value['created_at']);
			$data[$key]['acceptor'] = $value['bill_info']['acceptor_type'] == Bill::ACCEPTOR_TYPE_ENTERPRISE ? $value['bill_info']['acceptor'] : $acceptorTypeMap[$value['bill_info']['acceptor_type']];
			unset($data[$key]['bill_info'], $data[$key]['opposite'], $data[$key]['opposite_id'], $data[$key]['amount']);
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}
}
