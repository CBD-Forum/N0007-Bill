<?php
namespace common\components\OAuth2;

use Yii;
use common\components\ActiveRecord;
use common\components\Exception;
use common\helpers\DatabaseHelper;
use common\migration\CreateTable;

/**
 * AuthorizationCode Model
 * 授权码模型
 * ------------------------
 * @version 1.0.0
 * @author Verdient。
 */
class AuthorizationCode extends ActiveRecord
{

	/**
	 * tableName()
	 * 设置数据表名
	 * ------------
	 * @inheritdoc
	 * -----------
	 * @return String
	 * @author Verdient。
	 */
	public static function tableName(){
		if(!DatabaseHelper::hasTable('oauth2_authorization_code')){
			CreateTable::OAuth2AuthorizationCode();
		}
		return '{{oauth2_authorization_code}}';
	}

	/**
	 * rules()
	 * 校验规则
	 * --------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public function rules(){
		return [
			[['authorization_code', 'user_id', 'expires'], 'required'],
			['user_id', 'integer'],
			['authorization_code', 'string', 'max' => 40],
		];
	}

	/**
	 * attributeLabels()
	 * 设置别名
	 * -----------------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public function attributeLabels(){
		return [
			'authorization_code' => 'Authorization Code',
			'client_id' => 'Client ID',
			'user_id' => 'User ID'
		];
	}

	/**
	 * createAuthorizationCode(Integer $length, [Boolean $deleteOldAuthorizationCode = false])
	 * 生成AuthorizationCode
	 * ---------------------------------------------------------------------------------------
	 * @param AuthorizationCode长度
	 * @param Boolean $deleteOldAuthorizationCode 是否删除旧的authorizationCode
	 * ------------------------------------------------------------------------
	 * @return Object
	 * @author Verdient。
	 */
	public function createAuthorizationCode($length, $deleteOldAuthorizationCode = false){
		$this->deleteAll(['<', 'expires', time()]);
		if($deleteOldAuthorizationCode === true){
			$this->deleteAll(['user_id' => $this->user_id]);
		}
		$this->authorization_code = Yii::$app->security->generateRandomString($length);
		if (!$this->save()) {
			throw new Exception(10020);
		}
		return $this->attributes;
	}

	/**
	 * getAuthorizationCodeInformationByAuthorizationCode(String $authorizationCode[, Boolean $asArray = false, Array / String $select = []])
	 * 通过授权码获取授权码信息
	 * --------------------------------------------------------------------------------------------------------------------------------------
	 * @param String $authorizationCode 授权码
	 * @param Boolean $asArray 是否返回数组，默认返回对象
	 * @param Array / String $select 选择的字段
	 * --------------------------------------------------
	 * @return Object / Array
	 * @author Verdient。
	 */
	public static function getAuthorizationCodeInformationByAuthorizationCode($authorizationCode, $asArray = false, $select = []){
		return self::getActiveRecordInformation(['authorization_code' => $authorizationCode], $asArray, $select);
	}
}
