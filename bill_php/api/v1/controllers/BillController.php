<?php
namespace api\v1\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use api\v1\components\ApiController;
use api\v1\components\Exception;
use api\v1\models\Agent;
use api\v1\models\Bill;
use api\v1\models\MaintenanceRecord;
use api\v1\models\PublicKey;
use api\v1\models\StatisticsRealTime;
use api\v1\models\TradeRecord;
use api\v1\models\User;

/**
 * Bill Controller
 * 票据控制器
 * ---------------
 * @version 1.0.0
 * @author Verdient。
 */
class BillController extends ApiController
{
	/**
	 * actionCreate()
	 * 创建票据
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionCreate(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill();
		$model->setScenario('create');
		$model->load(['Bill' => Yii::$app->request->post()]);
		$model->status = Bill::STATUS_CHECK_PENDING;
		$model->creator = $model->possessor = $userInfo->id;
		if(in_array($userInfo->type, [User::TYPE_MEMBER, User::TYPE_EXTERNAL])){
			$model->agent_id = $userInfo->agentInfo->id;
		}
		if($model->validate()){
			$model->is_traded = 0;
			$model->apply_at = date('Y-m-d H:i:s');
			$model->save(false);
			return $this->sendResponse(['message' => '票据创建成功']);
		}
		$model->handleError();
	}

	/**
	 * actionUpdate()
	 * 更新票据
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionUpdate(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill();
		$model->setScenario('update');
		$model->load(['Bill' => Yii::$app->request->post()]);
		$model->possessor = $userInfo->id;
		if($model->validate()){
			foreach($model->attributes as $key => $value){
				if(isset($model->fieldStatusMap[$key]) && !($model->instance->field_status & $model->fieldStatusMap[$key])){
					$model->instance->$key = $value;
				}
			}
			$model->instance->bill_front_path = $model->bill_front_path;
			$model->instance->bill_back_path = $model->bill_back_path;
			$model->instance->status = Bill::STATUS_CHECK_PENDING;
			$model->instance->apply_at = date('Y-m-d H:i:s');
			$model->instance->save(false);
			return $this->sendResponse(['message' => '票据修改成功']);
		}
		$model->handleError();
	}

	/**
	 * actionDelete()
	 * 删除票据
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionDelete(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill();
		$model->setScenario('delete');
		$model->load(['Bill' => Yii::$app->request->post()]);
		$model->possessor = $userInfo->enabledUser;
		if($model->validate()){
			$model2 = new MaintenanceRecord;
			$model2->user_id = $model->instance->possessor;
			$model2->operator = $userInfo->id;
			$model2->bill_id = $model->instance->id;
			$model2->type = $model->instance->possessor == $userInfo->id ? MaintenanceRecord::TYPE_DELETE_BY_SELF : MaintenanceRecord::TYPE_DELETE_BY_FINANCE;
			$model2->bill_info = Json::encode($model->instance);
			if(!in_array($model->instance->status, [Bill::STATUS_CHECK_PENDING, Bill::STATUS_CHECK_FAILED])){
				if(!Yii::$app->blockChain->deleteBill($model->signature, $model->instruction_id, $userInfo->address, $model->instance->bill_number, $model->instance->possessor)){
					throw new Exception(13046);
				}
				$model2->instruction = $model->instruction_id;
			}
			$transaction = Yii::$app->db->beginTransaction();
			try{
				if(!in_array($model->instance->status, [Bill::STATUS_CHECK_PENDING, Bill::STATUS_CHECK_FAILED]) && $model->instance->possessor == $model->instance->creator){
					StatisticsRealTime::updateRecord(StatisticsRealTime::OPERATION_DELETE, $model->instance->possessor, $model->instance->type, $model->instance->face_amount, $model->is_traded);
				}
				$model->instance->status = Bill::STATUS_DELETED;
				$model->instance->save(false);
				$model2->save(false);
				$transaction->commit();
				return $this->sendResponse(['message' => '票据删除成功']);
			}catch(\Exception $e){
				$transaction->rollback();
				return $this->sendResponse(['message' => '票据删除失败']);
			}
		}
		$model->handleError();
	}

	/**
	 * actionCreatorBillList()
	 * 创建的票据列表
	 * -----------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionCreatorBillList(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('creatorBillList');
		$model->load(['Bill' => Yii::$app->request->get()]);
		if($model->validate()){
			return $this->sendResponse($model->creatorBillList(Bill::find()
				->where(['creator' => $userInfo->id])
				->andFilterWhere(['between', 'acceptance_at', $model->startTime, $model->endTime])
				->andFilterWhere(['or', ['like', 'bill_number', $model->query], ['like', 'acceptor', $model->query], ['like', 'face_amount', $model->query]])
			));
		}
		$model->handleError();
	}

	/**
	 * actionInfo()
	 * 票据信息
	 * ------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionInfo(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_ADMIN, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill();
		$model->setScenario('info');
		$model->load(['Bill' => Yii::$app->request->get()]);
		if($model->validate()){
			$info = ArrayHelper::toArray($model->instance);
			$publicKey = PublicKey::find()->select('public_key')->where(['user_id' => $info['possessor']])->asArray()->one();
			$agent = Agent::find()->where(['id' => $info['agent_id']])->asArray()->one();
			$user = User::find()->where(['id' => $info['possessor']])->asArray()->one();
			$info['bill_front_path'] = Yii::$app->staticResource->path2url($info['bill_front_path']);
			$info['bill_back_path'] = Yii::$app->staticResource->path2url($info['bill_back_path']);
			$info['type'] = $model->typeMap[$info['type']];
			$info['acceptor_type'] = $model->acceptorTypeMap[$info['acceptor_type']];
			$info['enterprise_name'] = isset($user['name']) ? $user['name'] : null;
			$info['agent_name'] = isset($agent['name']) ? $agent['name'] : null;
			$info['agent_mobile'] = isset($agent['mobile']) ? $agent['mobile'] : null;
			$info['possessor_address'] = empty($publicKey) ? null : $publicKey['public_key'];
			unset($info['agent_id'], $info['created_at'], $info['updated_at']);
			$model->checkInfoDecode($info);
			return $this->sendResponse($info);
		}
		$model->handleError();
	}

	/**
	 * actionListingList()
	 * 挂牌的票据列表
	 * -------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionListingList(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('listingList');
		$model->load(['Bill' => Yii::$app->request->get()]);
		if($model->validate()){
			$query = Bill::find()
				->where(['status' => Bill::STATUS_LISTING])
				->andFilterWhere(['type' => $model->type])
				->andFilterWhere(['>=', 'acceptance_at', date('Y-m-d')])
				->andFilterWhere(['like', 'acceptor', $model->acceptor])
				->andFilterWhere(['acceptor_type' => $model->acceptor_type])
				->andFilterWhere(['between', 'acceptance_at', $model->startTime, $model->endTime])
				->andFilterWhere(['or', ['like', 'bill_number', $model->query], ['like', 'acceptor', $model->query], ['like', 'face_amount', $model->query], ['like', 'financing_amount', $model->query]]);
			if($range = $model->getRange()){
				$query->andFilterWhere(['between', 'financing_amount', $range[0], $range[1]]);
			}
			return $this->sendResponse($model->listingList($query));
		}
		$model->handleError();
	}

	/**
	 * actionMyList()
	 * 我的票据列表
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionMyList(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('myList');
		$model->load(['Bill' => Yii::$app->request->get()]);
		if($model->validate()){
			$query = Bill::find()
				->where(['or', ['possessor' => $userInfo->id], ['investor' => $userInfo->id]])
				->andFilterWhere(['type' => $model->type])
				->andFilterWhere(['>=', 'acceptance_at', date('Y-m-d')])
				->andFilterWhere(['like', 'acceptor', $model->acceptor])
				->andFilterWhere(['acceptor_type' => $model->acceptor_type])
				->andFilterWhere(['between', 'acceptance_at', $model->startTime, $model->endTime]);
			if($range = $model->getRange()){
				$query->andFilterWhere(['between', 'financing_amount', $range[0], $range[1]]);
			}
			return $this->sendResponse($model->myList($query));
		}
		$model->handleError();
	}

	/**
	 * actionWaitingCheckList()
	 * 自己的待审核列表
	 * ------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionWaitingCheckList(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('waitingCheckList');
		$model->load(['Bill' => Yii::$app->request->get()]);
		if($model->validate()){
			$query = Bill::find()
				->where(['status' => [Bill::STATUS_CHECK_PENDING, Bill::STATUS_CHECK_FAILED], 'possessor' => $userInfo->id])
				->andFilterWhere(['type' => $model->type])
				->andFilterWhere(['between', 'acceptance_at', $model->startTime, $model->endTime])
				->andFilterWhere(['or', ['like', 'bill_number', $model->query], ['like', 'acceptor', $model->query], ['like', 'face_amount', $model->query]]);
			return $this->sendResponse($model->getSelfWaitingCheckList($query));
		}
		$model->handleError();
	}
}