<?php
namespace common\components;

use Yii;
use yii\base\Model;

/**
 * StaticResource Model
 * 静态资源 模型
 * --------------------
 * @version 1.0.0
 * @author Verdient。
 */
class StaticResource extends Model
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
	}

	/**
	 * path2url($path)
	 * 路径转换为url
	 * ---------------
	 * @return String
	 * @author Verdient。
	 */
	public function path2url($path){
		if(!$path){
			return null;
		}
		$path = str_replace('\\', '/', $path);
		return $this->protocol . '://' . $this->host . (($this->port && $this->port != 80) ? ':' . $this->port : '') . '/' .$path;
	}
}