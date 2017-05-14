<?php
namespace common\models;

use Yii;
use common\behaviors\ExceptionBehavior;
use common\components\ActiveRecord;
use common\helpers\ValidateHelper;

/**
 * TradeCompany Model
 * 企业模型
 * ------------------
 * @version 1.0.0
 * @author Verdient。
 */
class TradeCompany extends ActiveRecord
{
	/**
	 * @status const STATUS_CHECK_PENDING
	 * 待审核
	 * ----------------------------------
	 * @author Verdient。
	 */
	const STATUS_CHECK_PENDING = '0';

	/**
	 * @status const STATUS_WAITING_CREATE
	 * 审核通过
	 * -----------------------------------
	 * @author Verdient。
	 */
	const STATUS_WAITING_CREATE = 1;

	/**
	 * @status const STATUS_CHECK_FAILED
	 * 审核失败
	 * ---------------------------------
	 * @author Verdient。
	 */
	const STATUS_CHECK_FAILED = 100;

	/**
	 * getDb()
	 * 获取数据库配置
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	public static function getDb(){
		return Yii::$app->db2;
	}

	/**
	 * tableName()
	 * 设置表名
	 * ----------
	 * @return String
	 * @author Verdient。
	 */
	public static function tableName(){
		return '{{t_trade_company}}';
	}


	/**
	 * behaviors()
	 * 行为设置
	 * -----------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public function behaviors(){
		return [
			'ExceptionBehavior' => ExceptionBehavior::className()
		];
	}

	/**
	 * validateAddress(String $attribute, Mixed $params)
	 * 验证公钥
	 * -------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateAddress($attribute, $params){
		if(!$this->hasErrors()){
			if(!$this->instance = PublicKey::find()->select(['user_id', 'status'])->where(['public_key' => $this->address])->asArray()->one()){
				$this->addError($attribute, 27048);
			}else if($this->instance['status'] != PublicKey::STATUS_REGULAR){
				$this->addError($attribute, 27049);
			}else{
				if(!$this->instance = User::find()->select('name')->where(['id' => $this->instance['user_id']])->asArray()->one()){
					$this->addError($attribute, 27050);
				}else if(empty($this->instance['name'])){
					$this->addError($attribute, 27050);
				}
			}
		}
	}

	/**
	 * validateId(String $attribute, Mixed $params)
	 * 验证企业ID
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
				$this->addError($attribute, 27054);
			}
		}
	}

	/**
	 * sortFilter()
	 * 排序过滤器
	 * ------------
	 * @return function
	 * @author Verdient。
	 */
	public function sortFilter(){
		return function($value){
			if($value){
				return str_replace('-', ' ', $value);
			}
			return false;
		};
	}

	/**
	 * getEnterprise()
	 * 关联企业信息
	 * ---------------
	 * @return Object
	 * @author Verdient。
	 */
	public function getEnterprise(){
		return $this->hasOne(Enterprise::className(), ['enterprise_id' => 'id']);
	}
}