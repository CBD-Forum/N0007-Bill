<?php
namespace console\components;

use yii\console\Controller;

/**
 * Console Controller
 * 命令行控制器
 * ------------------
 * @version 1.0.0
 * @author Verdient。
 */
class ConsoleController extends Controller
{
	/**
	 * @var public $yes
	 * 确认执行
	 * ----------------
	 * @author Verident。
	 */
	public $yes;

	/**
	 * options(String $actionID)
	 * 参数设置
	 * -------------------------
	 * @param String $actionID 动作ID
	 * ------------------------------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verident。
	 */
	public function options($actionID){
		return ['yes'];
	}

	/**
	 * optionAliases()
	 * 参数别名设置
	 * ----------------
	 * @inheritdoc
	 * -----------
	 * @author Verident。
	 */
	public function optionAliases(){
		return ['y' => 'yes'];
	}

	/**
	 * confirm(String $message[, Boolean / String $dafault = null])
	 * 确认
	 * ------------------------------------------------------------
	 * @param String $message 提示字符串
	 * @param Boolean / String $dafault 默认值
	 * ---------------------------------------
	 * @return Boolean
	 * @author Verdient
	 */
	public function confirm($message, $default = null){
		if(!$this->yes){
			$default = $default === true ? 'y' : ($default === false ? 'n' : $default);
			switch(strtolower($this->prompt($message . ' (y[es]/n[o])', ['default' => $default]))){
				case 'y': case 'yes':
					return true;
				case 'n': case 'no':
					return false;
				default:
					return $this->confirm('Error: Unknown operator, please try again:', $default);
					break;
			}
		}
		return true;
	}

	/**
	 * prompt(String $text[, Array $options = []])
	 * 接收用户输入的值
	 * -------------------------------------------
	 * @param String $text 提示字符串
	 * @param Array $options 选项
	 * ------------------------------
	 * @return Boolean
	 * @author Verdient
	 */
	public function prompt($text, $options = []){
		if(!$prompt = parent::prompt($text, $options)){
			if(!isset($options['default'])){
				return $this->prompt($text, $options);
			}
		}
		return $prompt;
	}
}