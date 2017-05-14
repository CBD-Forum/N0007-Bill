<?php
namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use common\components\Exception;

/**
 * Exception Behavior
 * 异常行为模型
 * ------------------
 * @version 1.0.0
 * @author Verdient。
 */
class ExceptionBehavior extends Behavior
{
	/**
	 * catchException($e)
	 * 捕获异常
	 * ------------------
	 * @param Object $e 异常对象
	 * -------------------------
	 * @author Verdient。
	 */
	public function catchException($e){
		$code = $e->getCode();
		$message = Yii::t('errorCode', $code);
		if($e instanceof Exception){
			throw new Exception($message, $e->getCode());
		}
		if(defined('YII_ENV') && strtolower(YII_ENV) === 'dev'){
			throw new \Exception($e->getMessage() . ' -- ' . $e->getFile() . ':' .  $e->getLine(), $e->getCode());
		}
		throw new Exception(500);
	}

	/**
	 * getFirstErrorCode()
	 * 获取第一个错误代码
	 * --------------------
	 * @throws Exception
	 * @author Verdient。
	 */
	public function getFirstErrorCode(){
		$errors = $this->owner->getFirstErrors();
		return reset($errors);
	}

	/**
	 * handleError()
	 * 处理错误
	 * -------------
	 * @throws Exception
	 * @author Verdient。
	 */
	public function handleError(){
		throw new Exception($this->firstErrorCode);
	}
}