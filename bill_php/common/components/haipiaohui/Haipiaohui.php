<?php
namespace common\components\haipiaohui;

use Yii;
use yii\base\Model;
use yii\helpers\Json;
use common\models\BankCompany;
use common\models\CompanyBaseInfo;
use common\models\TradeCompany;
use common\models\User;

/**
 * Haipiaohui Model
 * 海票惠 模型
 * ----------------
 * @version 1.0.0
 * @author Verdient。
 */
class Haipiaohui extends Model
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
	 * @var public $enabledProtocols
	 * 允许的协议
	 * -----------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $enabledProtocols = ['http', 'https'];

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
	 * @var public $onlyLocal
	 * 仅使用本地
	 * ----------------------
	 * @method Config
	 * @author Verident。
	 */
	public $onlyLocal = false;

	/**
	 * init()
	 * 初始化
	 * ------
	 * @author Verident。
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
	 * generateMemberAccount(String $name, String $username, String $parent)
	 * 生成成员公司账号
	 * ---------------------------------------------------------------------
	 * @param String $name 企业名称
	 * @param String $username 登录名称
	 * @param String $parent 财务公司ID
	 * --------------------------------
	 * @return Mixed
	 * @author Verdient。
	 */
	public function generateMemberAccount($name, $username, $parent){
		if($this->onlyLocal === true){
			$model = new TradeCompany;
			$model->orgName = $name;
			$model->status = 1;
			$model->createTime = date('Y-m-d H:i:s');
			$model->updateTime = date('Y-m-d H:i:s');
			$model->save(false);
			$model2 = new User;
			$model2->create_date = date('Y-m-d H:i:s');
			$model2->email = $username;
			$model2->enabled = 'T';
			$model2->encryptPwd = 'D58CD03CB0928F96FEE0D4929BF4F77F';
			$model2->enterprise_id = $model->id;
			$model2->name = $name;
			$model2->secret_key = 'e0b3abe8-2617-4aa3-b0b2-db02cacdefbb';
			$model2->userTag = 'FINANCE_MEMBER';
			$model2->belong_com = $parent;
			$model2->save(false);
			return ['username' => $username, 'password' => '111111'];
		}
		return $this->_send(['name' => $name, 'username' => $username, 'belong_com' => $parent]);
	}

	/**
	 * changePassword(String $id, String $password)
	 * 修改密码
	 * --------------------------------------------
	 * @param String $id 用户ID
	 * @param String $password 新密码
	 * ------------------------------
	 * @return Mixed
	 * @author Verdient。
	 */
	public function changePassword($id, $password){
		if($this->onlyLocal === true){
			$model = User::findOne(['id' => $id]);
			$model->encryptPwd = $model->encryptPassword($password, $model->secret_key);
			$model->save(false);
			return ['success' => true];
		}
		return $this->_send(['user_id' => $id, 'password' => $password]);
	}

	/**
	 * resetPassword(String $id)
	 * 重置密码
	 * -------------------------
	 * @param String $id 企业ID
	 * ------------------------
	 * @return Mixed
	 * @author Verdient。
	 */
	public function resetPassword($id){
		return $this->_send(['user_id' => $id]);
	}

	/**
	 * generateMemberHistory(String $id, Integer $page = 1, Integer $pageSize = 10)
	 * 生成成员公司账号历史
	 * ----------------------------------------------------------------------------
	 * @param String $id 财务公司ID
	 * @param Integer $page 页码
	 * @param Integer $pageSize 分页大小
	 * ---------------------------------
	 * @return Mixed
	 * @author Verdient。
	 */
	public function generateMemberHistory($id, $page = 1, $pageSize = 10){
		return $this->_send(['belong_com' => $id, 'page' => $page, 'pageSize' => $pageSize]);
	}

	/**
	 * sendBankInfo(Array $data)
	 * 发送银行信息
	 * -------------------------
	 * @param Array $data 待发送的数据
	 * -------------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function sendBankInfo(Array $data){
		if($this->onlyLocal === true){
			if(!$model = BankCompany::find()->where(['companyId' => $data['companyId']])->one()){
				$model = new BankCompany;
			}
			foreach($data as $key => $value){
				$model->$key = $value;
			}
			$model->create_date = date('Y-m-d H:i:s');
			$model->last_update_date = date('Y-m-d H:i:s');
			$model->save(false);
			return ['success' => true];
		}
		return $this->_send($data);
	}

	/**
	 * sendBaseCompanyInfo(Array $data)
	 * 发送基本企业信息
	 * --------------------------------
	 * @param Array $data 待发送的数据
	 * -------------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function sendBaseCompanyInfo(Array $data){
		if($this->onlyLocal === true){
			if(!$model = CompanyBaseInfo::find()->where(['enterprise_id' => $data['enterprise_id']])->one()){
				$model = new CompanyBaseInfo;
			}
			foreach($data as $key => $value){
				$model->$key = $value;
			}
			$model->save(false);
			return ['success' => true];
		}
		return $this->_send($data);
	}

	/**
	 * _send(Array $path)
	 * 发送信息
	 * ------------------
	 * @param Array $data 待发送的数据
	 * -------------------------------
	 * @return Array
	 * @author Verdient。
	 */
	protected function _send($data){
		Yii::$app->cUrl->reset();
		Yii::$app->cUrl->setData($data, 'JSON');
		$url = $this->routes[debug_backtrace()[1]['function']];
		$result = Yii::$app->cUrl->post($url, 'JSON');
		$urlParse = parse_url($url);
		Log::addLog([
			'status' => isset($result['success']) && $result['success'] == true ? Log::STATUS_SUCCESS : Log::STATUS_FAILED,
			'url' => $url,
			'route' => $urlParse['path'],
			'gets' => isset($urlParse['query']) ? json_encode(parse_str($urlParse['query'])) : null,
			'posts' => json_encode($data),
			'datas' => json_encode($result)
		]);
		if(isset($result['success']) && $result['success'] == true){
			return $result;
		}
		return false;
	}
}