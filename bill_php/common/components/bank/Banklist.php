<?php
namespace common\components\bank;

use Yii;
use yii\base\Model;
use yii\helpers\Json;

/**
 * Banklist Model
 * 银行列表对接模型
 * ----------------
 * @version 1.0.0
 * @author Verdient。
 */
class Banklist extends Model
{
	/**
	 * @scenario const SCENARIO_CITY
	 * 获取城市列表
	 * -----------------------------
	 * @author Verdient。
	 */
	const SCENARIO_CITY = 'city';

	/**
	 * @scenario const SCENARIO_BANK
	 * 获取银行列表
	 * -----------------------------
	 * @author Verdient。
	 */
	const SCENARIO_BANK = 'bank';

	/**
	 * @scenario const SCENARIO_BANK_BRANCH
	 * 获取支行列表
	 * ------------------------------------
	 * @author Verdient。
	 */
	const SCENARIO_BANK_BRANCH = 'bank_branch';

	/**
	 * @scenario const SCENARIO_BANK_INFO
	 * 获取银行信息
	 * ----------------------------------
	 * @author Verdient。
	 */
	const SCENARIO_BANK_INFO = 'bank_info';

	/**
	 * @scenario const SCENARIO_CITY_INFO
	 * 获取城市信息
	 * ----------------------------------
	 * @author Verdient。
	 */
	const SCENARIO_CITY_INFO = 'city_info';

	/**
	 * @var public $cityType
	 * 城市类型
	 * ---------------------
	 * @author Verdient。
	 */
	public $cityType;

	/**
	 * @var public $cityNode
	 * 上级城市代码
	 * ---------------------
	 * @author Verdient。
	 */
	public $cityNode;

	/**
	 * @var public $bankCode
	 * 银行代码
	 * ---------------------
	 * @author Verdient。
	 */
	public $bankCode;

	/**
	 * @var public $cityCode
	 * 城市代码
	 * ---------------------
	 * @author Verdient。
	 */
	public $cityCode;

	/**
	 * @var public $bankType
	 * 银行类型
	 * ---------------------
	 * @author Verdient。
	 */
	public $bankType;

	/**
	 * scenarios()
	 * 设置场景
	 * -----------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public function scenarios(){
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_CITY] = ['cityType', 'cityNode'];
		$scenarios[self::SCENARIO_BANK] = [];
		$scenarios[self::SCENARIO_BANK_BRANCH] = ['bankCode', 'cityCode'];
		$scenarios[self::SCENARIO_BANK_INFO] = ['bankCode', 'bankType'];
		$scenarios[self::SCENARIO_CITY_INFO] = ['cityCode', 'cityType'];
		return $scenarios;
	}

	/**
	 * rules()
	 * 设置校验规则
	 * ------------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public function rules(){
		return [
			//获取城市列表
			['cityType', 'required', 'on' => self::SCENARIO_CITY],
			[['cityType', 'cityNode'], 'string', 'on' => self::SCENARIO_CITY],
			[['cityType', 'cityNode'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_CITY],
			['cityType', 'in', 'range' => ['1', '2', '3'], 'on' => self::SCENARIO_CITY],
			['cityType', 'validateCtiyNode', 'on' => self::SCENARIO_CITY],

			//获取银行支行列表
			[['bankCode', 'cityCode'], 'required', 'on' => self::SCENARIO_BANK_BRANCH],
			[['bankCode', 'cityCode'], 'string', 'on' => self::SCENARIO_BANK_BRANCH],
			[['bankCode', 'cityCode'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_BANK_BRANCH],

			//获取银行信息
			[['bankCode', 'bankType'], 'required', 'on' => self::SCENARIO_BANK_INFO],
			[['bankCode', 'bankType'], 'string', 'on' => self::SCENARIO_BANK_INFO],
			[['bankCode', 'bankType'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_BANK_INFO],
			['bankType', 'in', 'range' => ['1', '2'], 'on' => self::SCENARIO_BANK_INFO],

			//获取城市信息
			[['cityCode', 'cityType'], 'required', 'on' => self::SCENARIO_CITY_INFO],
			[['cityCode', 'cityType'], 'string', 'on' => self::SCENARIO_CITY_INFO],
			[['cityCode', 'cityType'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_CITY_INFO],
			['cityType', 'in', 'range' => ['1', '2'], 'on' => self::SCENARIO_CITY_INFO],
		];
	}

	/**
	 * validateCtiyNode(String $attribute, Mixed $params)
	 * 校验上级城市
	 * --------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateCtiyNode($attribute, $params){
		if (($this->cityType == 2 || $this->cityType == 3) && empty($this->cityNode)) {
			$this->addError('cityNode', '上级城市代码不能为空');
		}
	}

	/**
	 * 获取城市列表
	 * @author CGA
	 */
	public function getCity($posts = []){
		$flag = false;
		$error = '';
		$result = [];
		$this->setScenario(self::SCENARIO_CITY);
		$content = $this->get_content($posts);
		if (!$error = $this->_getError()) {
			if ($content['success'] == 1) {
				$flag = true;
				$result = $content['data'];
			} else {
				$error = $content['msg'];
			}
		}
		return [$flag, $result, $error];
	}

