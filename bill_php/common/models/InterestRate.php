<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * InterestRate Model
 * 利率模型
 * ------------------
 * @version 1.0.0
 * @author Verdient。
 */
class InterestRate extends Model
{

	/**
	 * annualizedRate(Float $principal, Float $receivables, Integer $lifeOfLoan)
	 * 年化利率
	 * -------------------------------------------------------------------------
	 * @param Float $principal 本金
	 * @param Float $receivables 本息
	 * @param Integer $lifeOfLoan 借款天数
	 * -----------------------------------
	 * @return Float
	 * @author Verdient。
	 */
	public static function annualizedRate($principal, $receivables, $lifeOfLoan){
		return self::dayRate($principal, $receivables, $lifeOfLoan) * 360;
		// return pow((1 + self::dayRate($principal, $receivables, $lifeOfLoan)), 365) -1;
	}

	/**
	 * dayRate(Float $principal, Float $receivables, Integer $lifeOfLoan)
	 * 日利率
	 * ------------------------------------------------------------------
	 * @param Float $principal 本金
	 * @param Float $receivables 本息
	 * @param Integer $lifeOfLoan 借款天数
	 * @return Float
	 * @author Verdient。
	 */
	public static function dayRate($principal, $receivables, $lifeOfLoan){
		return ($receivables - $principal) / $receivables / $lifeOfLoan * 100;
		// return pow(($receivables / $principal), (1 / $lifeOfLoan)) - 1;
	}
}