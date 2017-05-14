<?php
namespace common\components\OAuth2;

use Yii;
use common\components\ActiveRecord;
use common\components\Exception;
use common\helpers\DatabaseHelper;
use common\migration\CreateTable;

/**
 * RefreshToken
 * 刷新密钥
 * ------------
 * @version 1.0.0
 * @author Verdient。
 */
class RefreshToken extends ActiveRecord
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
		if(!DatabaseHelper::hasTable('oauth2_refresh_token')){
			CreateTable::OAuth2RefreshToken();
		}
		return '{{oauth2_refresh_token}}';
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
			[['refresh_token', 'user_id', 'expires'], 'required'],
			[['user_id', 'expires'], 'integer'],
			[['refresh_token'], 'string']
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
			'refresh_token' => 'Refresh Token',
			'client_id' => 'Client ID',
			'user_id' => 'User ID',
			'expires' => 'Expires',
		];
	}

	/**
	 * createRefreshToken(Integer $length, [Boolean $deleteOldRefreshToken = false])
	 * 生成RefreshToken
	 * -----------------------------------------------------------------------------
	 * @param Integer $length RefreshToken长度
	 * @param Boolean $deleteOldRefreshToken 是否删除旧的刷新密钥
	 * ----------------------------------------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function createRefreshToken($length, $deleteOldRefreshToken = false){
		$this->refresh_token = Yii::$app->security->generateRandomString($length);
		$this->deleteAll(['<', 'expires', time()]);
		if($deleteOldRefreshToken === true){
			$this->deleteAll(['user_id' => $this->user_id]);
		}
		if(!$this->save()){
			throw new Exception(10022);
		}
		return $this->attributes;
	}

	/**
	 * getRefreshTokenInformationByRefreshToken(String $refreshToken[, Boolean $asArray = false, Array / String $select = []])
	 * 通过刷新密钥获取刷新密钥信息
	 * -----------------------------------------------------------------------------------------------------------------------
	 * @param String $refreshToken 刷新密钥
	 * @param Boolean $asArray 是否返回数组，默认返回对象
	 * @param Array / String $select 选择的字段
	 * --------------------------------------------------
	 * @return Object / Array
	 * @author Verdient。
	 */
	public static function getRefreshTokenInformationByRefreshToken($refreshToken, $asArray = false, $select = []){
		return self::getActiveRecordInformation(['refresh_token' => $refreshToken], $asArray, $select);
	}
}
