<?php
namespace api\v1\controllers;

use Yii;
use api\v1\components\ApiController;
use api\v1\components\Exception;
use api\v1\models\Config;
use api\v1\models\User;

/**
 * Config Controller
 * 配置控制器
 * ---------------
 * @version 1.0.0
 * @author Verdient。
 */
class ConfigController extends ApiController
{
	/**
	 * actionSetAnnualizedRate()
	 * 设置贴现率
	 * -------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionSetAnnualizedRate(){
		$this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Config;
		$model->setScenario('setAnnualizedRate');
		$model->load(['Config' => Yii::$app->request->post()]);
		if($model->validate()){
			Yii::$app->config->setConfig('annualized_rate_min', $model->annualized_rate_min);
			Yii::$app->config->setConfig('annualized_rate_max', $model->annualized_rate_max);
			return $this->sendResponse(['message' => '贴现率设置成功']);
		}
		$model->handleError();
	}

	/**
	 * actionGetAnnualizedRate()
	 * 获取贴现率
	 * -------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionAnnualizedRate(){
		$this->checkPermission([User::TYPE_ADMIN]);
		$configs = Yii::$app->config->configs;
		return $this->sendResponse(['annualized_rate_min' => isset($configs['annualized_rate_min']) ? $configs['annualized_rate_min'] : null, 'annualized_rate_max' => isset($configs['annualized_rate_max']) ? $configs['annualized_rate_max'] : null]);
	}

	/**
	 * actionGetConfigs()
	 * 获取配置
	 * ------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionGetConfigs(){
		$this->checkPermission([User::TYPE_ADMIN]);
		return $this->sendResponse(Yii::$app->config->configs);
	}

	/**
	 * actionSetConfigs()
	 * 批量设置
	 * ------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionSetConfigs(){
		$this->checkPermission([User::TYPE_ADMIN]);
		$model = new Config;
		$model->setScenario('setConfigs');
		$model->load(['Config' => Yii::$app->request->post()]);
		if($model->validate()){
			Yii::$app->config->setConfigs([
				'annualized_rate_min' => $model->annualized_rate_min,
				'annualized_rate_max' => $model->annualized_rate_max,
				'listing_reserve_day' => $model->listing_reserve_day
			]);
			return $this->sendResponse(['message' => '设置修改成功']);
		}
		$model->handleError();
	}
}