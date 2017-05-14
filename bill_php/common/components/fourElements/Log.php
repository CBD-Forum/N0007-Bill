<?php
namespace common\components\fourElements;

use Yii;
use yii\helpers\Json;
use common\components\ActiveRecord;
use common\components\Exception;
use common\helpers\DatabaseHelper;
use common\migration\CreateTable;

/**
 * Log Model
 * 日志模型
 * ---------
 * @version 1.0.0
 * @author Verdient。
 */
class Log extends ActiveRecord
{
	/**
	 * @status const STATUS_SUCCESS
	 * 成功
	 * ----------------------------
	 * @author Verdient。
	 */
	const STATUS_SUCCESS = 1;

	/**
	 * @status const STATUS_FAILED
	 * 失败
	 * ---------------------------
	 * @author Verdient。
	 */
	const STATUS_FAILED = 2;

	/**
	 * tableName()
	 * 设置表名称
	 * -----------
	 * @inheritdoc
	 * -----------
	 * @return String
	 * @author Verdient。
	 */
	public static function tableName(){
		$tableName = Yii::$app->db->tablePrefix . 'four_elements_log_' . date('Y_m');
		if(!DatabaseHelper::hasTable($tableName)){
			CreateTable::FourElementsLog($tableName);
		}
		return '{{' . $tableName . '}}';
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
			[['status', 'name', 'persion_id', 'bank_card_number', 'mobile'], 'filter', 'filter' => 'trim'],
			[['name', 'persion_id', 'bank_card_number', 'mobile', 'send', 'result'], 'required'],
			['send', 'filter', 'filter' => self::sendFilter()],
			['result', 'filter', 'filter' => self::resultFilter()],
			['status', 'in', 'range' => [self::STATUS_SUCCESS, self::STATUS_FAILED]],
		];
	}

	/**
	 * sendFilter()
	 * send数据过滤器
	 * --------------
	 * @return Array / Boolean
	 * @author Verdient。
	 */
	public function sendFilter(){
		return function($value){
			return empty($value) ? null : Json::encode($value);
		};
	}

	/**
	 * resultFilter()
	 * result数据过滤器
	 * ----------------
	 * @return Array / Boolean
	 * @author Verdient。
	 */
	public function resultFilter(){
		return function($value){
			return empty($value) ? null : Json::encode($value);
		};
	}

	/**
	 * addLog($values)
	 * 添加日志
	 * ---------------
	 * @param Object $values 日志内容
	 * ------------------------------
	 * @author Verdient。
	 */
	public static function addLog($values){
		$model = new self;
		$model->load(['Log' => $values]);
		return $model->save();
	}
}