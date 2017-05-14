<?php
namespace api\v1\models;

use Yii;
use common\components\Config as baseConfig;

/**
 * Config Model
 * 配置模型
 * ------------
 * @version 1.0.0
 * @author Verdient。
 */
class Config extends baseConfig
{
	/**
	 * rules()
	 * 数据验证规则
	 * ------------
	 * @inheritdoc
	 * ------------
	 * @return Array
	 * @author Verdient。
	 */
	public function rules(){
		return [
			['annualized_rate_min', 'required', 'message' => 33000, 'on' => ['setAnnualizedRate', 'setConfigs']],
			['annualized_rate_max', 'required', 'message' => 33001, 'on' => ['setAnnualizedRate', 'setConfigs']],
			['annualized_rate_min', 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,4})?$/', 'message' => 33002, 'on' => ['setAnnualizedRate', 'setConfigs']],
			['annualized_rate_min', 'compare', 'operator' => '<=', 'compareValue' => 99.99, 'message' => 33010, 'on' => ['setAnnualizedRate', 'setConfigs']],
			['annualized_rate_max', 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,4})?$/', 'message' => 33003, 'on' => ['setAnnualizedRate', 'setConfigs']],
			['annualized_rate_max', 'compare', 'operator' => '<=', 'compareValue' => 99.99, 'message' => 33011, 'on' => ['setAnnualizedRate', 'setConfigs']],
			['annualized_rate_min', 'compare', 'operator' => '<', 'compareAttribute' => 'annualized_rate_max', 'message' => 33004, 'on' => ['setAnnualizedRate', 'setConfigs']],
			['listing_reserve_day', 'required', 'message' => 33005, 'on' => 'setConfigs'],
			['listing_reserve_day', 'integer', 'message' => 33006, 'on' => 'setConfigs'],
			['listing_reserve_day', 'compare', 'operator' => '<=', 'compareValue' => 15, 'message' => 33007, 'on' => 'setConfigs'],
			['listing_reserve_day', 'compare', 'operator' => '>=', 'compareValue' => 1, 'message' => 33009, 'on' => 'setConfigs']
		];
	}
}
