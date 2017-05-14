<?php
namespace common\models;

use Yii;
use common\components\ActiveRecord;
use common\helpers\DatabaseHelper;
use common\migration\CreateTable;

/**
 * DbLog Model
 * 数据库日志 模型
 * ---------------
 * @version 1.0.0
 * @author Verdient。
 */
class DbLog extends ActiveRecord
{
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
		$tableName = Yii::$app->db->tablePrefix . 'db_log_' . date('Y_m');
		if(!DatabaseHelper::hasTable($tableName)){
			CreateTable::DbLog($tableName);
		}
		return '{{' . $tableName . '}}';
	}
}