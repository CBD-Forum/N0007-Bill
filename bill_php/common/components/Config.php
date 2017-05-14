<?php
namespace common\components;

use Yii;
use common\components\ActiveRecord;
use common\components\Exception;

/**
 * Config Model
 * 配置模型
 * ------------
 * @version 1.0.0
 * @author Verdient。
 */
class Config extends ActiveRecord
{
	/**
	 * @var public $enableConfigs
	 * 允许的配置
	 * --------------------------
	 * @author Verdient。
	 */
	public $enableConfigs = [
		'annualized_rate_min', 'annualized_rate_max', 'listing_reserve_day', 'authentication_retry_max'
	];

	/**
	 * @var public $annualized_rate_min
	 * 贴现率上限
	 * --------------------------------
	 * @author Verdient。
	 */
	public $annualized_rate_min;

	/**
	 * @var public $annualized_rate_max
	 * 贴现率上限
	 * --------------------------------
	 * @author Verdient。
	 */
	public $annualized_rate_max;

	/**
	 * @var public $authentication_retry_max
	 * 认证失败最大重试次数
	 * -------------------------------------
	 * @author Verdient。
	 */
	public $authentication_retry_max;

	/**
	 * @var public $listing_reserve_day
	 * 可挂牌的保留天数
	 * --------------------------------
	 * @author Verdient。
	 */
	public $listing_reserve_day;

	/**
	 * @var protected $config
	 * 配置信息
	 * -------------------
	 * @author Verdient。
	 */
	protected $_configs;

	/**
	 * getConfigs()
	 * 获取配置列表
	 * -----------------
	 * @return Array
	 * @author Verdient。
	 */
	public function getConfigs($asArray = true){
		if(!$this->_configs){
			$query = self::find()->select(['name', 'value']);
			if($asArray === true){
				$query->asArray();
			}
			if($result = $query->all()){
				foreach($result as $key => $value){
					if($asArray === true){
						$this->_configs[$value['name']] = $value['value'];
					}else{
						$this->_configs[$value['name']] = $value;
					}
				}
			}
		}
		return $this->_configs;
	}

	/**
	 * getConfig([String $name = null])
	 * 获取配置内容
	 * -------------------------------------
	 * @param String $name 配置名称
	 * ----------------------------
	 * @return Object
	 * @author Verdient。
	 */
	public function getConfig($name = null){
		if($name){
			return self::find()->select(['name', 'value'])->where(['name' => $name])->one();
		}
		return $this->getConfigs(false);
	}

	/**
	 * setConfig(String $name, String $value)
	 * 设置配置内容
	 * -------------------------------------------
	 * @param String $name 配置名称
	 * @param String $value 配置内容
	 * -----------------------------
	 * @return Object
	 * @author Verdient。
	 */
	public function setConfig($name, $value){
		if(!$result  = self::findOne(['name' => $name], false, ['name', 'value'])){
			$result = new self;
			$result->name = $name;
		}
		$result->value = $value;
		$result->save(false);
	}

	/**
	 * setConfigs(Array $configs)
	 * 批量设置配置内容
	 * --------------------------
	 * @param Array $configs 配置数组
	 * ------------------------------
	 * @return Object
	 * @author Verdient。
	 */
	public function setConfigs(Array $configs){
		foreach($configs as $key => $value){
			if(!in_array($key, $this->enableConfigs)){
				throw new Exception(33008);
			}
		}
		foreach($configs as $key => $value){
			$this->setConfig($key, $value);
		}
	}
}