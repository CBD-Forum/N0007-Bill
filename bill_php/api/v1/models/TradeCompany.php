<?php
namespace api\v1\models;

use Yii;
use common\components\ActiveDataProvider;
use common\models\Bank;
use common\models\TradeCompany as BaseTradeCompany;

/**
 * TradeCompany Model
 * 企业模型
 * ------------------
 * @version 1.0.0
 * @author Verdient。
 */
class TradeCompany extends BaseTradeCompany
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
			['id', 'required', 'message' => 27039, 'on' => 'enterpriseInfo'],
			['id', 'integer', 'message' => 27040, 'on' => 'enterpriseInfo'],
			['id', 'validateId', 'on' => 'enterpriseInfo'],
			['address', 'required', 'message' => 27046, 'on' => 'enterpriseName'],
			['address', 'string', 'min' => 40, 'max' => 100, 'message' => 27047, 'tooLong' => 27047, 'tooShort' => 27047, 'on' => 'enterpriseName'],
			['address', 'validateAddress', 'on' => 'enterpriseName'],
			['id', 'default', 'value' => 0, 'on' => 'enterpriseList'],
			['id', 'integer', 'message' => 27053, 'on' => 'enterpriseList'],
			['sort', 'default', 'value' => 'createTime-desc', 'on' => 'enterpriseList'],
			['sort', 'in', 'range' => ['createTime-asc', 'createTime-desc'], 'message' => 27051, 'on' => 'enterpriseList'],
			['sort', 'filter', 'filter' => self::sortFilter(), 'on' => 'enterpriseList'],
			['page', 'default', 'value' => 1, 'on' => 'enterpriseList'],
			['pageSize', 'default', 'value' => 10, 'on' => 'enterpriseList'],
			['page', 'integer', 'message' => 27037, 'on' => 'enterpriseList'],
			['pageSize', 'integer', 'message' => 27038, 'on' => 'enterpriseList'],
		];
	}
}