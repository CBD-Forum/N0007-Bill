<?php
namespace common\log;

use Yii;
use yii\helpers\VarDumper;
use yii\log\FileTarget as baseFileTarget;
use yii\log\Logger;

/**
 * FileTarget
 * 文件目标
 * ----------
 * @version 1.0.0
 * @author Verdient。
 */
class FileTarget extends baseFileTarget
{

	/**
	 * formatMessage(Array $message)
	 * 格式化信息
	 * -----------------------------
	 * @param Array $message 信息
	 * --------------------------
	 * @return String
	 * @author Verident。
	 */
	public function formatMessage($message){
		list($text, $level, $category, $timestamp) = $message;
		$level = Logger::getLevelName($level);
		if (!is_string($text)) {
			if ($text instanceof \Throwable || $text instanceof \Exception) {
				$text = (string) $text;
			} else {
				$text = VarDumper::export($text);
			}
		}
		$traces = [];
		if (isset($message[4])) {
			foreach ($message[4] as $trace) {
				$traces[] = "in {$trace['file']}:{$trace['line']}";
			}
		}
		$prefix = $this->getMessagePrefix($message);
		list($usec, $sec) = explode(" ", microtime());
    	$microtime = $sec . str_replace('0.', '', $usec);
		return "$microtime | " . date('Y-m-d H:i:s', $timestamp) . " | {$prefix} | $level | $category | $text" . (empty($traces) ? '' : "\n	" . implode("\n	", $traces));
	}
}