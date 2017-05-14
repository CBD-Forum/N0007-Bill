<?php
namespace api\v1\controllers;

use Yii;
use yii\web\Response;
use api\v1\components\ApiController;

/**
 * Site Controller
 * 默认控制器
 * ---------------
 * @version 1.0.0
 * @author Verdient。
 */
class SiteController extends ApiController
{
	/**
	 * actionIndex()
	 * 显示首页
	 * -------------
	 * @return String
	 * @author Verdient。
	 */
	public function actionIndex(){
		$this->layout = false;
		if(in_array($this->module->response->acceptMimeType, ['application/json', 'application/xml'])){
			return $this->sendResponse(['message' => 'Welcome to haipiaohui api system']);
		}
		Yii::$app->response->format = Response::FORMAT_HTML;
		return $this->render('index');
	}
}
