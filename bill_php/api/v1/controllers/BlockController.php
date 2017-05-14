<?php
namespace api\v1\controllers;

use Yii;
use api\v1\components\ApiController;
use api\v1\components\Exception;
use api\v1\models\User;

/**
 * Block Controller
 * 区块链控制器
 * ----------------
 * @version 1.0.0
 * @author Verdient。
 */
class BlockController extends ApiController
{
	/**
	 * actionQuery()
	 * 向区块链发送查询数据
	 * --------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionQuery(){
		if(Yii::$app->request->headers->get('Authorization') != 'hehehehe'){
			throw new Exception(403);
		}
		if(!$signature = Yii::$app->request->post('signature')){
			throw new Exception(36001);
		}
		if($result = Yii::$app->blockChain->query($signature)){
			return $this->sendResponse($result);
		}
		throw new Exception(36000);
	}

	/**
	 * actionExecute()
	 * 向区块链发送执行数据
	 * --------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionExecute(){
		if(Yii::$app->request->headers->get('Authorization') != 'hehehehe'){
			throw new Exception(403);
		}
		if(!$signature = Yii::$app->request->post('signature')){
			throw new Exception(36001);
		}
		if(Yii::$app->blockChain->execute($signature)){
			return $this->sendResponse(['message' => '操作成功']);
		}
		throw new Exception(36000);
	}

	/**
	 * actionCashIn()
	 * 充值
	 * --------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionCashIn(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		if(!$amount = Yii::$app->request->post('amount')){
			throw new Exception(36002);
		}
		if(!Yii::$app->blockChain->cashIn($userInfo->address, $userInfo->id, $amount)){
			throw new Exception(36003);
		}
		return $this->sendResponse(['message' => '充值成功']);
	}

	/**
	 * actionResister()
	 * 注册
	 * ----------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionResister(){
		$userInfo = $this->checkPermission([User::TYPE_EXTERNAL], [User::TYPE_EXTERNAL]);
		if(!Yii::$app->blockChain->userRegisterBySystem($userInfo->id, $userInfo->address, $userInfo->id)){
			throw new Exception(14015);
		}
		return $this->sendResponse(['message' => '注册成功']);
	}
}