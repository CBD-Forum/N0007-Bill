<?php
namespace api\v1\controllers;

use Yii;
use common\components\upload\Image;
use common\components\upload\Office;
use common\helpers\Helper;
use api\v1\components\ApiController;
use api\v1\components\Exception;
use api\v1\models\User;

/**
 * UploadController Controller
 * 上传控制器
 * ---------------------------
 * @version 1.0.0
 * @author Verdient。
 */
class UploadController extends ApiController
{
	/**
	 * actionImage()
	 * 上传图片
	 * -------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionImage(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Image('image_file');
		$model->setScenario('create');
		$model->type = Yii::$app->request->post('type');
		if($model->validate() && $model->saveAs(Yii::getAlias('@static') . DIRECTORY_SEPARATOR . $model->path, true) && $model->save(false)){
			return $this->sendResponse([
				'size' => $model->size,
				'original_name' => $model->original_name,
				'type' => $model->type,
				'path' => $model->path,
				'upload_at' => date('Y-m-d H:i:s', $model->created_at),
				'url' => Yii::$app->staticResource->path2url($model->path)
			]);
		}
		$model->handleError();
	}

	/**
	 * actionOffice()
	 * 上传Office文件
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionOffice(){
		$model = new Office('office_file');
		$model->setScenario('create');
		$model->type = Yii::$app->request->post('type');
		if($model->validate() && $model->saveAs(Yii::getAlias('@static') . DIRECTORY_SEPARATOR . $model->path, true) &&  $model->save(false)){
			return $this->sendResponse([
				'size' => $model->size,
				'original_name' => $model->original_name,
				'type' => $model->type,
				'path' => $model->path,
				'upload_at' => date('Y-m-d H:i:s', $model->created_at),
				'url' => Yii::$app->staticResource->path2url($model->path)
			]);
		}
		$model->handleError();
	}
}