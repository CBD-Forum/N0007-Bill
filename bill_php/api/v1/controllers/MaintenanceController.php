<?php
namespace api\v1\controllers;

use Yii;
use yii\helpers\Json;
use common\components\upload\Image;
use common\components\upload\Office;
use common\components\upload\Upload;
use api\v1\components\ApiController;
use api\v1\components\Exception;
use api\v1\models\Bill;
use api\v1\models\MaintenanceRecord;
use api\v1\models\PublicKey;
use api\v1\models\StatisticsRealTime;
use api\v1\models\User;

/**
 * Maintenance Controller
 * 维护 控制器
 * ----------------------
 * @version 1.0.0
 * @author Verdient。
 */
class MaintenanceController extends ApiController
{
	/**
	 * actionSelfList()
	 * 自己的待维护列表
	 * ----------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionSelfList(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER], [User::TYPE_MEMBER]);
		$model = new Bill;
		$model->setScenario('waitingMaintenanceList');
		$model->load(['Bill' => Yii::$app->request->get()]);
		if($model->validate()){
			return $this->sendResponse($model->waitingMaintenanceList(Bill::find()
				->where(['status' => Bill::STATUS_HOLDING, 'possessor' => $userInfo->id])
				->andFilterWhere(['type' => $model->type])
				->andFilterWhere(['between', 'acceptance_at', $model->startTime, $model->endTime])
				->andFilterWhere(['or', ['like', 'bill_number', $model->query], ['like', 'acceptor', $model->query], ['like', 'face_amount', $model->query]])
			));
		}
		$model->handleError();
	}

	/**
	 * actionSubstituteList()
	 * 代维护列表
	 * ----------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionSubstituteList(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW]);
		$model = new Bill;
		$model->setScenario('waitingSubstituteMaintenanceList');
		$model->load(['Bill' => Yii::$app->request->get()]);
		if($model->validate()){
			$query = Bill::find()->where(['status' => Bill::STATUS_HOLDING]);
			switch($userInfo->type){
				case User::TYPE_FINANCE:
					if($userInfo->members){
						$query->andFilterWhere(['possessor' => $userInfo->members]);
					}else{
						return $this->sendResponse(['data' => [], 'count' => 0]);
					}
					break;
				case User::TYPE_REVIEW:
					$query->andFilterWhere(['possessor' => $userInfo->parent]);
					break;
			}
			$query->andFilterWhere(['between', 'acceptance_at', $model->startTime, $model->endTime]);
			$query->andFilterWhere(['or', ['like', 'bill_number', $model->query], ['like', 'acceptor', $model->query], ['like', 'face_amount', $model->query]]);
			return $this->sendResponse($model->waitingSubstituteMaintenanceList($query));
		}
		$model->handleError();
	}

	/**
	 * actionDiscount()
	 * 贴现
	 * ----------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionDiscount(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_MEMBER], [User::TYPE_MEMBER]);
		$model = new MaintenanceRecord;
		$model->setScenario('discount');
		$model->load(['MaintenanceRecord' => Yii::$app->request->post()]);
		$model->user_id = $userInfo->enabledUser;
		$model->operator = $userInfo->id;
		if($model->validate()){
			$model->user_id = $model->instance->possessor;
			if(!$from = PublicKey::find()->select('public_key')->where(['user_id' => $model->instance->possessor, 'status' => PublicKey::STATUS_REGULAR])->asArray()->one()){
				throw new Exception(21069);
			}
			$to['public_key'] = null;
			if($model->type == MaintenanceRecord::TYPE_DISCOUNT_IN_SYSTEM){
				if(!$discountApplicant = $model->findDiscountApplicant($model->discount_applicant)){
					throw new Exception(21053);
				}
				if($model->instance->possessor == $discountApplicant){
					throw new Exception(21052);
				}
				$model->instance->possessor = $discountApplicant;
				if(!$to = PublicKey::find()->select('public_key')->where(['user_id' => $model->instance->possessor, 'status' => PublicKey::STATUS_REGULAR])->asArray()->one()){
					throw new Exception(21069);
				}
			}else{
				$model->instance->status = Bill::STATUS_FINISHED;
			}
			if(!Yii::$app->blockChain->discountBill($model->signature, $model->instruction_id, $userInfo->address, $model->instance->bill_number, $model->discount_applicant, $model->annualized_rate, $model->transfer_at, $model->contract_number, $from['public_key'], $to['public_key'])){
				throw new Exception(21060);
			}
			$transaction = Yii::$app->db->beginTransaction();
			try{
				StatisticsRealTime::updateRecord(StatisticsRealTime::OPERATION_DISCOUNT, $userInfo->id, $model->instance->type, $model->instance->face_amount, $model->instance->is_traded == 1);
				$model->instance->is_traded = 1;
				$model->wechselspesen = round($model->instance->face_amount - $model->transfer_amount, 2);
				$model->bearing_days = round((strtotime($model->instance->acceptance_at) - strtotime($model->transfer_at)) / 86400);
				$model->bill_info = Json::encode($model->instance);
				$model->instruction = $model->instruction_id;
				$model->instance->save(false);
				$model->save(false);
				$transaction->commit();
				return $this->sendResponse(['message' => '票据贴现成功']);
			}catch(\Exception $e){
				$transaction->rollback();
				return $this->catchException($e);
			}
		}
		$model->handleError();
	}

	/**
	 * actionEndorsed()
	 * 背书
	 * ----------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionEndorsed(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_MEMBER], [User::TYPE_MEMBER]);
		$model = new MaintenanceRecord;
		$model->setScenario('endorsed');
		$model->load(['MaintenanceRecord' => Yii::$app->request->post()]);
		$model->user_id = $userInfo->enabledUser;
		$model->operator = $userInfo->id;
		if($model->validate()){
			$model->user_id = $model->instance->possessor;
			if(!$from = PublicKey::find()->select('public_key')->where(['user_id' => $model->instance->possessor, 'status' => PublicKey::STATUS_REGULAR])->asArray()->one()){
				throw new Exception(21069);
			}
			$to['public_key'] = '';
			if($model->type == MaintenanceRecord::TYPE_ENDORSEMENT_IN_SYSTEM){
				if(!$endorsor = $model->findEndorsor($model->endorsor)){
					throw new Exception(21054);
				}
				if($endorsor == $model->instance->possessor){
					throw new Exception(21034);
				}
				$model->instance->possessor = $endorsor;
				if(!$to = PublicKey::find()->select('public_key')->where(['user_id' => $model->instance->possessor, 'status' => PublicKey::STATUS_REGULAR])->asArray()->one()){
					throw new Exception(21069);
				}
			}else{
				$model->instance->status = Bill::STATUS_FINISHED;
			}
			if(!Yii::$app->blockChain->endorseBill($model->signature, $model->instruction_id, $userInfo->address, $model->instance->bill_number, $from['public_key'], $to['public_key'])){
				throw new Exception(21061);
			}
			$transaction = Yii::$app->db->beginTransaction();
			try{
				StatisticsRealTime::updateRecord(StatisticsRealTime::OPERATION_ENDORSE, $userInfo->id, $model->instance->type, $model->instance->face_amount, $model->instance->is_traded == 1);
				$model->instance->is_traded = 1;
				$model->bearing_days = empty($model->transfer_at) ? null : round((strtotime($model->instance->acceptance_at) - strtotime($model->transfer_at)) / 86400);
				$model->wechselspesen = empty($model->transfer_amount) ? null : round($model->instance->face_amount - $model->transfer_amount, 2);
				$model->bill_info = Json::encode($model->instance);
				$model->instruction = $model->instruction_id;
				$model->instance->save(false);
				$model->save(false);
				$transaction->commit();
				return $this->sendResponse(['message' => '票据背书成功']);
			}catch(\Exception $e){
				$transaction->rollback();
				return $this->catchException($e);
			}
		}
		$model->handleError();
	}

	/**
	 * actionDiscountContract()
	 * 上传贴现合同文件
	 * ------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionDiscountContract(){
		$model = new Upload('contract_file');
		if(!$model->file){
			throw new Exception(21071);
		}
		$image = new Image;
		$office = new Office;
		if(in_array(strtoupper(explode('/', $model->format)[1]), array_map('strtoupper', $image->enabledFormat))){
			$model = new Image('contract_file');
		}else if(in_array(strtoupper($model->getExtension()), array_map('strtoupper', $office->enabledFormat))){
			$model = new Office('contract_file');
		}else{
			throw new Exception(21070);
		}
		unset($image, $office);
		$model->setScenario('create');
		$model->type = Upload::TYPE_DISCOUNT_CONTRACT;
		if($model->validate() && $model->saveAs(Yii::getAlias('@static') . DIRECTORY_SEPARATOR . $model->path, true) &&  $model->save(false)){
			return $this->sendResponse([
				'size' => $model->size,
				'original_name' => $model->original_name,
				'type' => $model->type,
				'path' => $model->path,
				'upload_at' => date('Y-m-d H:i:s', $model->created_at),
				'url' => Yii::$app->staticResource->path2url($model->path)
			]);
		}
		$model->handleError();
	}

	/**
	 * actionEndorsedContract()
	 * 上传背书合同文件
	 * ------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionEndorsedContract(){
		$model = new Upload('contract_file');
		if(!$model->file){
			throw new Exception(21071);
		}
		$image = new Image;
		$office = new Office;
		if(in_array(strtoupper(explode('/', $model->format)[1]), array_map('strtoupper', $image->enabledFormat))){
			$model = new Image('contract_file');
		}else if(in_array(strtoupper($model->getExtension()), array_map('strtoupper', $office->enabledFormat))){
			$model = new Office('contract_file');
		}else{
			throw new Exception(21070);
		}
		unset($image, $office);
		$model->setScenario('create');
		$model->type = Upload::TYPE_ENDORSED_CONTRACT;
		if($model->validate() && $model->saveAs(Yii::getAlias('@static') . DIRECTORY_SEPARATOR . $model->path, true) &&  $model->save(false)){
			return $this->sendResponse([
				'size' => $model->size,
				'original_name' => $model->original_name,
				'type' => $model->type,
				'path' => $model->path,
				'upload_at' => date('Y-m-d H:i:s', $model->created_at),
				'url' => Yii::$app->staticResource->path2url($model->path)
			]);
		}
		$model->handleError();
	}

	/**
	 * actionHistory()
	 * 维护历史
	 * ---------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionHistory(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_MEMBER], [User::TYPE_MEMBER]);
		$model = new MaintenanceRecord;
		$model->setScenario('history');
		$model->load(['MaintenanceRecord' => Yii::$app->request->get()]);
		if($model->validate()){
			$query = MaintenanceRecord::find()
				->where(['user_id' => $userInfo->selfAndMembers])
				->andFilterWhere(['<>', MaintenanceRecord::tableName() . '.type', MaintenanceRecord::TYPE_DELETE_BY_SELF])
				->andFilterWhere(['<>', MaintenanceRecord::tableName() . '.type', MaintenanceRecord::TYPE_DELETE_BY_FINANCE])
				->andFilterWhere([MaintenanceRecord::tableName() . '.type' => $model->type, Bill::tableName() . '.type' => $model->bill_type])
				->andFilterWhere(['between', Bill::tableName(). '.acceptance_at', $model->startTime, $model->endTime]);
			return $this->sendResponse($model->maintenanceRecordList($query));
		}
		$model->handleError();
	}

	/**
	 * actionExportHistory()
	 * 导出维护历史
	 * ---------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionExportHistory(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_MEMBER], [User::TYPE_MEMBER]);
		$model = new MaintenanceRecord;
		$model->setScenario('history');
		$model->load(['MaintenanceRecord' => Yii::$app->request->get()]);
		if($model->validate()){
			$query = MaintenanceRecord::find()
				->where(['user_id' => $userInfo->selfAndMembers])
				->andFilterWhere([MaintenanceRecord::tableName() . '.type' => $model->type, Bill::tableName() . '.type' => $model->bill_type])
				->andFilterWhere(['between', Bill::tableName(). '.acceptance_at', $model->startTime, $model->endTime]);
			if(!$data = $model->exportMaintenanceRecord($query)){
				throw new Exception(21049);
			}
			$data = Yii::$app->export2Excel->excelDataFormat($data);
			$title = $this->transferTitle($data['title']);
			$content[] = ['sheet_name' => '交易纪录', 'sheet_title' => $title, 'ceils' => $data['ceils']];
			if(!$path = Yii::$app->export2Excel->export2excel($content, 'MaintenanceRecord-' . date('Y-m-d-h-i-s'))){
				throw new Exception(21059);
			}
			return $this->sendResponse(['url' => Yii::$app->staticResource->path2url($path)]);
		}
		$model->handleError();
	}

	/**
	 * transferTitle(Array $title)
	 * 转换标题
	 * ---------------------------
	 * @param Array $title 标题
	 * ------------------------
	 * @return Array
	 * @author Verdient。
	 */
	private function transferTitle(Array $title){
		$map = [
			'id' => '序号',
			'user_name' => '转让申请人',
			'bill_number' => '票据编号',
			'drawer' => '出票人全称',
			'taker' => '收票人全称',
			'contract_number' => '转让合同编号',
			'acceptor' => '承兑人全称',
			'bill_type' => '汇票类型',
			'transfer_amount' => '贴现金额（元）',
			'transfer_at' => '转让日期',
			'acceptance_at' => '到期日期',
			'bearing_days' => '计息天数',
			'discount_applicant' => '贴现人',
			'endorsor' => '被背书人',
			'annualized_rate' => '年贴现率%',
			'wechselspesen' => '贴现费（元）',
			'face_amount' => '票面金额（元）',
			'type' => '交易类型',
			'created_at' => '交易创建时间',
			'updated_at' => '最后修改时间',
			'issue_at' => '出票日期',
		];
		$result = [];
		foreach($title as $key => $value){
			$result[] = isset($map[$value]) ? $map[$value] : 'Unknown';
		}
		return $result;
	}
}