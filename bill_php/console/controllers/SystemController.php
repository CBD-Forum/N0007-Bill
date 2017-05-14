<?php
namespace console\controllers;

use Yii;
use common\components\OAuth2\AccessToken;
use common\components\upload\Upload;
use common\helpers\DatabaseHelper;
use common\helpers\FileHelper;
use common\models\Agent;
use common\models\AuthenticationRecord;
use common\models\Bill;
use common\models\CheckRecord;
use common\models\DbLog;
use common\models\Daybook;
use common\models\Enterprise;
use common\models\MaintenanceRecord;
use common\models\StatisticsRealTime;
use common\models\TradeRecord;
use console\components\ConsoleController;

/**
 * System Controller
 * 系统 控制器
 * ----------------
 * @version 1.0.0
 * @author Verdient。
 */
class SystemController extends ConsoleController
{
	/**
	 * actionCopyToDb
	 * 复制日志到数据库
	 * ----------------
	 * @return Integer
	 * @author Verdient。
	 */
	public function actionCopyToDb(){
		if(!$this->confirm('Copy logs from local to the Database, Confirm?', false)){
			exit(1);
		}
		$path = Yii::$app->log->targets['db-execute']->logFile;
		if(file_exists($path)){
			$syncAt = DbLog::find()->select('sync_at')->limit(1)->orderBy(['sync_at' => SORT_DESC])->one();
			$syncAt  = $syncAt ? $syncAt['sync_at'] : 0;
			$rows = [];
			$file = fopen($path, "r");
			while(!feof($file)){
				if($row = fgets($file)){
					$row = explode(' | ' , $row);
					if(count($row) > 4 && strstr($row[3], 'end') && $syncAt < $row[0]){
						$rows[] = [$row[5], $row[1], $row[0]];
					}
				}
			}
			fclose($file);
			Yii::$app->log->targets['db-execute']->enabled = false;
			Yii::$app->db->createCommand()->batchInsert(DbLog::tableName(), ['sql', 'log_at', 'sync_at'], $rows)->execute();
			$this->stdout("\nCopy success!\n");
			exit(0);
		}
		$this->stderr("\nLog file not exist!\n");
		exit(1);
	}

	/**
	 * actionReset()
	 * 系统重置
	 * ----------------
	 * @return Integer
	 * @author Verdient。
	 */
	public function actionReset(){
		if(!$this->confirm('This operation will erase all data, Confirm?', false)){
			exit(1);
		}
		FileHelper::removeFolder('@upload', false, false);
		Yii::$app->log->targets['db-execute']->enabled = false;
		DatabaseHelper::truncateTable(AccessToken::tableName());
		DatabaseHelper::truncateTable(Upload::tableName());
		DatabaseHelper::truncateTable(Agent::tableName());
		DatabaseHelper::truncateTable(AuthenticationRecord::tableName());
		DatabaseHelper::truncateTable(Bill::tableName());
		DatabaseHelper::truncateTable(CheckRecord::tableName());
		DatabaseHelper::truncateTable(Enterprise::tableName());
		DatabaseHelper::truncateTable(Daybook::tableName());
		DatabaseHelper::truncateTable(StatisticsRealTime::tableName());
		DatabaseHelper::truncateTable(MaintenanceRecord::tableName());
		DatabaseHelper::truncateTable(TradeRecord::tableName());
		$this->stdout("\nAll data has been cleared!\n");
		exit(0);
	}
}