<?php
namespace api\v1\controllers;

use Yii;
use api\v1\components\ApiController;
use api\v1\components\Exception;
use api\v1\models\PublicKey;
use api\v1\models\User;

/**
 * User Controller
 * 用户控制器
 * ---------------
 * @version 1.0.0
 * @author Verdient。
 */
class UserController extends ApiController
{
	/**
	 * actionInfo()
	 * 获取用户信息
	 * ------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionInfo(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_MEMBER, User::TYPE_EXTERNAL, User::TYPE_ADMIN]);
		return $this->sendResponse([
			'user_id' => $userInfo->id,
			'type' => $userInfo->type,
			'status' => $userInfo->status,
			'username' => $userInfo->name,
			'mobile' => $userInfo->phone,
			'enterprise_id' => $userInfo->enterprise_id,
			'enterprise_name' => $userInfo->tradeCompanyInfo ? $userInfo->tradeCompanyInfo->orgName : null,
			'enterprise_status' => $userInfo->enterpriseInfo ? $userInfo->enterpriseInfo->status : 0
		]);
	}

	/**
	 * actionAddMember()
	 * 增加成员公司
	 * -----------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionAddMember(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE]);
		$model = new User;
		$model->setScenario('addMember');
		$model->load(['User' => Yii::$app->request->post()]);
		if($model->validate()){
			if(!$result = Yii::$app->haipiaohui->generateMemberAccount($model->enterprise_name, $model->user_name, $userInfo->enterprise_id)){
				throw new Exception(31011);
			}
			if(!$user = User::find()->select('id')->where(['email' => $result['username']])->asArray()->one()){
				throw new Exception(31034);
			}
			$publicKeyModel = new PublicKey;
			$publicKeyModel->user_id = $user['id'];
			$publicKeyModel->random = $publicKeyModel->generateRandom();
			$publicKeyModel->status = PublicKey::STATUS_WAITING_SIGN_UP;
			if(!$publicKey = Yii::$app->blockChain->getPublicKey($result['password'], $publicKeyModel->random)){
				throw new Exception(31033);
			}
			$publicKeyModel->public_key = $publicKey;
			$publicKeyModel->save(false);
			return $this->sendResponse([
				'email' => $result['username'],
				'password' => $result['password'],
				'public_key' => $publicKey,
				'user_id' => $user['id']
			]);
		}
		$model->handleError();
	}

	/**
	 * actionActivateExternal()
	 * 激活外部公司
	 * ------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionActivateExternal(){
		$userInfo = $this->checkPermission([User::TYPE_ADMIN]);
		$model = new PublicKey;
		$model->setScenario('activateExternal');
		$model->load(['PublicKey' => Yii::$app->request->post()]);
		if($model->validate()){
			if(!Yii::$app->blockChain->userRegister($model->signature, $model->instruction_id, $userInfo->address, $model->instance->public_key, $model->user_id, User::TYPE_EXTERNAL)){
				throw new Exception(35000);
			}
			$model->instance->status = PublicKey::STATUS_REGULAR;
			$model->instance->save(false);
			return $this->sendResponse(['message' => '用户激活成功']);
		}
		$model->handleError();
	}

	/**
	 * actionActivateMember()
	 * 激活成员公司
	 * ----------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionActivateMember(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE]);
		$model = new PublicKey;
		$model->setScenario('activateMember');
		$model->load(['PublicKey' => Yii::$app->request->post()]);
		if($model->validate()){
			if(!Yii::$app->blockChain->userRegister($model->signature, $model->instruction_id, $userInfo->address, $model->instance->public_key, $model->user_id, User::TYPE_MEMBER)){
				throw new Exception(35000);
			}
			$model->instance->status = PublicKey::STATUS_WAITING_CHANGE_PASSWORD;
			$model->instance->save(false);
			return $this->sendResponse(['message' => '用户激活成功']);
		}
		$model->handleError();
	}

	/**
	 * actionWaitingActivateList()
	 * 待激活成员公司列表
	 * ---------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionWaitingActivateList(){
		$userInfo = $this->checkPermission([User::TYPE_ADMIN]);
		$model = new PublicKey;
		$model->setScenario('waitingActivateList');
		$model->load(['PublicKey' => Yii::$app->request->get()]);
		if($model->validate()){
			$query = PublicKey::find()
				->where(['user_id' => $userInfo->externals, 'status' => [PublicKey::STATUS_WAITING_SIGN_UP, PublicKey::STATUS_SIGN_UP_FAILED]]);
			return $this->sendResponse($model->waitingActivateList($query));
		}
		$model->handleError();
	}

	/**
	 * actionAddMemberHistory()
	 * 开户历史
	 * ------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionAddMemberHistory(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE]);
		$model = new User;
		$model->setScenario('addMemberHistory');
		$model->load(['User' => Yii::$app->request->get()]);
		if($model->validate()){
			$query = User::find()
				->where(['belong_com' => $userInfo->enterprise_id, 'userTag' => 'FINANCE_MEMBER'])
				->andFilterWhere(['or', ['like', 'name', $model->query], ['like', 'email', $model->query]]);
			return $this->sendResponse($model->addMemberHistory($query));
		}
		$model->handleError();
	}
}