<?php
namespace common\components;

use Yii;
use yii\base\Model;
use common\helpers\CUrlHelper;

/**
 * BlockChainResolve Model
 * 区块链解析 模型
 * -----------------------
 * @version 1.0.0
 * @author Verdient。
 */
class BlockChainResolve extends Model
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
	 * getBalance(String $uid)
	 * 获取余额
	 * -----------------------
	 * @param String $uid 用户ID
	 * -------------------------
	 * @return Array / false
	 * @author Verdient。
	 */
	public function getBalance($uid){
		$data = ['uid' => $uid];
		$result = $this->_send($data);
		return isset($result['code']) && $result['code'] == 200 ? $result : false;
	}

	/**
	 * _send()
	 * 发送数据
	 * --------
	 * @author Verident。
	 */
	protected function _send($data){
		if(!empty($this->beforeSend) && is_callable($this->beforeSend)){
			$data = call_user_func($this->beforeSend, $data);
		}
		Yii::$app->cUrl->setData($data);
		return Yii::$app->cUrl->post($this->routes[debug_backtrace()[1]['function']], 'JSON');
	}
}