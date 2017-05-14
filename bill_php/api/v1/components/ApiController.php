<?php
namespace api\v1\components;

use Yii;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use common\behaviors\ExceptionBehavior;
use api\v1\components\Exception;

/**
 * Api Controller
 * Api控制器
 * --------------
 * Api项目下所有的控制器均继承自该控制器
 * --------------------------------------
 * @version 1.0.0
 * @author Verdient。
 */
class ApiController extends Controller
{
	/**
	 * behaviors()
	 * 行为设置
	 * -----------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public function behaviors(){
		return ArrayHelper::merge([
			'contentNegotiator' => [
				'class' => ContentNegotiator::className(),
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
					'application/xml' => Response::FORMAT_XML,
					'text/html' => Response::FORMAT_HTML
				],
			],
			'Cors' => [
				'class' => Cors::className(),
				'cors' => [
					'Origin' => ['*'],
					'Access-Control-Request-Headers' => ['*'],
					'Access-Control-Allow-Credentials' => true,
					'Access-Control-Request-Method' => ['GET', 'HEAD', 'OPTIONS', 'POST'],
				],
			],
			'ExceptionBehavior' => ExceptionBehavior::className()
		], parent::behaviors());
	}

	/**
	 * beforeAction(Object $action)
	 * 操作前的操作
	 * ----------------------------
	 * @param Object $action 动作对象
	 * ------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function beforeAction($action){
		parent::beforeAction($action);
		if(Yii::$app->request->isOptions){
			$this->sendResponse(['message' => 'OK']);
			return false;
		}
		return true;
	}

	/**
	 * checkPermission(Array $enabledType[, Array $certificatedType = [], Boolean $authenticated = false])
	 * 操作前的操作
	 * ---------------------------------------------------------------------------------------------------
	 * @param Array $enabledType 允许的用户类型
	 * @param Array $certificatedType 需要四要素认证的用户类型
	 * @param Boolean authenticated 是否需要企业认证
	 * -------------------------------------------------------
	 * @return Object
	 * @throws Exception
	 * @author Verdient。
	 */
	public function checkPermission(Array $enabledType, Array $certificatedType = [], $authenticated = false){
		return Yii::$app->permission->check($enabledType, $certificatedType, $authenticated);
	}

	/**
	 * sendResponse([Array $data = []])
	 * 发送相应
	 * --------------------------------
	 * @param Array $data 要发送的数据
	 * -------------------------------
	 * @return Object
	 * @author Verdient。
	 */
	public function sendResponse($data = []){
		if($data === true || $data == null){
			$data = [];
		}
		if(!is_array($data)){
			throw new Exception(12000);
		}
		return Yii::$app->response->data = array_merge(['code' => 200], $data);
	}
}
