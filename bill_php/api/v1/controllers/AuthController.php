<?php
namespace api\v1\controllers;

use Yii;
use common\helpers\StringHelper;
use api\v1\components\ApiController;
use api\v1\components\Exception;
use api\v1\models\Agent;
use api\v1\models\Auth;
use api\v1\models\Enterprise;
use api\v1\models\PublicKey;
use api\v1\models\User;

/**
 * Auth Controller
 * 认证控制器
 * ---------------
 * @version 1.0.0
 * @author Verdient。
 */
class AuthController extends ApiController
{
	/**
	 * actionToken()
	 * 获取token
	 * -------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionToken(){
		$model = Yii::$app->OAuth2;
		$model->setScenario('token');
		if($model->validate()){
			$model->setScenario($model->grantType);
			$model->validate();
			$model->addLog();
			if(!$model->hasErrors()){
				if(!$publicKeyModel = PublicKey::findOne(['user_id' => $model->user->id])){
					if($model->user->type == User::TYPE_EXTERNAL){
						$publicKeyModel = new PublicKey;
						$publicKeyModel->user_id = $model->user->id;
						$publicKeyModel->random = $publicKeyModel->generateRandom();
						if(!$publicKey = Yii::$app->blockChain->getPublicKey($model->password, $publicKeyModel->random)){
							throw new Exception(14012);
						}
						if(!Yii::$app->blockChain->userRegisterBySystem($model->user->id, $publicKey, $model->user->id)){
							throw new Exception(14015);
						}
						$publicKeyModel->public_key = $publicKey;
						$publicKeyModel->status = PublicKey::STATUS_REGULAR;
						$publicKeyModel->save(false);
					}else{
						throw new Exception(14014);
					}
				}
				if(!isset($publicKey)){
					if(!$publicKey = Yii::$app->blockChain->getPublicKey($model->password, $publicKeyModel->random)){
						throw new Exception(14012);
					}
				}
				if($publicKeyModel->public_key != $publicKey){
					throw new Exception(14013);
				}
				return $this->sendResponse(array_merge($model->createAccessToken(), ['random' => $publicKeyModel->random]));
			}
		}
		$errors = $model->getFirstErrors();
		throw new Exception(reset($errors));
	}

	/**
	 * actionCertification()
	 * 四要素实名认证
	 * ---------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionCertification(){
		$userInfo = $this->checkPermission([User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Auth;
		$model->setScenario('certification');
		$model->load(['Auth' => Yii::$app->request->post()]);
		if($model->validate()){
			if(!$agentModel = Agent::findOne([
				'status' => Agent::STATUS_REGULAR,
				'name' => $model->name,
				'id_number' => $model->id_number,
				'bank_card_number' => $model->bank_card_number,
				'mobile' => $model->mobile,
				'user_id' => $userInfo->id
			])){
				if(!Yii::$app->fourElements->validateFourElements($model->name, $model->id_number, $model->bank_card_number, $model->mobile)){
					throw new Exception(14010);
				}
				$agentModel = new Agent;
				$agentModel->status = Agent::STATUS_REGULAR;
				$agentModel->user_id = $userInfo->id;
				$agentModel->name = $model->name;
				$agentModel->id_number = $model->id_number;
				$agentModel->bank_card_number = $model->bank_card_number;
				$agentModel->mobile = $model->mobile;
			}
			$agentModel->access_token = Yii::$app->OAuth2->accessTokenInfo['access_token'];
			$agentModel->setScenario('create');
			if(!$agentModel->save()){
				throw new Exception(14010);
			}
			return $this->sendResponse(['message' => '认证成功']);
		}
		$errors = $model->getFirstErrors();
		throw new Exception(reset($errors));
	}

	/**
	 * actionEnterpriseApply()
	 * 申请企业认证
	 * -----------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionEnterpriseApply(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Enterprise();
		$model->setScenario('apply');
		$model->enterprise_id = $userInfo->enterprise_id;
		if($model->validate()){
			$model->instance->amount = null;
			$model->instance->status = Enterprise::STATUS_WAITING_AUDIT;
			$model->instance->failed_count = 0;
			$model->instance->save(false);
			return $this->sendResponse(['message' => '认证申请成功']);
		}
		$model->handleError();
	}

	/**
	 * actionEnterprise()
	 * 企业认证
	 * -----------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionEnterprise(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Enterprise();
		$model->setScenario('confirm');
		$model->load(['Enterprise' => Yii::$app->request->post()]);
		$model->enterprise_id = $userInfo->enterprise_id;
		if($model->validate()){
			$model->instance->amount = null;
			$model->instance->failed_count = null;
			$model->instance->status = Enterprise::STATUS_REGULAR;
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$model->instance->save(false);
				$transaction->commit();
				return $this->sendResponse(['message' => '认证成功']);
			}catch(\Exception $e){
				$transaction->rollback();
				$this->catchException();
			}
		}
		if($model->firstErrorCode == 26006){
			$model->instance->failed_count++;
			$leftNum = (int)Yii::$app->config->configs['authentication_retry_max'] - (int)$model->instance->failed_count;
			if($leftNum <= 0){
				$model->instance->amount = null;
				$model->instance->failed_count = null;
				$model->instance->status = Enterprise::STATUS_AUTHENTICATION_FAILED;
				$model->instance->save(false);
				return $this->sendResponse(['code' => 29007, 'message' => '认证失败，请重新提交申请']);
			}
			$model->instance->save(false);
			return $this->sendResponse(['code' => 29006, 'message' => '验证款错误，您还有' . StringHelper::numberToChinese($leftNum) . '次机会']);
		}
		$model->handleError();
	}
}