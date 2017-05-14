<?php
namespace common\components\OAuth2;

use Yii;
use yii\helpers\Json;
use common\components\ActiveRecord;
use common\components\Exception;
use common\helpers\DatabaseHelper;
use common\migration\CreateTable;

/**
 * Log Model
 * 日志模型
 * ---------
 * @version 1.0.0
 * @author Verdient。
 */
class Log extends ActiveRecord
{
	/**
	 * tableName()
	 * 设置表名称
	 * -----------
	 * @inheritdoc
	 * -----------
	 * @return String
	 * @author Verdient。
	 */
	public static function tableName(){
		$tableName = 'oauth2_log_' . date('Y_m');
		if(!DatabaseHelper::hasTable($tableName)){
			CreateTable::OAuth2Log($tableName);
		}
		return '{{' . $tableName . '}}';
	}

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
			[['oauth2_scenario', 'is_oauth2_request', 'is_oauth2_authorized', 'route', 'url', 'user_agent', 'ip'], 'required'],
			[['oauth2_scenario', 'route', 'url', 'user_agent', 'ip', 'authorization_code', 'access_token', 'refresh_token', 'username'], 'string', 'min' => 0, 'max' => 255],
			[['is_oauth2_request', 'is_oauth2_authorized'], 'boolean', 'trueValue' => true, 'falseValue' => false, 'strict' => true],
			['user_id', 'integer'],
			['gets', 'filter', 'filter' => self::getsFilter()],
			['posts', 'filter', 'filter' => self::postsFilter()],
		];
	}

	/**
	 * getsFilter()
	 * get数据过滤器
	 * -------------
	 * @return Array
	 * @author Verdient。
	 */
	public function getsFilter(){
		return function($value){
			return empty($value) ? null : Json::encode($value);
		};
	}

	/**
	 * postsFilter()
	 * post数据过滤器
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	public function postsFilter(){
		return function($value){
			if(isset($value['password'])){
				unset($value['password']);
			}
			return empty($value) ? null : Json::encode($value);
		};
	}

	/**
	 * addLog($values)
	 * 添加日志
	 * ---------------
	 * @param Object $values 日志内容
	 * ------------------------------
	 * @author Verdient。
	 */
	public static function addLog($values){
		$model = new self;
		$model->load(['Log' => $values]);
		return $model->save();
	}
}