<?php
namespace api\v1\controllers;

use Yii;
use common\components\upload\Image;
use api\v1\components\ApiController;
use api\v1\components\Exception;
use api\v1\models\Bill;
use api\v1\models\Daybook;
use api\v1\models\StatisticsRealTime;
use api\v1\models\TradeRecord;
use api\v1\models\User;

/**
 * Trade Controller
 * 交易 控制器
 * ----------------
 * @version 1.0.0
 * @author Verdient。
 */
class TradeController extends ApiController
{
	/**
	 * actionListing()
	 * 挂牌
	 * ---------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionListing(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL], true);
		$model = new Bill;
		$model->setScenario('listing');
		$model->possessor = $userInfo->id;
		$model->load(['Bill' => Yii::$app->request->post()]);
		if($model->validate()){
			if(!Yii::$app->blockChain->listingBill($model->signature, $model->instruction_id, $userInfo->address, $model->instance->bill_number, $model->financing_amount)){
				throw new Exception(13069);
			}
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$model->instance->status = Bill::STATUS_LISTING;
				$model->instance->financing_amount = $model->financing_amount;
				$model->instance->wechselspesen = $model->wechselspesen;
				$model->instance->annualized_rate = $model->annualized_rate;
				$model->instance->save(false);
				TradeRecord::addTradeRecord(TradeRecord::TYPE_LISTING, $model->instance, $model->instruction_id);
				$transaction->commit();
				return $this->sendResponse(['message' => '票据挂牌成功']);
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException($e);
			}
		}
		$model->handleError();
	}

	/**
	 * actionDelist()
	 * 摘牌
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionDelist(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL], true);
		$model = new Bill;
		$model->setScenario('delist');
		$model->load(['Bill' => Yii::$app->request->post()]);
		$model->investor = $userInfo->id;
		if($model->validate()){
			if(!$result = Yii::$app->blockChain->getBalance($userInfo->address)){
				throw new Exception(13078);
			}
			if($result['available'] < (float)$model->instance->financing_amount){
				throw new Exception(13079);
			}
			if(!Yii::$app->blockChain->operateNote($model->signature, $model->instruction_id, $userInfo->address, $model->instance->bill_number, TradeRecord::TYPE_DELIST)){
				throw new Exception(13070);
			}
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$model->instance->status = Bill::STATUS_PAYMENT_FAILED;
				$model->instance->investor = $model->investor;
				if(Yii::$app->bank->payFreeze($userInfo->enterprise_id, $model->instance->possessorEnterpriseId, $model->instance->financing_amount, $model->trade_password)){
					$model->instance->status = Bill::STATUS_WAITING_POSSESSOR_ASSIGNMENT;
					Daybook::addRecord(Daybook::TYPE_PAYMENT, Daybook::STATUS_REGULAR, $model->instance);
					StatisticsRealTime::updateRecord(StatisticsRealTime::OPERATION_PAYMENT, $model->instance->investor, $model->instance->type, $model->instance->financing_amount);
					TradeRecord::addTradeRecord(TradeRecord::TYPE_PAYMENT, $model->instance);
				}
				$model->instance->save(false);
				TradeRecord::addTradeRecord(TradeRecord::TYPE_DELIST, $model->instance, $model->instruction_id);
				$transaction->commit();
				return $this->sendResponse(['message' => '票据摘牌成功']);
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException($e);
			}
		}
		$model->handleError();
	}

	/**
	 * actionPossessorAssignment()
	 * 拥有人确认转移票据
	 * ---------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionPossessorAssignment(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('possessorAssignment');
		$model->possessor = $userInfo->id;
		$model->load(['Bill' => Yii::$app->request->post()]);
		if($model->validate()){
			if(!Yii::$app->blockChain->operateNote($model->signature, $model->instruction_id, $userInfo->address, $model->instance->bill_number, TradeRecord::TYPE_ASSIGNMENT)){
				throw new Exception(13071);
			}
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$model->instance->status = Bill::STATUS_WAITING_INVESTOR_CONFIRM;
				$model->instance->save(false);
				TradeRecord::addTradeRecord(TradeRecord::TYPE_ASSIGNMENT, $model->instance, $model->instruction_id);
				$transaction->commit();
				return $this->sendResponse(['message' => '转让确认成功']);
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException($e);
			}
		}
		$model->handleError();
	}

	/**
	 * actionInvestorConfirm()
	 * 投资人确认收到票据
	 * -----------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionInvestorConfirm(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('investorConfirm');
		$model->investor = $userInfo->id;
		$model->load(['Bill' => Yii::$app->request->post()]);
		if($model->validate()){
			if(!$to = User::find()->select('enterprise_id')->where(['id' => $model->instance->possessor])->asArray()->one()){
				throw new Exception(13080);
			}
			if(!Yii::$app->bank->getUnfreezeCode($to['enterprise_id'], $model->instance->id, $model->instance->financing_amount)){
				throw new Exception(13081);
			}
			if(!Yii::$app->blockChain->operateNote($model->signature, $model->instruction_id, $userInfo->address, $model->instance->bill_number, TradeRecord::TYPE_HOLD)){
				throw new Exception(13072);
			}
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$model->instance->status = Bill::STATUS_COLLECTION_FAILED;
				$model->instance->save(false);
				TradeRecord::addTradeRecord(TradeRecord::TYPE_HOLD, $model->instance, $model->instruction_id);
				$transaction->commit();
				return $this->sendResponse(['message' => '确认收到票据成功']);
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException($e);
			}
		}
		$model->handleError();
	}

	/**
	 * actionPossessorRevoke()
	 * 创建拥有人撤销挂单
	 * -----------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionPossessorRevoke(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('possessorRevoke');
		$model->possessor = $userInfo->id;
		$model->load(['Bill' => Yii::$app->request->post()]);
		if($model->validate()){
			if(!Yii::$app->blockChain->operateNote($model->signature, $model->instruction_id, $userInfo->address, $model->instance->bill_number, TradeRecord::TYPE_REVOKE)){
				throw new Exception(13073);
			}
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$model->instance->status = Bill::STATUS_HOLDING;
				$model->instance->financing_amount = null;
				$model->instance->wechselspesen = null;
				$model->instance->annualized_rate = null;
				$model->instance->save(false);
				TradeRecord::addTradeRecord(TradeRecord::TYPE_REVOKE, $model->instance, $model->instruction_id);
				$transaction->commit();
				return $this->sendResponse(['message' => '拥有者撤单成功']);
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException($e);
			}
		}
		$model->handleError();
	}

	/**
	 * actionInvestorRevoke()
	 * 投资人撤销订单
	 * ----------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionInvestorRevoke(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('investorRevoke');
		$model->investor = $userInfo->id;
		$model->load(['Bill' => Yii::$app->request->post()]);
		if($model->validate()){
			if(!Yii::$app->blockChain->operateNote($model->signature, $model->instruction_id, $userInfo->address, $model->instance->bill_number, TradeRecord::TYPE_APPLY_CANCEL_ORDER)){
				throw new Exception(13074);
			}
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$model->instance->status = $model->instance->status == Bill::STATUS_WAITING_POSSESSOR_ASSIGNMENT ? Bill::STATUS_WAITING_POSSESSOR_REVOKE_CONFIRM_BEFORE_ASSIGNMENT : Bill::STATUS_WAITING_POSSESSOR_REVOKE_CONFIRM_AFTER_ASSIGNMENT;
				$model->instance->save(false);
				TradeRecord::addTradeRecord(TradeRecord::TYPE_APPLY_CANCEL_ORDER, $model->instance, $model->instruction_id);
				$transaction->commit();
				return $this->sendResponse(['message' => '投资者撤单成功，等待拥有者确认']);
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException($e);
			}
		}
		$model->handleError();
	}

	/**
	 * actionInvestorCancelRevoke()
	 * 投资人关闭撤单
	 * ----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionInvestorCancelRevoke(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('investorCancelRevoke');
		$model->investor = $userInfo->id;
		$model->load(['Bill' => Yii::$app->request->post()]);
		if($model->validate()){
			if(!Yii::$app->blockChain->operateNote($model->signature, $model->instruction_id, $userInfo->address, $model->instance->bill_number, TradeRecord::TYPE_REVOKE_CANCEL_ORDER)){
				throw new Exception(13075);
			}
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$model->instance->status = ($model->instance->status == Bill::STATUS_WAITING_POSSESSOR_REVOKE_CONFIRM_BEFORE_ASSIGNMENT || $model->instance->status == Bill::STATUS_POSSESSOR_REFUSE_REVOKE_BEFORE_ASSIGNMENT) ? Bill::STATUS_WAITING_POSSESSOR_ASSIGNMENT : Bill::STATUS_WAITING_INVESTOR_CONFIRM;
				$model->instance->save(false);
				TradeRecord::addTradeRecord(TradeRecord::TYPE_REVOKE_CANCEL_ORDER, $model->instance, $model->instruction_id);
				$transaction->commit();
				return $this->sendResponse(['message' => '撤单关闭成功，已恢复撤单前状态']);
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException($e);
			}
		}
		$model->handleError();
	}

	/**
	 * actionPossessorConfirmRevoke()
	 * 拥有人确认撤销订单
	 * ------------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionPossessorConfirmRevoke(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('possessorConfirmRevoke');
		$model->possessor = $userInfo->id;
		$model->load(['Bill' => Yii::$app->request->post()]);
		if($model->validate()){
			if(!Yii::$app->blockChain->operateNote($model->signature, $model->instruction_id, $userInfo->address, $model->instance->bill_number, TradeRecord::TYPE_AGREE_CANCEL_ORDER)){
				throw new Exception(13076);
			}
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$model->instance->status = Bill::STATUS_REFUND_FAILED;
				$instance = clone $model->instance;
				if(Yii::$app->bank->payUnfreeze($userInfo->enterprise_id, $model->instance->possessorEnterpriseId, $model->instance->financing_amount, $model->trade_password)){
					Daybook::addRecord(Daybook::TYPE_REFUND, Daybook::STATUS_REGULAR, $model->instance);
					TradeRecord::addTradeRecord(TradeRecord::TYPE_REFUND, $model->instance);
					TradeRecord::addTradeRecord(TradeRecord::TYPE_REIMBURSE, $model->instance);
					$model->instance->investor = null;
					$model->instance->investor_contract_path = null;
					$model->instance->possessor_contract_path = null;
					$model->instance->status = Bill::STATUS_LISTING;
				}
				TradeRecord::addTradeRecord(TradeRecord::TYPE_AGREE_CANCEL_ORDER, $instance, $model->instruction_id);
				$model->instance->save(false);
				$transaction->commit();
				return $this->sendResponse(['message' => '撤单确认成功，票据已恢复为挂牌状态']);
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException($e);
			}
		}
		$model->handleError();
	}

	/**
	 * actionPossessorRefuseRevoke()
	 * 拥有人拒绝撤销订单
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionPossessorRefuseRevoke(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('possessorRefuseRevoke');
		$model->possessor = $userInfo->id;
		$model->load(['Bill' => Yii::$app->request->post()]);
		if($model->validate()){
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$model->instance->status = $model->instance->status == Bill::STATUS_WAITING_POSSESSOR_REVOKE_CONFIRM_BEFORE_ASSIGNMENT ? Bill::STATUS_POSSESSOR_REFUSE_REVOKE_BEFORE_ASSIGNMENT : Bill::STATUS_POSSESSOR_REFUSE_REVOKE_AFTER_ASSIGNMENT;
				$model->instance->save(false);
				TradeRecord::addTradeRecord(TradeRecord::TYPE_REFUSE_CANCEL_ORDER, $model->instance);
				$transaction->commit();
				return $this->sendResponse(['message' => '撤单拒绝成功，已恢复撤单前状态']);
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException($e);
			}
		}
		$model->handleError();
	}

	/**
	 * actionInvestorContractFile()
	 * 投资人上传合同文件
	 * ----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionInvestorContractFile(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('investorContractFile');
		$model->investor = $userInfo->id;
		$model->load(['Bill' => Yii::$app->request->post()]);
		if($model->validate()){
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$image = new Image('investor_contract_file');
				$image->setScenario('create');
				$image->type = Image::TYPE_INVESTOR_CONTRACT;
				if($image->validate() && $image->saveAs(Yii::getAlias('@static') . DIRECTORY_SEPARATOR . $image->path, true) && $image->save(false)){
					$model->instance->investor_contract_path = $image->path;
					$model->instance->save(false);
					$transaction->commit();
					return $this->sendResponse(['message' => '投资人合同上传成功']);
				}
				$image->handleError();
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException($e);
			}
		}
		$model->handleError();
	}

	/**
	 * actionPossessorContractFile()
	 * 拥有人上传合同文件
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionPossessorContractFile(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('possessorContractFile');
		$model->possessor = $userInfo->id;
		$model->load(['Bill' => Yii::$app->request->post()]);
		if($model->validate()){
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$image = new Image('possessor_contract_file');
				$image->setScenario('create');
				$image->type = Image::TYPE_POSSESSOR_CONTRACT;
				if($image->validate() && $image->saveAs(Yii::getAlias('@static') . DIRECTORY_SEPARATOR . $image->path, true) && $image->save(false)){
					$model->instance->possessor_contract_path = $image->path;
					$model->instance->save(false);
					$transaction->commit();
					return $this->sendResponse(['message' => '拥有人合同上传成功']);
				}
				$image->handleError();
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException($e);
			}
		}
		$model->handleError();
	}

	/**
	 * actionList()
	 * 交易列表
	 * ------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionList(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Bill;
		$model->setScenario('tradeList');
		$model->load(['Bill' => Yii::$app->request->get()]);
		if($model->validate()){
			switch($model->filter){
				case Bill::FILTER_HOLDING:
					$query = Bill::find();
					$query->andFilterWhere(['possessor' => $userInfo->id]);
					$query->andFilterWhere(['status' => Bill::STATUS_HOLDING]);
					break;

				case Bill::FILTER_TRADING:
					$query = Bill::find();
					$query->andFilterWhere(['or', ['possessor' => $userInfo->id], ['investor' => $userInfo->id]]);
					$query->andFilterWhere(['between', 'status', Bill::STATUS_LISTING, Bill::STATUS_POSSESSOR_REFUSE_REVOKE_AFTER_ASSIGNMENT]);
					break;

				case Bill::FILTER_FINLISHED:
					$query = TradeRecord::find();
					$query->where(['user_id' => $userInfo->id]);
					$query->andFilterWhere([TradeRecord::tableName() . '.type' => [TradeRecord::TYPE_COLLECTION, TradeRecord::TYPE_REIMBURSE]]);
					$query->andFilterWhere(['bill_type' => $model->type]);
					if($model->query){
						$query->andFilterWhere(['or', ['like', Bill::tableName() . '.bill_number', $model->query], ['like', Bill::tableName() . '.acceptor', $model->query], ['like', Bill::tableName() . '.face_amount', $model->query], ['like', TradeRecord::tableName() . 'amount', $model->query]]);
					}
					break;
			}
			if(in_array($model->filter, [Bill::FILTER_HOLDING, Bill::FILTER_TRADING])){
				$query->andFilterWhere(['type' => $model->type]);
				$query->andFilterWhere(['>=', 'acceptance_at', date('Y-m-d')]);
				$query->andFilterWhere(['like', 'acceptor', $model->acceptor]);
				$query->andFilterWhere(['acceptor_type' => $model->acceptor_type]);
				$query->andFilterWhere(['between', 'acceptance_at', $model->startTime, $model->endTime]);
				if($range = $model->getRange()){
					$query->andFilterWhere(['between', 'financing_amount', $range[0], $range[1]]);
				}
				if($model->query){
					$query->andFilterWhere(['or', ['like', 'bill_number', $model->query], ['like', 'acceptor', $model->query], ['like', 'face_amount', $model->query], ['like', 'financing_amount', $model->query]]);
				}
			}
			return $this->sendResponse($model->tradeList($query));
		}
		$model->handleError();
	}

	/**
	 * actionHistory()
	 * 交易历史列表
	 * ---------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionHistory(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new TradeRecord;
		$model->setScenario('tradeHistory');
		$model->load(['TradeRecord' => Yii::$app->request->get()]);
		if($model->validate()){
			$query = TradeRecord::find()
				->where(['user_id' => $userInfo->id, 'type' => [TradeRecord::TYPE_TYPE_IN, TradeRecord::TYPE_LISTING, TradeRecord::TYPE_DELIST, TradeRecord::TYPE_ASSIGNMENT, TradeRecord::TYPE_REVOKE, TradeRecord::TYPE_AGREE_CANCEL_ORDER]])
				->andFilterWhere(['type' => $model->type])
				->andFilterWhere(['bill_type' => $model->bill_type])
				->andFilterWhere(['between', 'created_at', $model->startTime, $model->endTime]);
			return $this->sendResponse($model->tradeHistory($query));
		}
		$model->handleError();
	}
}