	/**
	 * 获取银行列表
	 * @author CGA
	 */
	public function getBank(){
		$flag = false;
		$error = '';
		$result = [];
		$this->setScenario(self::SCENARIO_BANK);
		$content = $this->get_content();
		if (!$error = $this->_getError()) {
			if ($content['success'] == 1) {
				$flag = true;
				$result = $content['data'];
			} else {
				$error = $content['msg'];
			}
		}
		return [$flag, $result, $error];
	}

	/**
	 * 获取银行支行列表
	 * @author CGA
	 */
	public function getBankBranch($posts = []){
		$flag = false;
		$error = '';
		$result = [];
		$this->setScenario(self::SCENARIO_BANK_BRANCH);
		$content = $this->get_content($posts);
		if (!$error = $this->_getError()) {
			if ($content['success'] == 1) {
				$flag = true;
				$result = $content['data'];
			} else {
				$error = $content['msg'];
			}
		}
		return [$flag, $result, $error];
	}

	/**
	 * 获取银行信息
	 * @author CGA
	 */
	public function getBankInfo($posts = []){
		$flag = false;
		$error = '';
		$result = [];
		$this->setScenario(self::SCENARIO_BANK_INFO);
		$content = $this->get_content($posts);
		if (!$error = $this->_getError()) {
			if ($content['success'] == 1) {
				$flag = true;
				$result = $content['data'];
			} else {
				$error = $content['msg'];
			}
		}
		return [$flag, $result, $error];
	}

	/**
	 * 获取城市信息
	 * @author CGA
	 */
	public function getCityInfo($posts = []){
		$flag = false;
		$error = '';
		$result = [];
		$this->setScenario(self::SCENARIO_CITY_INFO);
		$content = $this->get_content($posts);
		if (!$error = $this->_getError()) {
			if ($content['success'] == 1) {
				$flag = true;
				$result = $content['data'];
			} else {
				$error = $content['msg'];
			}
		}
		return [$flag, $result, $error];
	}

	/**
	 * 访问接口
	 * @author CGA
	 */
	private function get_content($posts = []){
		$this->attributes = $posts;
		if ($this->validate()) {
			$activeAttributes = $this->activeAttributes();
			$bodys = [];
			foreach ($activeAttributes as $key => $value) {
				if (!empty($this->$value)) {
					$bodys[$value] = $this->$value;
				}
			}
			$scenario = $this->getScenario();
			if ($scenario == 'default' || empty($scenario)) {
				$this->addError('system', '非法场景访问');
				return false;
			}
			$url = Yii::$app->haipingxian->bankServiceApi;
			$opter = isset($posts['opter']) && !empty($posts['opter']) ? $posts['opter'] : Yii::$app->OAuth2->userInfo->enterprise_id;
			$head = [
				'opter'  => $opter,
				'action' => Yii::$app->params['bank'][$scenario],
				'bType'  => '1',
			];
			$params = array_merge($head, $bodys);
			list($flag, $result, $error) = $this->getSoap($params);
			if (isset($result['success'])) {
				return $result;
			} else {
				$this->addError('system', '银行接口访问失败');
				return false;
			}
		}
	}

	public function encryption($posts = []){
		$post_json = Json::encode($posts,JSON_UNESCAPED_UNICODE);
		$post_strArray = str_split(preg_replace('/([\x80-\xff]*)/i','',$post_json));
		sort($post_strArray);
		$post_str = implode("", $post_strArray);
		$signature = sha1(Yii::$app->params['bank']['encryptionKey'] . $post_str);
		$posts['signature'] = $signature;
		return Json::encode($posts);
	}

	public function getSoap($posts = []){
		$flag = false;
		$data = [];
		$error = '';
		$params = $this->encryption($posts);
		try {
			$client = new \SoapClient(Yii::$app->haipingxian->bankServiceApi);
			$account = Yii::$app->params['bank']['account'];
			$password = Yii::$app->params['bank']['password'];
			$headerVar = new \SoapVar(array('account' => $account, 'password' => $password), SOAP_ENC_OBJECT);
			$header = new \SoapHeader(Yii::$app->haipingxian->bankServiceApi, 'SoapInterceptor', $headerVar, false, SOAP_ACTOR_NEXT);
			$client->__setSoapHeaders($header);
			$data = $client->swopBankInfo($params);
			if ($data) {
				$flag = true;
				$data = Json::decode($data);
			}
		} catch (\SoapFault $e) {
			$error = $e->faultstring;
		}
		return [$flag, $data, $error];
	}

	/**
	 * 返回模型错误
	 * @author CGA
	 */
	private function _getError(){
		if ($this->hasErrors()) {
			$error = null;
			$errors = $this->getErrors();
			foreach ($errors as $value) {
				$error = $value[0];
				break;
			}
			return $error;
		} else {
			return false;
		}
	}
}
