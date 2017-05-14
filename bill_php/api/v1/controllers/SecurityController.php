<?php
namespace api\v1\controllers;

use Yii;
use api\v1\components\ApiController;
use api\v1\components\Exception;
use api\v1\models\PublicKey;
use api\v1\models\Security;
use api\v1\models\User;

/**
 * Security Controller
 * 安全 控制器
 * -------------------
 * @version 1.0.0
 * @author Verdient。
 */
class SecurityController extends ApiController
{
	/**
	 * actionChangePassword()
	 * 修改密码
	 * ----------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionChangePassword(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_ADMIN, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new User;
		$model->setScenario('changePassword');
		$model->load(['User' => Yii::$app->request->post()]);
		if($model->validate()){
			if(!$publicKey = Yii::$app->blockChain->getPublicKey($model->new_password, $userInfo->publicKeyInfo->random)){
				throw new Exception(31033);
			}
			if(!in_array($userInfo->status, [PublicKey::STATUS_WAITING_CHANGE_PASSWORD, PublicKey::STATUS_REGULAR])){
				throw new Exception(31035);
			}
			if(Yii::$app->haipiaohui->changePassword($userInfo->id, $model->new_password)){
				$userInfo->publicKeyInfo->status = PublicKey::STATUS_REGULAR;
				if(!Yii::$app->blockChain->transferAccount($model->signature, $model->instruction_id, $userInfo->publicKeyInfo->public_key, $userInfo->id, $publicKey)){
					$userInfo->publicKeyInfo->status = PublicKey::STATUS_TRANSFER_FAILED;
					$userInfo->publicKeyInfo->save(false);
					throw new Exception(31021);
				}
				$userInfo->publicKeyInfo->public_key = $publicKey;
				$userInfo->publicKeyInfo->save(false);
				return $this->sendResponse(['message' => '密码修改成功']);
			}
			throw new Exception(31012);
		}
		$model->handleError();
	}

	/**
	 * actionChangeTradePassword()
	 * 修改交易密码
	 * ---------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionChangeTradePassword(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_ADMIN, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Security;
		$model->setScenario('changeTradePassword');
		$model->load(['Security' => Yii::$app->request->post()]);
		if($model->validate()){
			if(!Yii::$app->bank->changeTradePassword($userInfo->enterprise_id, $model->trade_password, $model->new_trade_password)){
				throw new Exception(32006);
			}
			return $this->sendResponse(['message' => '交易密码修改成功']);
		}
		$model->handleError();
	}

	/**
	 * actionSetTradePassword()
	 * 设置交易密码
	 * ------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionSetTradePassword(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_ADMIN, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Security;
		$model->setScenario('setTradePassword');
		$model->load(['Security' => Yii::$app->request->post()]);
		if($model->validate()){
			if(!Yii::$app->bank->setTradePassword($userInfo->enterprise_id, $model->new_trade_password)){
				throw new Exception(32007);
			}
			return $this->sendResponse(['message' => '交易密码设置成功']);
		}
		$model->handleError();
	}

	/**
	 * actionTradePasswordStatus()
	 * 交易密码状态
	 * ---------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionTradePasswordStatus(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_ADMIN, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		if($result = Yii::$app->bank->tradePasswordStatus($userInfo->enterprise_id)){
			return $this->sendResponse(['status' => (integer)$result]);
		}
		throw new Exception(32008);
	}
}