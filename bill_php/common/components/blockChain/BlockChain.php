<?php
namespace common\components\blockChain;

use Yii;
use yii\base\Model;

/**
 * BlockChain Model
 * 区块链 模型
 * ----------------
 * @version 1.0.0
 * @author Verdient。
 */
class BlockChain extends Model
{
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
	 * @var public $beforeSend
	 * 发送前的准备
	 * -----------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $beforeSend = null;

	/**
	 * init()
	 * 初始化
	 * ------
	 * @param String $publicKey 公钥
	 * -----------------------------
	 * @return Array / false
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
			$this->routes[$key] = $this->protocol . '://' . $this->host . ($this->port ? (':' . $this->port) : '') . $value;
		}
	}

	/**
	 * getPublicKey(String $password, String $random)
	 * 获取公钥
	 * ----------------------------------------------
	 * @param String $password 密码
	 * @param String $random 随机数
	 * ----------------------------
	 * @return String
	 * @author Verdient。
	 */
	public function getPublicKey($password, $random){
		$result = $this->_send(['password' => $password, 'random' => $random]);
		return isset($result['statusCode']) && $result['statusCode'] == 200 ? $result['data']['publicKey'] : false;
	}

	/**
	 * getKeys(String $password, String $random)
	 * 获取公私钥
	 * -----------------------------------------
	 * @param String $password 密码
	 * @param String $random 随机数
	 * ----------------------------
	 * @return String
	 * @author Verdient。
	 */
	public function getKeys($password, $random){
		$result = $this->_send(['password' => $password, 'random' => $random]);
		return isset($result['statusCode']) && $result['statusCode'] == 200 ? ['publicKey' => $result['data']['publicKey'], 'privateKey' => $result['data']['privateKey']] : false;
	}

	/**
	 * getBill(String $signature)
	 * 获取票据
	 * --------------------------
	 * @param String $signature 签名字符串
	 * -----------------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function getBill($signature){
		$result = $this->_send(['signature' => $signature]);
		return isset($result['statusCode']) && $result['statusCode'] == 200 ? $result : false;
	}

	/**
	 * getBalance(String $address)
	 * 获取余额
	 * ---------------------------
	 * @param String $address 公钥地址
	 * -------------------------------
	 * @return Array / Boolean
	 * @author Verdient。
	 */
	public function getBalance($address){
		$result = $this->_send(['userAddr' => $address]);
		if(isset($result['statusCode']) && $result['statusCode'] == 200){
			if(!isset($result['data']['rmb'])){
				$result['data']['rmb'] = 0;
			}
			if(!isset($result['data']['frozen'])){
				$result['data']['frozen'] = 0;
			}
			$data['available'] = $result['data']['rmb'] / 100;
			$data['frozen'] = $result['data']['frozen'] / 100;
			$data['total'] = ($result['data']['rmb'] + $result['data']['frozen']) / 100;
		}
		return isset($data) ? $data : false;
	}

