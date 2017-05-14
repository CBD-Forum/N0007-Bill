<?php
namespace api\v1\models;

use Yii;
use common\components\ActiveDataProvider;
use common\models\Daybook as BaseDaybook;
use common\validators\TradePasswordValidator;

/**
 * Daybook Model
 * 资金流水 模型
 * -------------
 * @version 1.0.0
 * @author Verdient。
 */
class Daybook extends BaseDaybook
{
	/**
	 * @var trade_password
	 * 交易密码
	 * -------------------
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
			[['amount', 'account', 'trade_password'], 'filter', 'filter' => 'trim', 'on' => ['cashIn', 'cashOut']],
			[['signature', 'instruction_id'], 'filter', 'filter' => 'trim', 'on' => 'cashOut'],
			['amount', 'required', 'message' => 30000, 'on' => ['cashIn', 'cashOut']],
			['trade_password', 'required', 'message' => 30049, 'on' => 'cashOut'],
			['signature', 'required', 'message' => 30010, 'on' => 'cashOut'],
			['instruction_id', 'required', 'message' => 30052, 'on' => 'cashOut'],
			['trade_password', TradePasswordValidator::className(), 'message' => 30051, 'on' => 'cashOut'],
			['amount', 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/', 'message' => 30002, 'on' => ['cashIn', 'cashOut']],
			['amount', 'validateAmount', 'on' => ['cashOut']],
			[['type', 'startTime', 'endTime', 'page', 'pageSize'], 'filter', 'filter' => 'trim', 'on' => 'daybookList'],
			['page', 'default', 'value' => 1, 'on' => 'daybookList'],
			['pageSize', 'default', 'value' => 10, 'on' => 'daybookList'],
			['type', 'integer', 'message' => 30026, 'on' => 'daybookList'],
			['type', 'in', 'range' => [self::TYPE_CASH_IN, self::TYPE_CASH_OUT, self::TYPE_PAYMENT, self::TYPE_REFUND, self::TYPE_DRAWBACK, self::TYPE_COLLECTION], 'message' => 30027, 'on' => 'daybookList'],
			['page', 'integer', 'message' => 30030, 'on' => 'daybookList'],
			['pageSize', 'integer', 'message' => 30031, 'on' => 'daybookList'],
			['startTime', 'date', 'format' => 'yyyy-MM-dd', 'message' => 30032, 'on' => 'daybookList'],
			['endTime', 'date', 'format' => 'yyyy-MM-dd', 'message' => 30033, 'on' => 'daybookList'],
			['startTime', 'filter', 'filter' => self::startTimeFilter(), 'on' => 'daybookList'],
			['endTime', 'filter', 'filter' => self::endTimeFilter(), 'on' => 'daybookList'],
			['endTime', 'compare', 'compareAttribute' => 'startTime', 'operator' => '>=', 'message' => 30039, 'on' => 'daybookList'],
		];
	}

	/**
	 * addRecord(Integer $type, Integer $status, Object $instance)
	 * 添加记录
	 * -----------------------------------------------------------
	 * @param Integer $type 类型
	 * @param Integer $status 状态
	 * @param Object $instance 模型对象
	 * --------------------------------
	 * @return Boolean
	 * @author Verident。
	 */
	public static function addRecord($type, $status, $instance){
		$model = new self;
		$model->status = $status;
		$model->type = $type;
		switch($type){
			case self::TYPE_PAYMENT:
				$model->user_id = $instance->investor;
				$model->bill_id = $instance->id;
				$model->amount = $instance->financing_amount;
				$model->account = $instance->possessor;
				return $model->save(false);
				break;

			case self::TYPE_REFUND:
				$model->user_id = $instance->possessor;
				$model->amount = $instance->financing_amount;
				$model->bill_id = $instance->id;
				$model->account = $instance->investor;
				$model->save(false);
				$model->isNewRecord = true;
				$model->type = Daybook::TYPE_DRAWBACK;
				unset($model->id);
				list($model->user_id, $model->account) = [$model->account, $model->user_id];
				return $model->save(false);
				break;

			case self::TYPE_COLLECTION:
				$model->user_id = $instance->possessor;
				$model->amount = $instance->financing_amount;
				$model->bill_id = $instance->id;
				$model->account = $instance->investor;
				return $model->save(false);
				break;

			default:
				throw new Exception(30057);
				break;
		}
	}

	/**
	 * daybookList()
	 * 资产流水列表
	 * -------------
	 * @return Array
	 * @author Verdient。
	 */
	public function daybookList($query){
		$provider = new ActiveDataProvider([
			'query' => $query->select(['id', 'status', 'type', 'amount', 'created_at'])->asArray(),
			'sort' => [
				'defaultOrder' => [
					'created_at' => SORT_DESC
				]
			]
		]);
		$data = $provider->models;
		$typeMap = eval(self::TYPE_MAP);
		$statusMap = eval(self::STATUS_MAP);
		foreach($data as $key => $value){
			if(in_array($value['type'], [self::TYPE_CASH_OUT, self::TYPE_PAYMENT, self::TYPE_REFUND])){
				$value['amount'] = 0 - $value['amount'];
			}
			$data[$key]['status'] = isset($statusMap[$value['status']]) ? $statusMap[$value['status']] : 'Unknown';
			$data[$key]['type'] = isset($typeMap[$value['type']]) ? $typeMap[$value['type']] : 'Unknown';
			$data[$key]['amount'] = number_format($value['amount'], 2);
			$data[$key]['created_at'] = date('Y-m-d H:i:s', $value['created_at']);
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}
}