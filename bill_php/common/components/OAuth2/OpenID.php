<?php
namespace common\components\OAuth2;

use Yii;
use common\components\ActiveRecord;
use common\components\Exception;
use common\helpers\DatabaseHelper;
use common\migration\CreateTable;

class OpenID extends ActiveRecord
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
		if(!DatabaseHelper::hasTable('oauth2_open_id')){
			CreateTable::OAuth2OpenId();
		}
		return '{{oauth2_open_id}}';
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
			[['user_id', 'open_id'], 'required'],
			[['user_id', 'open_id'], 'unique'],
			['user_id', 'integer'],
			['open_id', 'string'],
		];
	}

	/**
	 * createOpenID()
	 * 生成openID
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	public function createOpenID($length){
		$this->open_id = Yii::$app->security->generateRandomString($length);
		if(!$this->save()){
			return self::getOpenIDInformationByUserID($this->user_id, true);
		}
		return $this->open_id;
	}

	/**
	 * getOpenIDInformationByOpenID(String $openId[, Boolean $asArray = false, Array / String $select = []])
	 * 通过openID获取openID信息
	 * -----------------------------------------------------------------------------------------------------
	 * @param String $openId openID
	 * @param Boolean $asArray 是否返回数组，默认返回对象
	 * @param Array / String $select 选择的字段
	 * --------------------------------------------------
	 * @return Object / Array
	 * @author Verdient。
	 */
	public static function getOpenIDInformationByOpenID($openId, $asArray = false, $select = []){
		return self::getActiveRecordInformation(['open_id' => $openId], $asArray, $select);
	}

	/**
	 * getOpenIDInformationByUserID(String $userId[, Boolean $asArray = false, Array / String $select = []])
	 * 通过userID获取openID信息
	 * -----------------------------------------------------------------------------------------------------
	 * @param String $userId 用户ID
	 * @param Boolean $asArray 是否返回数组，默认返回对象
	 * @param Array / String $select 选择的字段
	 * --------------------------------------------------
	 * @return Object / Array
	 * @author Verdient。
	 */
	public static function getOpenIDInformationByUserID($userId, $asArray = false, $select = []){
		return self::getActiveRecordInformation(['user_id' => $userId], $asArray, $select);
	}
}
