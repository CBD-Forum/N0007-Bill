<?php
namespace common\models;

use Yii;
use yii\helpers\Json;
use common\models\Bill;
use common\components\ActiveRecord;

/**
 * CheckRecord Model
 * 审核记录 模型
 * -----------------
 * @version 1.0.0
 * @author Verdient。
 */
class CheckRecord extends ActiveRecord
{
	/**
	 * @status const STATUS_PASSED
	 * 审核通过
	 * ---------------------------
	 * @author Verdient。
	 */
	const STATUS_PASSED = 1;

	/**
	 * @status const STATUS_FAILED
	 * 审核失败
	 * ---------------------------
	 * @author Verdient。
	 */
	const STATUS_FAILED = 2;

	/**
	 * @map const STATUS_MAP
	 * 状态映射关系
	 * ---------------------
	 * @author Verdient。
	 */
	const STATUS_MAP = "return [
		common\models\CheckRecord::STATUS_PASSED => '通过',
		common\models\CheckRecord::STATUS_FAILED => '未通过'
	];";

	/**
	 * validateId(String $attribute, Mixed $params)
	 * 验证记录ID
	 * --------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateId($attribute, $params){
		if(!$this->hasErrors()){
			$query = self::find()->where(['id' => $this->id]);
			if(isset($params['filter']) && is_array($params['filter'])){
				if(ArrayHelper::isAssociative($params['filter'])){
					$query->andFilterWhere($params['filter']);
				}else{
					foreach($params['filter'] as $key => $value){
						$query->andFilterWhere($value);
					}
				}
			}
			if(!$this->instance = $query->one()){
				$this->addError($attribute, 20027);
			}
		}
	}

	/**
	 * typeFilter()
	 * 类型过滤器
	 * ------------
	 * @return String
	 * @author yuan
	 */
	public function typeFilter(){
		return function ($value) {
			$map = ['银行承兑' => Bill::TYPE_BANK_DRAFT, '商业承兑' => Bill::TYPE_COMMERCIAL_DRAFT];
			return empty($value) ? [Bill::TYPE_BANK_DRAFT, Bill::TYPE_COMMERCIAL_DRAFT] : $map[$value];
		};
	}

	/**
	 * statusFilter()
	 * 状态过滤器
	 * --------------
	 * @return String
	 * @author yuan
	 */
	public function statusFilter(){
		return function($value) {
			return empty($value) ? [self::STATUS_FAILED, self::STATUS_PASSED] : $value;
		};
	}
}