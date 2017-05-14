<?php
namespace common\components;

use Yii;
use yii\base\Model;
use yii\helpers\Json;

/**
 * SOAP Model
 * cURL 模型
 * ----------------
 * @version 1.0.0
 * @author Verdient。
 */
class SOAP extends Model
{
	/**
	 * const VAR_ENCODING
	 * 变量编码
	 * ------------------
	 * @author Verdient。
	 */
	const VAR_ENCODING = 'varEncoding';

	/**
	 * const HEADER_NAME
	 * 头名称
	 * -----------------
	 * @author Verdient。
	 */
	const HEADER_NAME = 'headerName';

	/**
	 * const HEADER_MUSTUNDERSTAND
	 * 头必须理解
	 * ---------------------------
	 * @author Verdient。
	 */
	const HEADER_MUSTUNDERSTAND = 'headerMustunderstand';

	/**
	 * const HEADER_MUSTUNDERSTAND
	 * 消息接收者
	 * ---------------------------
	 * @author Verdient。
	 */
	const HEADER_ACTOR = 'headerActor';

	/**
	 * const VAR_DATA
	 * 变量数据
	 * --------------
	 * @author Verdient。
	 */
	const VAR_DATA = 'varData';

	/**
	 * const DATA
	 * 数据
	 * ----------
	 * @author Verdient。
	 */
	const DATA = 'data';

	/**
	 * @var private $_response
	 * 响应内容
	 * -----------------------
	 * @author Verident。
	 */
	private $_response = null;

	/**
	 * @var private $_options
	 * 参数
	 * ----------------------
	 * @author Verdient。
	 */
	private $_options = [];

	/**
	 * @var private $_soap
	 * SOAP实例
	 * -------------------
	 * @author Verdient。
	 */
	private $_soap = null;

	/**
	 * @var private $_defaultOptions
	 * 默认参数
	 * -----------------------------
	 * @author Verdient。
	 */
	private $_defaultOptions = [
		self::VAR_ENCODING => SOAP_ENC_OBJECT,
		self::HEADER_NAME => 'SoapInterceptor',
		self::HEADER_MUSTUNDERSTAND => false,
		self::HEADER_ACTOR => SOAP_ACTOR_NEXT,
	];

	/**
	 * fetch(String $method, String $url, String $dataType)
	 * 获取数据
	 * ----------------------------------------------------
	 * @param String $method 请求方法
	 * @param String $url url地址
	 * @param String $dataType 返回数据格式
	 * ------------------------------------
	 * @return Mixed
	 * @author Verdient。
	 */
	public function fetch($method, $url, $dataType = null){
		return $this->_request($method, $url, $dataType);
	}

	/**
	 * setVarData(Array $varData)
	 * 设置发送的变量
	 * --------------------------
	 * @param Array $varData 变量数据
	 * ------------------------------
	 * @return Mixed
	 * @author Verdient。
	 */
	public function setVarData(Array $varData){
		return $this->setOption(self::VAR_DATA, $varData);
	}

	/**
	 * setData(Array $data, Callable / String $callback = null)
	 * 设置发送的数据
	 * --------------------------------------------------------
	 * @param Array $data 发送的数据
	 * @param Callable / String $callback 回调函数
	 * -------------------------------------------
	 * @return Object
	 * @author Verdient。
	 */
	public function setData(Array $data, $callback = null){
		if(strtoupper($callback) == 'JSON'){
			$data = Json::encode($data);
		}
		if(is_callable($callback)){
			$data = call_user_func($callback, $data);
		}
		return $this->setOption(self::DATA, $data);
	}

	/**
	 * setOption(String $key, String $value)
	 * 设置选项
	 * -------------------------------------
	 * @param String $key 选项名称
	 * @param String $value 选项内容
	 * -----------------------------
	 * @return Object
	 * @author Verdient。
	 */
	public function setOption($key, $value){
		$this->_options[$key] = $value;
		return $this;
	}

	/**
	 * setOptions(Array $options)
	 * 批量设置选项
	 * --------------------------
	 * @param String $options 选项集合
	 * -------------------------------
	 * @return Object
	 * @author Verdient。
	 */
	public function setOptions($options){
		foreach($options as $key => $value){
			$this->setOption($key, $value);
		}
		return $this;
	}

	/**
	 * unsetOption(String $key)
	 * 删除选项
	 * ------------------------
	 * @param String $key 选项名称
	 * --------------------------
	 * @return Object
	 * @author Verdient。
	 */
	public function unsetOption($key){
		if(isset($this->_options[$key])){
			unset($this->_options[$key]);
		}
		return $this;
	}

	/**
	 * resetOptions()
	 * 重置选项
	 * --------------
	 * @return Object
	 * @author Verdient。
	 */
	public function resetOptions(){
		if (isset($this->_options)) {
			$this->_options = [];
		}
		return $this;
	}

	/**
	 * reset()
	 * 重置
	 * -------
	 * @return Object
	 * @author Verdient。
	 */
	public function reset(){
		if($this->_soap !== null){
			@curl_close($this->_soap);
		}
		if(isset($this->_options)){
			$this->_options = array();
		}
		$this->_soap = null;
		$this->_options = [];
		$this->_response = null;
		return $this;
	}

	/**
	 * getOption(String $key)
	 * 获取选项内容
	 * ----------------------
	 * @param String $key 选项名称
	 * ---------------------------
	 * @return Object
	 * @author Verdient。
	 */
	public function getOption($key){
		$mergesOptions = $this->getOptions();
		return isset($mergesOptions[$key]) ? $mergesOptions[$key] : false;
	}

	/**
	 * getOptions()
	 * 获取所有的选项内容
	 * ------------------
	 * @return Object
	 * @author Verdient。
	 */
	public function getOptions(){
		return $this->_options + $this->_defaultOptions;
	}

	/**
	 * getResponse()
	 * 获取响应内容
	 * -------------
	 * @return Mixed
	 * @author Verdient。
	 */
	public function getResponse(){
		return $this->_response;
	}

	/**
	 * _request(String $method, String $url, String $dataType)
	 * 请求
	 * -------------------------------------------------------
	 * @param String $method 请求方法
	 * @param String $url 请求地址
	 * @param String $dataType 返回数据格式
	 * ------------------------------------
	 * @return Object
	 * @author Verdient。
	 */
	private function _request($method, $url, $dataType = null){
		$options = $this->getOptions();
		try{
			$this->_soap = new \SoapClient($url);
			$var = $options[self::VAR_DATA] ? new \SoapVar($options[self::VAR_DATA], $options[self::VAR_ENCODING]) : [];
			$this->_soap->__setSoapHeaders(new \SoapHeader($url, $options[self::HEADER_NAME], $var, $options[self::HEADER_MUSTUNDERSTAND], $options[self::HEADER_ACTOR]));
			$this->_response = $this->_soap->$method($options[self::DATA]);
			if(strtoupper($dataType) == 'JSON'){
				try{
					$this->_response = Json::decode($this->_response);
				}catch(\Exception $e){
				}
			Yii::trace(['method' => $method, 'url' => $url, 'options' => $options, 'response' => $this->_response], __METHOD__);
			}
		}catch(\SoapFault $e){
			throw new Exception(29000, $e->faultstring);
		}
		return $this->_response;
	}
}