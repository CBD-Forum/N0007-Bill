<?php
namespace common\components;

use Yii;
use yii\base\UserException;

/**
 * Exception Model
 * 异常 模型
 * ---------------
 * @version 1.0.2
 * @author Verdient。
 */
class Exception extends UserException
{
	const ERROR = 'error';
	const UNKNOWN_ERROR_CODE = 500;

	/**
	 * __construct([Integer / String $code = null, String / Integer $message = null, Throwable $previous = null])
	 * 构造函数
	 * ----------------------------------------------------------------------------------------------------------
	 * @param Integer / String $code 错误代码
	 * @param String / Integer $message 错误描述
	 * @param Throwable $previous 先前的Exception
	 * ------------------------------------------
	 * @inheritdoc
	 * -----------
	 * @author Verdient。
	 */
	public function __construct($code = null, $message = null, $previous = null){
		if(is_numeric($message) && is_string($code)){
			list($code, $message) = [$message, $code];
		}
		$code = is_numeric($code) ? $code : self::UNKNOWN_ERROR_CODE;
		$message = $message ?: Yii::t('errorCode', $code);
		parent::__construct($message, $code, $previous);
	}

	/**
	 * getName()
	 * 获取异常信息
	 * ------------
	 * @inheritdoc
	 * -----------
	 * @return String
	 * @author Verdient。
	 */
	public function getName(){
		return self::ERROR;
	}
}
