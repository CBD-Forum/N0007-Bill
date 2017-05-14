<?php
namespace api\v1\controllers;

use Yii;
use api\v1\components\ApiController;
use api\v1\models\Data;
use api\v1\components\Exception;

/**
 * Data Controller
 * 数据控制器
 * ---------------
 * @version 1.0.0
 * @author Verdient。
 */
class DataController extends ApiController
{
	/**
	 * actionProvince()
	 * 获取省份列表
	 * ----------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionProvince(){
		return $this->sendResponse(['data' => Yii::$app->bank->getCityList('1', '')]);
	}

	/**
	 * actionCity()
	 * 获取城市列表
	 * ------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionCity(){
		$model = new Data;
		$model->setScenario('city');
		$model->load(['Data' => Yii::$app->request->get()]);
		if($model->validate()){
			return $this->sendResponse(['data' => Yii::$app->bank->getCityList('2', $model->node)]);
		}
		$model->handleError();
	}

	/**
	 * actionDistrict()
	 * 获取地区列表
	 * ----------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionDistrict(){
		$model = new Data;
		$model->setScenario('district');
		$model->load(['Data' => Yii::$app->request->get()]);
		if($model->validate()){
			return $this->sendResponse(['data' => Yii::$app->bank->getCityList('3', $model->node)]);
		}
		$model->handleError();
	}

	/**
	 * actionBank()
	 * 获取银行列表
	 * ------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionBank(){
		return $this->sendResponse(['data' => Yii::$app->bank->getBankList()]);
	}

	/**
	 * actionBank()
	 * 获取支行列表
	 * ------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionBankBranch(){
		$model = new Data;
		$model->setScenario('bankBranch');
		$model->load(['Data' => Yii::$app->request->get()]);
		if($model->validate()){
			return $this->sendResponse(['data' => Yii::$app->bank->getBankBranchList($model->bank, $model->city)]);
		}
		$model->handleError();
	}
}