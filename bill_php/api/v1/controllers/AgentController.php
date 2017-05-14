<?php
namespace api\v1\controllers;

use Yii;
use api\v1\components\ApiController;
use api\v1\models\Agent;
use api\v1\models\User;

/**
 * Agent Controller
 * 代理人控制器
 * ----------------
 * @version 1.0.0
 * @author Verdient。
 */
class AgentController extends ApiController
{
	/**
	 * actionList()
	 * 代理人列表
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionList(){
		$userInfo = $this->checkPermission([User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		return $this->sendResponse(['data' => Agent::find()->select(['name', 'id_number', 'bank_card_number', 'mobile'])->where(['user_id' => $userInfo->id])->all()]);
	}
}