	/**
	 * cashIn(String $userAddress, Integer $userId, Float $amount)
	 * 充值
	 * -----------------------------------------------------------
	 * @param String $userAddress 操作人公钥
	 * @param Integer $userId 用户ID
	 * @param Float $amount 充值金额
	 * -------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function cashIn($userAddress, $userId, $amount){
		$result = $this->_send([
			'uid' => $userAddress,
			'userAddr' => $userAddress,
			'userId' => $userId,
			'amount' => $amount
		]);
		return isset($result['statusCode']) && $result['statusCode'] == 200;
	}

	/**
	 * cashOut(String $signature, String $instructionId, String $userAddress, Integer $bankCard, Integer $userId, Float $amount)
	 * 提款
	 * -------------------------------------------------------------------------------------------------------------------------
	 * @param String $signature 签名字符串
	 * @param String $instructionId 指令ID
	 * @param String $userAddress 操作人公钥
	 * @param Integer $bankCard 银行卡号
	 * @param Integer $userId 用户ID
	 * @param Float $amount 提款金额
	 * -------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function cashOut($signature, $instructionId, $userAddress, $bankCard, $userId, $amount){
		$result = $this->_send([
			'signature' => $signature,
			'instructionId' => $instructionId,
			'uid' => $userAddress,
			'bankAddr' => $bankCard,
			'userId' => $userId,
			'amount' => $amount
		]);
		return isset($result['statusCode']) && $result['statusCode'] == 200;
	}

	/**
	 * createBill(String $signature, String $instructionId, Integer $userAddress, Integer $billId, String $type, String $drawer, String $acceptor, String $expiration, String $amount, Integer $uid, String $owner, String $issue_at, String $userInfo)
	 * 创建票据
	 * -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * @param String $signature 签名字符串
	 * @param String $instructionId 指令ID
	 * @param String $userAddress 操作人公钥
	 * @param Integer $billId 票据ID
	 * @param String $type 票据类型
	 * @param String $drawer 出票人
	 * @param String $acceptor 承兑人
	 * @param String $expiration 到期时间
	 * @param String $amount 票面金额
	 * @param Integer $uid 用户ID
	 * @param String $owner 拥有者公钥
	 * @param String $issue_at 出票日期
	 * @param String $info 用户信息
	 * -------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function createBill($signature, $instructionId, $userAddress, $billId, $type, $drawer, $taker, $acceptor, $expiration, $amount, $uid, $owner, $issue_at, $userInfo){
		$result = $this->_send([
			'signature' => $signature,
			'instructionId' => $instructionId,
			'uid' => $userAddress,
			'noteId' => $billId,
			'noteType' => $type,
			'drawer' => $drawer,
			'taker' => $taker,
			'acceptor' => $acceptor,
			'expiration' => $expiration,
			'nominalValue' => $amount,
			'owner' => $owner,
			'createdDate' => $issue_at,
			'userId' => $uid,
			'info' => $userInfo
		]);
		return isset($result['statusCode']) && $result['statusCode'] == 200;
	}

	/**
	 * deleteBill(String $signature, String $instructionId, Integer $userAddress, Integer $billId, String $owner)
	 * 删除票据
	 * ---------------------------------------------------------------------------------------------------------
	 * @param String $signature 签名字符串
	 * @param String instructionId 指令ID
	 * @param String $userAddress 操作人公钥
	 * @param Integer $billId 票据ID
	 * @param String $owner 拥有者公钥
	 * -------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function deleteBill($signature, $instructionId, $userAddress, $billId, $owner){
		$result = $this->_send([
			'signature' => $signature,
			'instructionId' => $instructionId,
			'uid' => $userAddress,
			'noteId' => $billId,
			'owner' => $owner
		]);
		return isset($result['statusCode']) && $result['statusCode'] == 200;
	}

	/**
	 * discountBill(String $signature, String $instructionId, Integer $userAddress, Integer $billId, String $discounter, Float $rate, String $date, String $protocol, String $owner[, String $toAddr = null])
	 * 贴现票据
	 * -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * @param String $signature 签名字符串
	 * @param String instructionId 指令ID
	 * @param String $userAddress 操作人公钥
	 * @param Integer $billId 票据ID
	 * @param String $discounter 贴现人
	 * @param Float $rate 年化利率
	 * @param String $date 贴现时间
	 * @param String $protocol 协议编号
	 * @param String $owner 拥有者公钥
	 * @param String $toAddr 对方公钥
	 * -------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function discountBill($signature, $instructionId, $userAddress, $billId, $discounter, $rate, $date, $protocol, $owner, $toAddr = null){
		$result = $this->_send([
			'signature' => $signature,
			'instructionId' => $instructionId,
			'uid' => $userAddress,
			'noteId' => $billId,
			'discounter' => $discounter,
			'discountRate' => $rate,
			'date' => $date,
			'protocol' => $protocol,
			'owner' => $owner,
			'toAddr' => $toAddr
		]);
		return isset($result['statusCode']) && $result['statusCode'] == 200;
	}

	/**
	 * endorseBill(String $signature, String $instructionId, String $userAddress, Integer $billId, String $owner[, String $to = null])
	 * 背书票据
	 * ------------------------------------------------------------------------------------------------------------------------------
	 * @param String $signature 签名字符串
	 * @param String instructionId 指令ID
	 * @param String $userAddress 操作人公钥
	 * @param Integer $billId 票据ID
	 * @param String $owner 拥有者公钥
	 * @param String $to 对方公钥
	 * -------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function endorseBill($signature, $instructionId, $userAddress, $billId, $owner, $to = null){
		$result = $this->_send([
			'signature' => $signature,
			'instructionId' => $instructionId,
			'uid' => $userAddress,
			'noteId' => $billId,
			'owner' => $owner,
			'to' => $to
		]);
		return isset($result['statusCode']) && $result['statusCode'] == 200;
	}

	/**
	 * setAdmin(String $signature, String $instructionId, String $address, String $type, Integer $userAddress)
	 * 设置管理员
	 * ------------------------------------------------------------------------------------------------------
	 * @param String $signature 签名字符串
	 * @param String instructionId 指令ID
	 * @param String $address 公钥地址
	 * @param String $type 管理员类型
	 * @param String $userAddress 操作人公钥
	 * -------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function setAdmin($signature, $instructionId, $address, $type, $userAddress){
		$result = $this->_send([
			'signature' => $signature,
			'instructionId' => $instructionId,
			'admin' => $address,
			'adminType' => $type,
			'uid' => $userAddress
		]);
		return isset($result['statusCode']) && $result['statusCode'] == 200;
	}

	/**
	 * transferAccount(String $signature, String $instructionId, String $userId, String $address, Integer $userAddress)
	 * 转移账户
	 * ---------------------------------------------------------------------------------------------------------------
	 * @param String $signature 签名字符串
	 * @param String instructionId 指令ID
	 * @param String $userId 用户ID
	 * @param String $address 新地址
	 * @param String $userAddress 操作人公钥
	 * -------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function transferAccount($signature, $instructionId, $userAddress, $userId, $address){
		$result = $this->_send([
			'signature' => $signature,
			'instructionId' => $instructionId,
			'userId' => $userId,
			'userAddr' => $address,
			'uid' => $userAddress
		]);
		return isset($result['statusCode']) && $result['statusCode'] == 200;
	}

	/**
	 * userRegister(String $signature, String $instructionId, Integer $userAddress, String $address, Integer $userId, Integer $type)
	 * 用户注册
	 * ----------------------------------------------------------------------------------------------------------------------------
	 * @param String $signature 签名字符串
	 * @param String instructionId 指令ID
	 * @param String $userAddress 操作人公钥
	 * @param String $address 用户公钥
	 * @param Integer $uid 用户ID
	 * @param Integer $type 用户类型
	 * -------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function userRegister($signature, $instructionId, $userAddress, $address, $userId, $type){
		$result = $this->_send([
			'signature' => $signature,
			'instructionId' => $instructionId,
			'uid' => $userAddress,
			'userAddr' => $address,
			'userId' => $userId,
			'userType' => $type
		]);
		return isset($result['statusCode']) && $result['statusCode'] == 200;
	}

	/**
	 * userRegisterBySystem(Integer $userAddress, String $address, Integer $userId)
	 * 系统用户注册
	 * ---------------------------------------------------------------------------
	 * @param String $userAddress 操作人公钥
	 * @param String $address 用户公钥
	 * @param Integer $uid 用户ID
	 * -------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function userRegisterBySystem($userAddress, $address, $userId){
		$result = $this->_send([
			'uid' => $userAddress,
			'userAddr' => $address,
			'userId' => $userId
		]);
		return isset($result['statusCode']) && $result['statusCode'] == 200;
	}

	/**
	 * operateNote(String $signature, String $instructionId, Integer $userAddress, String $billId, Integer $type)
	 * 操作票据
	 * ---------------------------------------------------------------------------------------------------------
	 * @param String $signature 签名字符串
	 * @param String instructionId 指令ID
	 * @param String $userAddress 操作人公钥
	 * @param String $billId 票据编号
	 * @param Integer $type 操作类型
	 * -------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function operateNote($signature, $instructionId, $userAddress, $billId, $type){
		$result = $this->_send([
			'signature' => $signature,
			'instructionId' => $instructionId,
			'uid' => $userAddress,
			'noteId' => $billId,
			'operateType' => $type
		]);
		return isset($result['statusCode']) && $result['statusCode'] == 200;
	}

	/**
	 * listingBill(String $signature, String $instructionId, Integer $userAddress, String $billId, Float $amount)
	 * 挂牌票据
	 * ---------------------------------------------------------------------------------------------------------
	 * @param String $signature 签名字符串
	 * @param String instructionId 指令ID
	 * @param String $userAddress 操作人公钥
	 * @param String $billId 票据编号
	 * @param Float $amount 挂牌金额
	 * -------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function listingBill($signature, $instructionId, $userAddress, $billId, $amount){
		$result = $this->_send([
			'signature' => $signature,
			'instructionId' => $instructionId,
			'uid' => $userAddress,
			'noteId' => $billId,
			'amount' => $amount
		]);
		return isset($result['statusCode']) && $result['statusCode'] == 200;
	}

	/**
	 * query(String $signature)
	 * 发送查询请求
	 * --------------------------
	 * @param String $signature 签名字符串
	 * -----------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function query($signature){
		$result = $this->_send(['signature' => $signature]);
		return isset($result['statusCode']) && $result['statusCode'] == 200 ? $result : false;
	}

	/**
	 * execute(String $signature)
	 * 发送操作请求
	 * --------------------------
	 * @param String $signature 签名字符串
	 * -----------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function execute($signature){
		$result = $this->_send(['signature' => $signature]);
		return isset($result['statusCode']) && $result['statusCode'] == 200;
	}

	/**
	 * _send(Array $data)
	 * 发送数据
	 * ------------------
	 * @param Array $data 待发送的数据
	 * -------------------------------
	 * @return Array
	 * @author Verident。
	 */
	protected function _send(Array $data){
		Yii::$app->cUrl->reset();
		if(!empty($this->beforeSend) && is_callable($this->beforeSend)){
			$data = call_user_func($this->beforeSend, $data);
		}
		Yii::$app->cUrl->setData($data);
		$url = $this->routes[debug_backtrace()[1]['function']];
		try{
			$result = Yii::$app->cUrl->post($url, 'JSON');
		}catch(\Exception $e){
			$result = ['statusCode' => $e->getCode(), 'message' => $e->getMessage()];
		}
		$urlParse = parse_url($url);
		$status = isset($result['statusCode']) && $result['statusCode'] == 200 ? Log::STATUS_SUCCESS : Log::STATUS_FAILED;
		if($status == Log::STATUS_FAILED){
			Yii::error(['url' => $url, 'send' => $data, 'receive' => $result], __METHOD__);
		}
		Log::addLog([
			'status' => $status,
			'url' => $url,
			'route' => $urlParse['path'],
			'error_info' => $status == Log::STATUS_FAILED ? (isset($result['errinfo']) ? $result['errinfo'] : (isset($result['message']) ? $result['message'] : null)) : null,
			'gets' => isset($urlParse['query']) ? json_encode(parse_str($urlParse['query'])) : null,
			'posts' => json_encode($data),
			'responses' => json_encode($result)
		]);
		return $result;
	}
}
