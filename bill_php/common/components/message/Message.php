<?php
namespace common\components\message;

use Yii;
use yii\base\Model;

/**
 * Message Model
 * 消息模型
 * -------------
 * @version 1.0.0
 * @author Verident。
 */
class Message extends Model
{
	/**
	 * @sms_tplsign const SMS_TPLSIGN_DEFAULT
	 * 默认tplsign
	 * --------------------------------------
	 * @author Verdient。
	 */
	const SMS_TPLSIGN_DEFAULT = 4;

	/**
	 * @var public $key
	 * 应用Key
	 * ----------------
	 * @method Config
	 * @author Verdient。
	 */
	public $key = '';

	/**
	 * @var public $secret
	 * 应用密钥
	 * -------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $secret = '';

	/**
	 * @var public $protocol
	 * 访问协议
	 * ---------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $protocol = '';

	/**
	 * @var public $enableProtocols
	 * 允许的协议
	 * ----------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $enableProtocols = ['http', 'https'];

	/**
	 * @var public $host
	 * 域名
	 * -----------------
	 * @method Config
	 * @author Verdient。
	 */
	public $host = '';

	/**
	 * @var public $port
	 * 端口
	 * -----------------
	 * @method Config
	 * @author Verdient。
	 */
	public $port = '';

	/**
	 * @var public $routes
	 * 路由
	 * -------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $routes = [];

	/**
	 * @var public $enableSMS
	 * 允许短信
	 * ----------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $enableSMS = true;

	/**
	 * @var public $enableEmail
	 * 允许电子邮件
	 * ------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $enableEmail = true;

	/**
	 * init()
	 * 初始化
	 * -----------------
	 * @method Config
	 * @author Verdient。
	 */
	public function init(){
		parent::init();
		if(!$this->protocol){
			$this->protocol = Yii::$app->request->isSecureConnection ? 'https' : 'http';
		}
		if(!$this->host){
			$this->host = explode(':', $_SERVER['HTTP_HOST'])[0];
		}
		foreach ($this->routes as $key => $value) {
			$this->routes[$key] = $this->protocol . '://' . $this->host . (($this->port && $this->port != 80) ? (':' . $this->port) : '') . $value;
		}
	}

	/**
	 * sendSMS(String $mobile, String $codeType, String $tplId[, String $vparam = null, String $tplSign = 'SMS_TPLSIGN_DEFAULT', String $country = 'CN'])
	 * 发送短信
	 * --------------------------------------------------------------------------------------------------------------------------------------------------
	 * @param String $mobile 手机号码
	 * @param String $codeType 类型
	 * @param String $tplId 模板编号
	 * @param String $vparam 变量字符串
	 * @param String $tplSign 模板标识
	 * @param String $country 号码所属国家
	 * -----------------------------------
 	 * @return Boolean
	 * @author Verdient。
	 */
	public function sendSMS($mobile, $codeType, $tplId, $vparam = null, $tplSign = 'SMS_TPLSIGN_DEFAULT', $country = 'CN'){
		if($this->enableSMS !== true){
			return true;
		}
		eval('$tplSign = self::' . $tplSign . ';');
		try{
			$ip = Yii::$app->request->userIP;
		}catch (\Exception $e) {
			$ip = '127.0.0.1';
		}
		$data = [
			'appkey' => $this->key,
			'token'	=> $this->_getAccessToken(),
			'country' => $country,
			'mobile' => $mobile,
			'codetype' => $codeType,
			'sign'=> md5($this->key . $this->secret . time()),
			'tplid'	=> $tplId,
			'tplsign' => $tplSign,
			'vparam' => urlencode(trim($vparam)),
			'ip' => $ip,
		];
		$result = $this->_send($data);
		$data = [
			'type' => Log::TYPE_SEND_SMS,
			'scenario' => $codeType,
			'account' => $mobile,
			'posts' => json_encode($data)
		];
		if(isset($result['code']) && $result['code'] == 200){
			$data['status'] = Log::STATUS_SUCCESS;
			$data['datas'] = json_encode($result);
			$data['sid'] = $result['data']['sid'];
			$data['operator'] = $result['data']['yys'];
			Log::addLog($data);
			return true;
		}
		$data['datas'] = json_encode($result);
		$data['status'] = Log::STATUS_FAILED;
		Log::addLog($data);
		return false;
	}

	/**
	 * validateCaptcha(String $mobile, String $codeType, String $code[, String $country = 'CN'])
	 * 校验验证码
	 * -----------------------------------------------------------------------------------------
	 * @param String $mobile 手机号码
	 * @param String $codeType 类型
	 * @param String $code 验证码
	 * @param String $country 所属国家
	 * -------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function validateCaptcha($mobile, $codeType, $code, $country = 'CN'){
		if($this->enableSMS !== true){
			return true;
		}
		$data = [
			'appkey' => $this->key,
			'token' => $this->_getAccessToken(),
			'mobile' => $mobile,
			't' => 'sms',
			'codetype' => $codeType,
			'code' => (string)$code,
			'country' => $country
		];
		$result = $this->_send($data);
		$data = [
			'type' => Log::TYPE_VALIDATE_SMS_CAPTCHA,
			'scenario' => $codeType,
			'account' => $mobile,
			'posts' => json_encode($data)
		];
		if(isset($result['code']) && $result['code'] == 200){
			$data['status'] = Log::STATUS_SUCCESS;
			$data['datas'] = json_encode($result);
			Log::addLog($data);
			return true;
		}
		$data['datas'] = json_encode($result);
		$data['status'] = Log::STATUS_FAILED;
		Log::addLog($data);
		return false;
	}

	/**
	 * _getAccessToken()
	 * 获取密钥
	 * -----------------
 	 * @return Boolean
	 * @author Verdient。
	 */
	protected function _getAccessToken(){
		if(!$token = Yii::$app->cache->get('message-token')){
			$data = [
				'appkey' => $this->key,
				'appsecret' => $this->secret,
			];
			$result = $this->_send($data);
			if(isset($result['message']) && $result['message'] == "succ"){
				$token = $result['data']['token'];
				Yii::$app->cache->set('message-token', $token, $result['data']['expiretime'] - time() - 5);
			}else{
				$token = false;
			}
		}
		return $token;
	}

	/**
	 * _send()
	 * 发送
	 * -------
 	 * @return Boolean
	 * @author Verdient。
	 */
	protected function _send(Array $data){
		Yii::$app->cUrl->reset();
		Yii::$app->cUrl->setHeads(['Authorization:' . base64_encode($this->key . ':' . time())]);
		Yii::$app->cUrl->setData($data);
		return Yii::$app->cUrl->post($this->routes[debug_backtrace()[1]['function']], 'JSON');
	}
}