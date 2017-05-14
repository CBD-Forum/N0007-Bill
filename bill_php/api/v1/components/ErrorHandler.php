<?php
namespace api\v1\components;

use Yii;
use yii\web\Response;
use yii\web\HttpException;
use yii\web\ErrorHandler as baseErrorHandler;

/**
 * ErrorHandler Model
 * 异常处理模型
 * ------------------
 * @version 1.0.0
 * @author Verdient。
 */
class ErrorHandler extends baseErrorHandler
{
	/**
	 * renderException(Object $exception)
	 * 传递异常
	 * ----------------------------------
	 * @param Object $exception 异常对象
	 * ---------------------------------
	 * @author Verdient。
	 */
	protected function renderException($exception){
		if(Yii::$app->has('response')){
			$response = Yii::$app->getResponse();
			$response->isSent = false;
			$response->stream = null;
			$response->data = null;
			$response->content = null;
		}else{
			$response = new Response();
		}
		if(!YII_DEBUG){
			$headers = Yii::$app->request->headers;
			if($headers->get('accept') == 'application/json'){
				$response->format = Response::FORMAT_JSON;
			}
		}
		$useErrorView = $response->format === Response::FORMAT_HTML && (!YII_DEBUG || $exception instanceof UserException);
		if($useErrorView && $this->errorAction !== null){
			$result = Yii::$app->runAction($this->errorAction);
			if ($result instanceof Response) {
				$response = $result;
			}else{
				$response->data = $result;
			}
		} elseif ($response->format === Response::FORMAT_HTML){
			if(YII_ENV_TEST || isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'){
				$response->data = '<pre>' . $this->htmlEncode(static::convertExceptionToString($exception)) . '</pre>';
			}else{
				if(YII_DEBUG){
					ini_set('display_errors', 1);
				}
				$file = $useErrorView ? $this->errorView : $this->exceptionView;
				$response->data = $this->renderFile($file, [
					'exception' => $exception,
				]);
			}
		}elseif($response->format === Response::FORMAT_RAW) {
			$response->data = static::convertExceptionToString($exception);
		}else{
			$response->data = $this->convertExceptionToArray($exception);
		}
		if($exception instanceof HttpException){
			$response->setStatusCode($exception->statusCode);
		}else{
			$response->setStatusCode(200);
		}
		if(!YII_DEBUG && is_array($response->data)){
			if(isset($response->data['status'])){
				$response->data['code'] = $response->data['status'];
			}
			unset($response->data['name'], $response->data['type'], $response->data['status'], $response->data['previous']);
			ksort($response->data);
		}
		$response->send();
	}
}
