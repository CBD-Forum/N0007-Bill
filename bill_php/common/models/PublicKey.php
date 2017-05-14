<?php
namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\components\ActiveRecord;

/**
 * PublicKey Model
 * 公钥 模型
 * ---------------
 * @version 1.0.0
 * @author Verdient。
 */
class PublicKey extends ActiveRecord
{
	/**
	 * @status const STATUS_WAITING_SIGN_UP
	 * 等待区块链创建用户
	 * ------------------------------------
	 * @author Verdient。
	 */
	const STATUS_WAITING_SIGN_UP = '0';

	/**
	 * @status const STATUS_SIGN_UP_FAILED
	 * 区块链创建用户失败
	 * -----------------------------------
	 * @author Verdient。
	 */
	const STATUS_SIGN_UP_FAILED = 1;

	/**
	 * @status const STATUS_REGULAR
	 * 正常
	 * ----------------------------
	 * @author Verdient。
	 */
	const STATUS_REGULAR = 2;

	/**
	 * @status const STATUS_TRANSFER_FAILED
	 * 区块链资产转移失败
	 * ------------------------------------
	 * @author Verdient。
	 */
	const STATUS_TRANSFER_FAILED = 3;

	/**
	 * @status const STATUS_WAITING_CHANGE_PASSWORD
	 * 等待修改密码
	 * --------------------------------------------
	 * @author Verdient。
	 */
	const STATUS_WAITING_CHANGE_PASSWORD = 4;

	/**
	 * @status const RANDOM_LENGTH
	 * 随机码长度
	 * ---------------------------
	 * @author Verdient。
	 */
	const RANDOM_LENGTH = 8;

	/**
	 * @map const STATUS_MAP
	 * 状态映射关系
	 * ---------------------
	 * @author Verdient。
	 */
	const STATUS_MAP = "return [
		common\models\PublicKey::STATUS_WAITING_SIGN_UP => '待激活',
		common\models\PublicKey::STATUS_SIGN_UP_FAILED => '激活失败',
		common\models\PublicKey::STATUS_REGULAR => '正常',
		common\models\PublicKey::STATUS_TRANSFER_FAILED => '资产转移失败',
		common\models\PublicKey::STATUS_WAITING_CHANGE_PASSWORD => '待修改密码',
	];";

	/**
	 * @var protected $_address
	 * 公钥地址
	 * ------------------------
	 * @author Verdient。
	 */
	protected $_address;

	/**
	 * validateUserID(String $attribute, Mixed $params)
	 * 验证用户ID
	 * ------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateUserID($attribute, $params){
		if(!$this->hasErrors()){
			$query = self::find()->where(['user_id' => $this->user_id]);
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
				$this->addError($attribute, 35003);
			}
		}
	}

	/**
	 * getAddress()
	 * 获取公钥地址
	 * ------------
	 * @return String
	 * @author Verdient。
	 */
	public function getAddress(){
		if(!$this->_address){
			if($this->status == self::STATUS_REGULAR){
				$this->_address = $this->public_key;
			}
		}
		return $this->_address;
	}

	/**
	 * setAddress(String $address)
	 * 设置公钥地址
	 * ---------------------------
	 * @param String $address 公钥地址
	 * -------------------------------
	 * @author Verdient。
	 */
	public function setAddress($value){
		$this->public_key = $value;
	}

	/**
	 * getUser()
	 * 关联用户信息
	 * ------------
	 * @return Object
	 * @author Verdient。
	 */
	public function getUser(){
		return $this->hasOne(User::className(), ['id' => 'user_id'])->with('tradeCompany');
	}
}
