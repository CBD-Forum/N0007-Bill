<?php
namespace common\components\haipiaohui;

use Yii;
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
	const STATUS_FAILED = 0;

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
		$tableName = Yii::$app->db->tablePrefix . 'haipiaohui_log_' . date('Y_m');
		if(!DatabaseHelper::hasTable($tableName)){
			CreateTable::HaipiaohuiLog($tableName);
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
			[['status', 'url', 'route', 'posts', 'datas'], 'safe', 'on' => 'create']
		];
	}

	/**
	 * addLog(Array $data)
	 * 添加日志
	 * -------------------
	 * @param Array $data 日志数据
	 * ---------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function addLog(Array $data){
		$model = new self;
		$model->setScenario('create');
		$model->load(['Log' => $data]);
		if($model->validate()){
			return $model->save(false);
		}
		$errors = $model->getFirstErrors();
		throw new Exception(reset($errors));
	}
}