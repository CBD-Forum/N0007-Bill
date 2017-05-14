<?php
namespace common\components;

use Yii;
use yii\base\Model;

/**
 * Validate Model
 * 校验 模型
 * --------------
 * @version 1.0.0
 * @author Verdient。
 */
class Validate extends Model
{
	/**
	 * @var public $enableValidate
	 * 是否启用校验
	 * ---------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $enableValidate = true;

	/**
	 * @var public $enabledTypes
	 * 允许的类型
	 * -------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $enabledTypes = [
		'mobile', 'IDCard', 'bankCard', 'billNumber', 'businessLicenseNumber'
	];

	/**
	 * check(String $value, String $type)
	 * 判断是否是合法的手机号码
	 * -------------------------------------
	 * @param String $value 待检验的值
	 * @param String $type 类型
	 * -------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function check($value, $type){
		if($this->enableValidate){
			if(!in_array((string)$type, $this->enabledTypes) || !method_exists($this, $type)){
				throw new Exception(12001);
			}
			return $this->$type($value);
		}
		return true;
	}

	/**
	 * mobile(String $value)
	 * 判断是否是合法的手机号码
	 * ------------------------
	 * @param String $value 手机号码
	 * ------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	private function mobile($value){
		$reg = "/^(13[0-9]|15[0-9]|18[0-9]|14[57]|17[078])[0-9]{8}$/";
		return preg_match($reg, $value) ? true : false;
	}

	/**
	 * IDCard(String $value)
	 * 判断是否是合法的身份证号码
	 * --------------------------
	 * @param String $value 身份证号码
	 * -------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	private function IDCard($value){
		$value = strtoupper($value);
		$reg = "/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}(0[1-9]|(1[0-2]))(0[1-9]|([1|2])\d|3[0-1])((\d{4})|\d{3}[X])$)$/";
		if (preg_match($reg, $value)) {
			if (strlen($value) == 18) {
				$value = str_split($value);
				$idCardWi = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
				$idCardY = [1, 0, 'X', 9, 8, 7, 6, 5, 4, 3, 2];
				$idCardWiSum = 0;
				for ($i = 0; $i < 17; $i++) {
					$idCardWiSum += $value[$i] * $idCardWi[$i];
				}
				$idCardMod = $idCardWiSum % 11;
				if ($value[17] == $idCardY[$idCardMod]) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * bankCard(String $value)
	 * 判断是否是合法的银行卡号
	 * ------------------------
	 * @param String $value 银行卡号
	 * -----------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	private function bankCard($value){
		return preg_match('/^(\d{16}|\d{19})$/', $value);
	}

	/**
	 * billNumber(String $value)
	 * 判断是否是合法的票据编号
	 * --------------------------
	 * @param String $value 票据编号
	 * -----------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	private function billNumber($value){
		return preg_match('/^(\d{30})$/', $value);
	}

	/**
	 * businessLicenseNumber(String $value)
	 * 判断是否是合法的营业执照编号
	 * ------------------------------------
	 * @param String $value 营业执照编号
	 * ---------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	private function businessLicenseNumber($value){
		return true;
		return preg_match('/^(\d{13}|\d{15})$/', $value);
	}
}
