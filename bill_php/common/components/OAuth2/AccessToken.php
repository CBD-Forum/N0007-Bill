<?php
namespace common\components\OAuth2;

use Yii;
use yii\helpers\ArrayHelper;
use common\components\ActiveRecord;
use common\components\Exception;
use common\helpers\DatabaseHelper;
use common\migration\CreateTable;

/**
 * AccessToken Model
 * 授权密钥模型
 * -----------------
 * @version 1.0.0
 * @author Verdient。
 */
class AccessToken extends ActiveRecord
{

	/**
	 * tableName()
	 * 设置数据表名
	 * ------------
	 * @return String
	 * @author Verdient。
	 */
	public static function tableName(){
		if(!DatabaseHelper::hasTable('oauth2_access_token')){
			CreateTable::OAuth2AccessToken();
		}
		return '{{oauth2_access_token}}';
	}

	/**
	 * rules()
	 * 校验规则
	 * --------
	 * @return Array
	 * @author Verdient。
	 */
	public function rules(){
		return [
			[['access_token', 'created_by', 'user_id', 'expires'], 'required'],
			[['user_id', 'created_by', 'expires'], 'integer'],
			[['access_token'], 'string'],
			[['access_token'], 'unique']
		];
	}

	/**
	 * attributeLabels()
	 * 设置别名
	 * -----------------
	 * @return Array
	 * @author Verdient。
	 */
	public function attributeLabels(){
		return [
			'access_token' => 'Access Token',
			'created_by' => 'Create By',
			'client_id' => 'Client ID',
			'user_id' => 'User ID',
			'expires' => 'Expires',
		];
	}

	/**
	 * createAccessToken(Integer $length, [Boolean $deleteOldAccessToken = false])
	 * 生成AccessToken
	 * ---------------------------------------------------------------------------
	 * @param Integer $length AccessToken长度
	 * @param Boolean $deleteOldAuthorizationCode 是否删除旧的accessToken
	 * ------------------------------------------------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function createAccessToken($length, $deleteOldAccessToken = false){
		$this->deleteAll(['<', 'expires', time()]);
		if($deleteOldAccessToken){
			$this->deleteAll(['=', 'user_id', $this->user_id]);
		}
		$this->access_token = Yii::$app->security->generateRandomString($length);
		if (!$this->save()) {
			throw new Exception(10021);
		}
		return $this;
	}

	/**
	 * getAccessTokenInformationByAccessToken(String $accessToken[, Boolean $asArray = false])
	 * 通过accessToken获取授权密钥信息
	 * ---------------------------------------------------------------------------------------
	 * @param String $accessToken 授权密钥
	 * @param Boolean $asArray 是否返回数组，默认返回对象
	 * --------------------------------------------------
	 * @return Object / Array
	 * @author Verdient。
	 */
	public static function getAccessTokenInformationByAccessToken($accessToken, $asArray = false, $select = []){
		$model = self::find()->select($select)->where(['access_token' => $accessToken]);
		if($asArray === true){
			$model->asArray();
		}
		return $model->One();
	}
}
