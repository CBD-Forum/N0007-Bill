<?php
namespace api\v1\controllers;

use Yii;
use common\models\BaseCompany;
use api\v1\components\ApiController;
use api\v1\components\Exception;
use api\v1\models\Message;

/**
 * Message Controller
 * 通知控制器
 * ------------------
 * @version 1.0.0
 * @author Verdient。
 */
class MessageController extends ApiController
{
	/**
	 * actionSendCaptcha()
	 * 发送验证码
	 * -------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionSendCaptcha(){
		$userInfo = Yii::$app->OAuth2->userInfo;
		if($time = Yii::$app->cache->get(__METHOD__ . $userInfo->id)){
			throw new Exception('访问过于频繁，请 ' . (int)(50 - (time() - $time)) . ' 秒后重试', 15000);
		}
		$model = new Message;
		$model->setScenario('captcha');
		$model->load(['Message' => Yii::$app->request->post()]);
		$model->enterprise_id = $userInfo->enterprise_id;
		if($model->validate()){
			if(Yii::$app->message->sendSMS($model->mobile, 'captcha', 18, 'xjxrandom')){
				Yii::$app->cache->set(__METHOD__ . $userInfo->id, time(), 50);
				return $this->sendResponse(['message' => '发送成功']);
			}
			throw new Exception(15001);
		}
		$errors = $model->getFirstErrors();
		throw new Exception(reset($errors));
	}
}