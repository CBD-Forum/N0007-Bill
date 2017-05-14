<?php
namespace common\components;

use Yii;
use yii\base\Model;
use yii\helpers\Json;

/**
 * CUrl Model
 * cURL 模型
 * ----------------
 * @version 1.0.0
 * @author Verdient。
 */
class CUrl extends Model
{
	/**
	 * @var private $_response
	 * 响应内容
	 * -----------------------
	 * @author Verident。
	 */
	private $_response = null;

	/**
	 * @var private $_responseCode
	 * 状态码
	 * ---------------------------
	 * @author Verdient。
	 */
	private $_responseCode = null;

	/**
	 * @var private $_options
	 * 参数
	 * ----------------------
	 * @author Verdient。
	 */
	private $_options = [];

	/**
	 * @var private $_curl
	 * cUrl实例
	 * -------------------
	 * @author Verdient。
	 */
	private $_curl = null;

	/**
	 * @var private $_defaultOptions
	 * 默认参数
	 * -----------------------------
	 * @author Verdient。
	 */
	private $_defaultOptions = [
		CURLOPT_USERAGENT => 'Yii2-CUrl-Agent',
		CURLOPT_TIMEOUT => 30,
		CURLOPT_CONNECTTIMEOUT => 30,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => false,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_HTTPHEADER => []
	];

	/**
	 * get(String $url, String $dataType)
	 * 通过get方式获取数据
	 * ------------------------------
	 * @param String $url url地址
	 * @param String $dataType 返回数据格式
	 * ------------------------------------
	 * @return Mixed
	 * @author Verdient。
	 */
	public function get($url, $dataType = null){
		return $this->_httpRequest('GET', $url, $dataType);
	}

	/**
	 * head(String $url)
	 * 通过head方式获取数据
	 * --------------------
	 * @param String $url url地址
	 * --------------------------
	 * @return Mixed
	 * @author Verdient。
	 */
	public function head($url){
		return $this->_httpRequest('HEAD', $url);
	}

	/**
	 * post(String $url, String $dataType)
	 * 通过post方式获取数据
	 * -----------------------------------
	 * @param String $url url地址
	 * @param String $dataType 返回数据格式
	 * ------------------------------------
	 * @return Mixed
	 * @author Verdient。
	 */
	public function post($url, $dataType = null){
		return $this->_httpRequest('POST', $url, $dataType);
	}

	/**
	 * put(String $url, String $dataType)
	 * 通过put方式获取数据
	 * -------------------
	 * @param String $url url地址
	 * @param String $dataType 返回数据格式
	 * ------------------------------------
	 * @return Mixed
	 * @author Verdient。
	 */
	public function put($url, $dataType = null){
		return $this->_httpRequest('PUT', $url, $dataType);
	}

	/**
	 * delete(String $url, String $dataType)
	 * 通过delete方式获取数据
	 * -------------------------------------
	 * @param String $url url地址
	 * @param String $dataType 返回数据格式
	 * ------------------------------------
	 * @return Mixed
	 * @author Verdient。
	 */
	public function delete($url, $dataType = null){
		return $this->_httpRequest('DELETE', $url, $dataType);
	}

	/**
	 * setHeads(Array $headers)
	 * 设置发送的头部信息
	 * ------------------------
	 * @param Array $headers 头部信息
	 * ------------------------------
	 * @return Mixed
	 * @author Verdient。
	 */
	public function setHeads(Array $headers){
		$this->setOption(CURLOPT_HTTPHEADER, $headers);
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
			$this->setOption(CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($data)]);
			return $this->setOption(CURLOPT_POSTFIELDS, $data);
		}
		if(is_callable($callback)){
			$data = call_user_func($callback, $data);
		}
		return $this->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
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
		if($this->_curl !== null){
			@curl_close($this->_curl);
		}
		if(isset($this->_options)){
			$this->_options = array();
		}
		$this->_curl = null;
		$this->_options = [];
		$this->_response = null;
		$this->_responseCode = null;
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
	 * getInfo(String $opt)
	 * 获取连接资源句柄的信息
	 * ----------------------
	 * @param String $opt 选项名称
	 * ---------------------------
	 * @return Object
	 * @author Verdient。
	 */
	public function getInfo($opt = null){
		if($this->_curl !== null && $opt === null){
			return curl_getinfo($this->_curl);
		}else if($this->_curl !== null && $opt !== null){
			return curl_getinfo($this->_curl, $opt);
		}else{
			return [];
		}
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
	 * getResponseCode()
	 * 获取状态码
	 * -----------------
	 * @return Integer
	 * @author Verdient。
	 */
	public function getResponseCode(){
		return $this->_responseCode;
	}

	/**
	 * _httpRequest(String $method, String $url, String $dataType)
	 * http请求
	 * -----------------------------------------------------------
	 * @param String $method 请求方式
	 * @param String $url 请求地址
	 * @param String $dataType 返回数据格式
	 * ------------------------------------
	 * @return Object
	 * @author Verdient。
	 */
	private function _httpRequest($method, $url, $dataType = null){
		$this->setOption(CURLOPT_CUSTOMREQUEST, strtoupper($method));
		if($method === 'HEAD'){
			$this->setOption(CURLOPT_NOBODY, true);
			$this->unsetOption(CURLOPT_WRITEFUNCTION);
		}
		$this->_curl = curl_init($url);
		curl_setopt_array($this->_curl, $this->getOptions());
		$body = curl_exec($this->_curl);
		if($body === false){
			switch(curl_errno($this->_curl)){
				case 7:
					throw new Exception(11001);
					break;
				default:
					throw new Exception(11000);
					break;
			}
		}
		$this->_responseCode = curl_getinfo($this->_curl, CURLINFO_HTTP_CODE);
		$this->_response = $body;
		curl_close($this->_curl);
		if($this->getOption(CURLOPT_CUSTOMREQUEST) === 'HEAD'){
			return true;
		}else{
			$this->_response = strtoupper($dataType) == 'JSON' ? Json::decode($this->_response) : $this->_response;
			return $this->_response;
		}
	}
}