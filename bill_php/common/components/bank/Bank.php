<?php
namespace common\components\bank;

use Yii;
use common\helpers\Helper;
use yii\base\Model;
use yii\helpers\Json;

/**
 * Bank Model
 * 银行接口对接模型
 * ----------------
 * @version 1.0.0
 * @author CGA
 */
class Bank extends Model
{
	/**
	 * @secnario const SCENARIO_GETBALANCE
	 * 获取余额
	 * -----------------------------------
	 * @author Verdient。
	 */
	const SCENARIO_GETBALANCE = 'getBalance';

	/**
	 * @secnario const SCENARIO_GETFREEZE
	 * 获取解冻码
	 * -----------------------------------
	 * @author Verdient。
	 */
	const SCENARIO_GETFREEZE = 'getFreeze';

	/**
	 * @secnario const SCENARIO_GETFREEZESTATUS
	 * 获取冻结状态
	 * ----------------------------------------
	 * @author Verdient。
	 */
	const SCENARIO_GETFREEZESTATUS = 'getFreezeStatus';

	/**
	 * @secnario const SCENARIO_GETTRANSACTIONDETAIL
	 * 获取交易详情
	 * ---------------------------------------------
	 * @author Verdient。
	 */
	const SCENARIO_GETTRANSACTIONDETAIL = 'getTransactionDetail';

	/**
	 * @secnario const SCENARIO_GETINFO
	 * 获取详情
	 * --------------------------------
	 * @author Verdient。
	 */
	const SCENARIO_GETINFO = 'getInfo';

	/**
	 * @secnario const SCENARIO_GETDEALFLOW
	 * 账号交易流水
	 * ------------------------------------
	 * @author Verdient。
	 */
	const SCENARIO_GETDEALFLOW = 'getDealflow';

	/**
	 * @secnario const SCENARIO_UPDATEBALANCE
	 * 更新余额
	 * --------------------------------------
	 * @author Verdient。
	 */
	const SCENARIO_UPDATEBALANCE = 'updateBalance';

	/**
	 * @secnario const SCENARIO_TRADING
	 * 转账交易
	 * --------------------------------
	 * @author Verdient。
	 */
	const SCENARIO_TRADING = 'trading';

	/**
	 * @secnario const SCENARIO_WITHDRAW
	 * 提款
	 * ----------------------------------
	 * @author Verdient。
	 */
	const SCENARIO_WITHDRAW = 'withdraw';

	/**
	 * @secnario const SCENARIO_EDITPWD
	 * 修改交易密码
	 * --------------------------------
	 * @author Verdient。
	 */
	const SCENARIO_EDITPWD = 'editPwd';

	/**
	 * @secnario const SCENARIO_VALIDATEPWD
	 * 验证交易密码
	 * ------------------------------------
	 * @author Verdient。
	 */
	const SCENARIO_VALIDATEPWD = 'validatePwd';

	/**
	 * @secnario const SCENARIO_TRACKING
	 * 交易追踪
	 * ---------------------------------
	 * @author Verdient。
	 */
	const SCENARIO_TRACKING = 'tracking';

	/**
	 * @secnario const ACTION_MAP
	 * 动作映射关系
	 * --------------------------
	 * @author Verdient。
	 */
	const ACTION_MAP = "return [
		common\components\bank\Bank::SCENARIO_GETBALANCE => '9201',
		common\components\bank\Bank::SCENARIO_GETFREEZE => '9202',
		common\components\bank\Bank::SCENARIO_GETFREEZESTATUS => '9203',
		common\components\bank\Bank::SCENARIO_GETTRANSACTIONDETAIL => '9204',
		common\components\bank\Bank::SCENARIO_GETINFO => '9207',
		common\components\bank\Bank::SCENARIO_GETDEALFLOW => '9208',
		common\components\bank\Bank::SCENARIO_UPDATEBALANCE  => '9001',
		common\components\bank\Bank::SCENARIO_TRADING => '9101',
		common\components\bank\Bank::SCENARIO_WITHDRAW => '9102',
		common\components\bank\Bank::SCENARIO_VALIDATEPWD => '8328',
		common\components\bank\Bank::SCENARIO_EDITPWD => '8201',
		common\components\bank\Bank::SCENARIO_TRACKING => '8105',
	];";

	/**
	 * @var public $enableBank;
	 * 账号
	 * ------------------------
	 * @author Verdient。
	 */
	public $enableBank = true;

	/**
	 * @var public $account
	 * 账号
	 * --------------------
	 * @author Verdient。
	 */
	public $account;

	/**
	 * @var public $password
	 * 密码
	 * ---------------------
	 * @author Verdient。
	 */
	public $password;

	/**
	 * @var public $encryptionKey
	 * 加密密钥
	 * --------------------------
	 * @author Verdient。
	 */
	public $encryptionKey;

	/**
	 * @var public $apiUrl
	 * 接口地址
	 * -------------------
	 * @author Verdient。
	 */
	public $apiUrl = '';

	/**
	 * @var public $custNo
	 * 会员编号
	 * -------------------
	 * @author Verdient。
	 */
	public $custNo;

	/**
	 * @var public $clientID
	 * 客户流水号
	 * ---------------------
	 * @author Verdient。
	 */
	public $clientID;

	/**
	 * @var public $type
	 * 原请求代码
	 * -----------------
	 * @author Verdient。
	 */
	public $type;

	/**
	 * @var public $startDate
	 * 开始日期
	 * ------------------
	 * @author Verdient。
	 */
	public $startDate;

	/**
	 * @var public $endDate
	 * 结束日期
	 * --------------------
	 * @author Verdient。
	 */
	public $endDate;

	/**
	 * @var public $queryType
	 * 查询类型
	 * ----------------------
	 * @author Verdient。
	 */
	public $queryType;

	/**
	 * @var public $tranType
	 * 交易类型
	 * ---------------------
	 * @author Verdient。
	 */
	public $tranType;

	/**
	 * @var public $startRecord
	 * 起始记录号
	 * ------------------------
	 * @author Verdient。
	 */
	public $startRecord;

	/**
	 * @var public $pageNumber
	 * 请求记录条数
	 * -----------------------
	 * @author Verdient。
	 */
	public $pageNumber;

	/**
	 * @var public $custAccNo
	 * 附属账号
	 * ----------------------
	 * @author Verdient。
	 */
	public $custAccNo;

	/**
	 * @var public $tranAmt
	 * 交易金额
	 * --------------------
	 * @author Verdient。
	 */
	public $tranAmt;

	/**
	 * @var public $freezeNo
	 * 冻结编码
	 * ---------------------
	 * @author Verdient。
	 */
	public $freezeNo;

	/**
	 * @var public $payPwd
	 * 交易密码
	 * -------------------
	 * @author Verdient。
	 */
	public $payPwd;

	/**
	 * @var public $memo
	 * 摘要
	 * -----------------
	 * @author Verdient。
	 */
	public $memo;

	/**
	 * @var public $preFlg
	 * 预约标志
	 * -------------------
	 * @author Verdient。
	 */
	public $preFlg;

	/**
	 * @var public $preDate
	 * 预约日期
	 * --------------------
	 * @author Verdient。
	 */
	public $preDate;

	/**
	 * @var public $preTime
	 * 预约时间
	 * --------------------
	 * @author Verdient。
	 */
	public $preTime;

	/**
	 * @var public $toCustNo
	 * 付款会员编号
	 * ---------------------
	 * @author Verdient。
	 */
	public $toCustNo;

	/**
	 * @var public $accAction
	 * 操作类型
	 * ----------------------
	 * @author Verdient。
	 */
	public $accAction;

	/**
	 * @var public $oldpayPwd
	 * 原密码
	 * ----------------------
	 * @author Verdient。
	 */
	public $oldpayPwd;

	/**
	 * @var public $newpayPwd
	 * 新密码密码
	 * ----------------------
	 * @author Verdient。
	 */
	public $newpayPwd;

	/**
	 * @var public $statusTrack
	 * 交易状态
	 * ------------------------
	 * @author Verdient。
	 */
	public $statusTrack;

	/**
	 * @var public $opter
	 * 操作人
	 * ------------------
	 * @author Verdient。
	 */
	public $opter;

	/**
	 * scenarios()
	 * 场景设置
	 * -----------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public function scenarios(){
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_GETBALANCE] = ['custNo'];
		$scenarios[self::SCENARIO_GETFREEZE] = ['clientID', 'type'];
		$scenarios[self::SCENARIO_GETFREEZESTATUS] = ['custNo', 'startDate', 'endDate'];
		$scenarios[self::SCENARIO_GETTRANSACTIONDETAIL] = ['custNo', 'startDate', 'endDate', 'queryType', 'tranType', 'startRecord', 'pageNumber', 'opter'];
		$scenarios[self::SCENARIO_GETINFO] = ['custNo', 'custAccNo', 'pageNumber', 'startRecord'];
		$scenarios[self::SCENARIO_GETDEALFLOW] = ['custNo', 'startDate', 'endDate', 'pageNumber', 'startRecord'];
		$scenarios[self::SCENARIO_UPDATEBALANCE] = ['custNo'];
		$scenarios[self::SCENARIO_TRADING] = ['custNo', 'toCustNo', 'tranType', 'tranAmt', 'freezeNo', 'payPwd', 'memo'];
		$scenarios[self::SCENARIO_WITHDRAW] = ['custNo', 'tranAmt', 'payPwd', 'memo', 'preFlg', 'preDate', 'preTime'];
		$scenarios[self::SCENARIO_EDITPWD] = ['custNo', 'accAction', 'oldpayPwd', 'newpayPwd'];
		$scenarios[self::SCENARIO_VALIDATEPWD] = ['custNo', 'payPwd'];
		$scenarios[self::SCENARIO_TRACKING] = ['startDate','endDate','statusTrack','pageNumber','startRecord'];
		return $scenarios;
	}

	/**
	 * 校验规则
	 * @author CGA
	 */
	public function rules(){
		return [
			//账户余额查询
			['custNo', 'required', 'on' => self::SCENARIO_GETBALANCE],
			['custNo', 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_GETBALANCE],
			['custNo', 'string', 'length' => [0, 45], 'on' => self::SCENARIO_GETBALANCE],

			//冻结查询
			['clientID', 'required', 'on' => self::SCENARIO_GETFREEZE],
			[['clientID', 'type'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_GETFREEZE],
			['clientID', 'string', 'length' => [0, 20], 'on' => self::SCENARIO_GETFREEZE],
			['type', 'string', 'length' => [0, 8], 'on' => self::SCENARIO_GETFREEZE],

			//冻结交易状态查询
			[['custNo', 'startDate', 'endDate'], 'required', 'on' => self::SCENARIO_GETFREEZESTATUS],
			[['custNo', 'startDate', 'endDate'], 'string', 'on' => self::SCENARIO_GETFREEZESTATUS],
			['custNo', 'string', 'length' => [0, 45], 'on' => self::SCENARIO_GETFREEZESTATUS],
			[['custNo', 'startDate', 'endDate'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_GETFREEZESTATUS],
			['startDate', 'match', 'pattern' => '/^(19|20)\d\d(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])$/', 'on' => self::SCENARIO_GETFREEZESTATUS],
			['startDate', 'checkStartDate', 'on' => self::SCENARIO_GETFREEZESTATUS],
			['endDate', 'match', 'pattern' => '/^(19|20)\d\d(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])$/', 'on' => self::SCENARIO_GETFREEZESTATUS],
			['endDate', 'checkEndDate', 'on' => self::SCENARIO_GETFREEZESTATUS],

			//账户交易明细查询
			[['custNo', 'startDate', 'endDate', 'startRecord', 'pageNumber'], 'required', 'on' => self::SCENARIO_GETTRANSACTIONDETAIL],
			[['custNo', 'startDate', 'endDate', 'queryType', 'tranType', 'startRecord', 'pageNumber'], 'string', 'on' => self::SCENARIO_GETTRANSACTIONDETAIL],
			['custNo', 'string', 'length' => [0, 45], 'on' => self::SCENARIO_GETTRANSACTIONDETAIL],
			[['custNo', 'startDate', 'endDate', 'queryType', 'tranType', 'startRecord', 'pageNumber'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_GETTRANSACTIONDETAIL],
			['startDate', 'match', 'pattern' => '/^(19|20)\d\d(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])$/', 'on' => self::SCENARIO_GETTRANSACTIONDETAIL],
			['startDate', 'checkStartDate', 'on' => self::SCENARIO_GETTRANSACTIONDETAIL],
			['endDate', 'match', 'pattern' => '/^(19|20)\d\d(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])$/', 'on' => self::SCENARIO_GETTRANSACTIONDETAIL],
			['endDate', 'checkEndDate', 'on' => self::SCENARIO_GETTRANSACTIONDETAIL],
			['queryType', 'in', 'range' => ['1'], 'on' => self::SCENARIO_GETTRANSACTIONDETAIL],
			['tranType', 'in', 'range' => ['11', '12', '13', '14', '15', '16', '21', '22', '23'], 'on' => self::SCENARIO_GETTRANSACTIONDETAIL],
			['startRecord', 'string', 'length' => [0, 4], 'on' => self::SCENARIO_GETTRANSACTIONDETAIL],
			['pageNumber', 'string', 'length' => [0, 2], 'on' => self::SCENARIO_GETTRANSACTIONDETAIL],

			//账户信息
			[['custNo', 'custAccNo', 'pageNumber', 'startRecord'], 'string', 'on' => self::SCENARIO_GETINFO],
			[['custNo', 'custAccNo', 'pageNumber', 'startRecord'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_GETINFO],
			['custNo', 'string', 'length' => [0, 45], 'on' => self::SCENARIO_GETINFO],
			['custAccNo', 'string', 'length' => [0, 20], 'on' => self::SCENARIO_GETINFO],
			['startRecord', 'string', 'length' => [0, 4], 'on' => self::SCENARIO_GETINFO],
			['pageNumber', 'string', 'length' => [0, 2], 'on' => self::SCENARIO_GETINFO],

			//账号交易流水
			[['custNo', 'startDate', 'endDate'], 'required', 'on' => self::SCENARIO_GETDEALFLOW],
			['custNo', 'string', 'length' => [0, 45], 'on' => self::SCENARIO_GETDEALFLOW],
			[['custNo', 'startDate', 'endDate', 'pageNumber', 'startRecord'], 'string', 'on' => self::SCENARIO_GETDEALFLOW],
			[['custNo', 'startDate', 'endDate', 'pageNumber', 'startRecord'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_GETDEALFLOW],
			['startDate', 'match', 'pattern' => '/^(19|20)\d\d(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])$/', 'on' => self::SCENARIO_GETDEALFLOW],
			['startDate', 'checkStartDate', 'on' => self::SCENARIO_GETDEALFLOW],
			['endDate', 'match', 'pattern' => '/^(19|20)\d\d(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])$/', 'on' => self::SCENARIO_GETDEALFLOW],
			['endDate', 'checkEndDate', 'on' => self::SCENARIO_GETDEALFLOW],
			['startRecord', 'string', 'length' => [0, 4], 'on' => self::SCENARIO_GETDEALFLOW],
			['pageNumber', 'string', 'length' => [0, 2], 'on' => self::SCENARIO_GETDEALFLOW],

			//更新余额
			['custNo', 'required', 'on' => self::SCENARIO_UPDATEBALANCE],
			['custNo', 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_UPDATEBALANCE],
			['custNo', 'string', 'length' => [0, 45], 'on' => self::SCENARIO_UPDATEBALANCE],

			//转账交易
			[['custNo', 'toCustNo', 'tranType', 'tranAmt', 'payPwd'], 'required', 'on' => self::SCENARIO_TRADING],
			[['custNo', 'toCustNo', 'tranType', 'tranAmt', 'freezeNo', 'payPwd', 'memo'], 'string', 'on' => self::SCENARIO_TRADING],
			[['custNo', 'toCustNo', 'tranType', 'tranAmt', 'freezeNo', 'payPwd', 'memo'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_TRADING],
			[['custNo', 'toCustNo'], 'string', 'length' => [0, 45], 'on' => self::SCENARIO_TRADING],
			['tranType', 'in', 'range' => ['BF', 'BG', 'BH', 'BR', 'BS'], 'on' => self::SCENARIO_TRADING],
			['tranType', 'checkFreezeNo', 'on' => self::SCENARIO_TRADING],
			['tranAmt', 'match', 'pattern' => '/^[0-9]{1,13}+(.[0-9]{2})+$/', 'on' => self::SCENARIO_TRADING],
			['freezeNo', 'string', 'length' => [0, 22], 'on' => self::SCENARIO_TRADING],
			['memo', 'string', 'length' => [0, 100], 'on' => self::SCENARIO_TRADING],

			//出金
			[['custNo', 'tranAmt', 'payPwd'], 'required', 'on' => self::SCENARIO_WITHDRAW],
			[['custNo', 'tranAmt', 'payPwd', 'memo', 'preFlg', 'preDate', 'preTime'], 'string', 'on' => self::SCENARIO_WITHDRAW],
			[['custNo', 'tranAmt', 'payPwd', 'memo', 'preFlg', 'preDate', 'preTime'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_WITHDRAW],
			['custNo', 'string', 'length' => [0, 45], 'on' => self::SCENARIO_WITHDRAW],
			['tranAmt', 'match', 'pattern' => '/^[0-9]{1,13}+(.[0-9]{2})+$/', 'on' => self::SCENARIO_WITHDRAW],
			['memo', 'string', 'length' => [0, 100], 'on' => self::SCENARIO_WITHDRAW],
			['preFlg', 'in', 'range' => ['0', '1'], 'on' => self::SCENARIO_WITHDRAW],
			['preDate', 'match', 'pattern' => '/^(19|20)\d\d(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])$/', 'on' => self::SCENARIO_WITHDRAW],
			['preTime', 'in', 'range' => ['100000', '120000', '140000', '160000'], 'on' => self::SCENARIO_WITHDRAW],

			//修改密码
			[['custNo', 'accAction', 'newpayPwd'], 'required', 'on' => self::SCENARIO_EDITPWD],
			[['custNo', 'accAction', 'oldpayPwd', 'newpayPwd'], 'string', 'on' => self::SCENARIO_EDITPWD],
			[['custNo', 'accAction', 'oldpayPwd', 'newpayPwd'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_EDITPWD],
			['custNo', 'string', 'length' => [0, 45], 'on' => self::SCENARIO_EDITPWD],
			['accACtion', 'in', 'range' => ['1', '2'], 'on' => self::SCENARIO_EDITPWD],
			['accACtion', 'checkOldPwd', 'on' => self::SCENARIO_EDITPWD],

			//校验密码
			[['custNo', 'payPwd'], 'required', 'on' => self::SCENARIO_VALIDATEPWD],
			[['custNo', 'payPwd'], 'string', 'on' => self::SCENARIO_VALIDATEPWD],
			[['custNo', 'payPwd'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_VALIDATEPWD],
			['custNo', 'string', 'length' => [0, 45], 'on' => self::SCENARIO_VALIDATEPWD],

			//交易追踪
			[['startDate','endDate'], 'required', 'on' => self::SCENARIO_TRACKING],
			[['startDate','endDate','statusTrack','pageNumber','startRecord'], 'string', 'on' => self::SCENARIO_TRACKING],
			[['startDate','endDate','statusTrack','pageNumber','startRecord'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_TRACKING],
			['startDate', 'match', 'pattern' => '/^(19|20)\d\d(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])$/', 'on' => self::SCENARIO_TRACKING],
			['startDate', 'checkStartDate', 'on' => self::SCENARIO_TRACKING],
			['endDate', 'match', 'pattern' => '/^(19|20)\d\d(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])$/', 'on' => self::SCENARIO_TRACKING],
			['endDate', 'checkEndDate', 'on' => self::SCENARIO_TRACKING],
			['statusTrack','in', 'range' => ['0', '1'], 'on' => self::SCENARIO_TRACKING],
		];
	}

	/**
	 * validateTradePassword(String $custNo, String $password)
	 * 验证交易密码
	 * -------------------------------------------------------
	 * @param String $custNo 客户编号
	 * @param String $password 交易密码
	 * --------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function validateTradePassword($custNo, $password){
		if($this->enableBank === true){
			$this->setScenario(self::SCENARIO_VALIDATEPWD);
			$result = $this->_request([
				'custNo' => (string)$custNo,
				'payPwd' => md5((string)$password),
			]);
			return $result['success'] == 1;
		}
		return true;
	}

	/**
	 * payFreeze(String $custNo, String $toCustNo, String $tranAmt, String $payPwd)
	 * 支付冻结
	 * ----------------------------------------------------------------------------
	 * @param String $custNo 摘牌企业会员编号
	 * @param String $toCustNo 挂牌企业会员编号
	 * @param String $tranAmt 交易融资金额
	 * @param String $payPwd 交易密码
	 * -----------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function payFreeze($custNo, $toCustNo, $tranAmt, $payPwd){
		if($this->enableBank === true){
			list($flag, $data, $error) = $this->trading([
				'custNo'   => (string)$custNo,
				'toCustNo' => (string)$toCustNo,
				'tranAmt'  => (string)$tranAmt,
				'payPwd'   => md5($payPwd),
				'tranType' => 'BS',
				'memo'	 => $custNo . '向' . $toCustNo . '支付冻结' . $tranAmt . '元',
			]);
			return $flag;
		}
		return true;
	}

	/**
	 * unfreeze(String $custNo, String $billId, String $tranAmt, String $payPwd)
	 * 确认收款解冻
	 * -------------------------------------------------------------------------
	 * @param String $custNo 挂牌企业会员编号
	 * @param String $billId 交易票据id
	 * @param String $tranAmt 交易融资金额
	 * @param String $payPwd 交易密码
	 * --------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function unfreeze($custNo, $billId, $tranAmt, $payPwd){
		if($this->enableBank === true){
			if(!$codeInfo = UnfreezeCode::getCode($custNo, $billId)){
				return false;
			}
			if($codeInfo['tranAmt'] != $tranAmt){
				return false;
			}
			list($flag, $data, $error) = $this->trading([
				'custNo'   => (string)$custNo,
				'toCustNo' => (string)$custNo,
				'tranType' => 'BG',
				'tranAmt'  => (string)$codeInfo['tranAmt'],
				'freezeNo' => (string)$codeInfo['unfreeze_code'],
				'payPwd'   => md5($payPwd),
				'memo'	 => $custNo . '解冻' . $tranAmt . '元',
			]);
			return $flag ? UnfreezeCode::useCode($custNo, $billId, $codeInfo['tranAmt'], $codeInfo['unfreeze_code']) : false;
		}
		return true;
	}

	/**
	 * payUnfreeze(String $custNo, String $toCustNo, String $tranAmt, String$payPwd)
	 * 解冻支付
	 * -----------------------------------------------------------------------------
	 * @param String $custNo 挂牌企业会员编号
	 * @param String $toCustNo 融资企业会员编号
	 * @param String $tranAmt 交易融资金额
	 * @param String $payPwd 交易密码
	 * ----------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function payUnfreeze($custNo, $toCustNo, $tranAmt, $payPwd){
		if($this->enableBank === true){
			list($flag, $data, $error) = $this->getFreezeStatus([
				'custNo'	=> (string)$custNo,
				'startDate' => date('Ymd', strtotime("-30 day")),
				'endDate'   => date('Ymd', time()),
			]);
			if(!$flag){
				return false;
			}
			$djCode = false;
			foreach($data as $value){
				if($value['DJAMT'] == $tranAmt && $value['JDTIME'] == '000000' && empty($value['JDDATE'])){
					$djCode = $value['DJCODE'];
					break;
				}
			}
			if(!$djCode){
				return false;
			}
			list($flag, $data, $error) = $this->trading([
				'custNo'   => (string)$custNo,
				'toCustNo' => (string)$toCustNo,
				'tranType' => 'BH',
				'tranAmt'  => (string)$tranAmt,
				'freezeNo' => (string)$djCode,
				'payPwd'   => md5($payPwd),
				'memo'	 => $custNo . '向' . $toCustNo . '解冻支付' . $tranAmt . '元',
			]);
			return $flag;
		}
		return true;
	}

	/**
	 * getUnfreezeCode(String $custNo, String $billId, String $tranAmt)
	 * 获取冻结码
	 * ----------------------------------------------------------------
	 * @param  String $custNo   挂牌企业会员编号
	 * @param  String $billId  交易票据id
	 * @param  String $tranAmt  交易融资金额
	 * -----------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function getUnfreezeCode($custNo, $billId, $tranAmt){
		if($this->enableBank === true){
			if(UnfreezeCode::getBillCode($billId)){
				return false;
			}
			$codes = UnfreezeCode::getCodes($custNo, $tranAmt);
			list($flag, $data, $error) = $this->getFreezeStatus([
				'custNo' => (string)$custNo,
				'startDate' => date('Ymd', strtotime("-30 day")),
				'endDate' => date('Ymd', time()),
			]);
			if(!$flag || !$data){
				return false;
			}
			foreach($data as $value){
				if($value['DJAMT'] == $tranAmt && $value['JDTIME'] == '000000' && empty($value['JDDATE']) && !in_array($value['DJCODE'], $codes)){
					$djCode = $value['DJCODE'];
					break;
				}
			}
			if(!isset($djCode)){
				return false;
			}
			if(!UnfreezeCode::addCode($custNo, $billId, $tranAmt, $djCode)){
				return false;
			}
		}
		return true;
	}

	/**
	 * changeTradePassword(String $custNo, String $oldpayPwd, String $newpayPwd)
	 * 修改密码
	 * -------------------------------------------------------------------------
	 * @param String $custNo 企业会员编号
	 * @param String $oldpayPwd 旧密码
	 * @param String $newpayPwd 新密码
	 * -----------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function changeTradePassword($custNo, $oldpayPwd, $newpayPwd){
		if($this->enableBank === true){
			list($flag, $data, $error) = $this->getInfo(['custNo' => (string)$custNo]);
			if(!$flag || $data['rows'][0]['payPwdType'] == 0){
				return false;
			}
			list($flag, $data, $error) = $this->editPwd([
				'custNo'	=> (string)$custNo,
				'accAction' => '2',
				'oldpayPwd' => md5($oldpayPwd),
				'newpayPwd' => md5($newpayPwd),
			]);
			return $flag;
		}
		return true;
	}

	/**
	 * setTradePassword(String $custNo, String $newpayPwd)
	 * 设置交易密码
	 * ---------------------------------------------------
	 * @param  String $custNo 企业会员编号
	 * @param  String $newpayPwd 新密码
	 * -----------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function setTradePassword($custNo, $newpayPwd){
		if($this->enableBank === true){
			list($flag, $data, $error) = $this->getInfo(['custNo' => (string)$custNo]);
			if(!$flag || $userData['rows'][0]['payPwdType'] == 1){
				return false;
			}
			list($flag, $data, $error) = $this->editPwd([
				'custNo' => (string)$custNo,
				'accAction' => '1',
				'newpayPwd' => md5($newpayPwd),
			]);
			return $flag;
		}
		return true;
	}

	/**
	 * tradePasswordStatus(String $custNo)
	 * 交易密码状态
	 * -----------------------------------
	 * @param  String $custNo 企业会员编号
	 * -----------------------------------
	 * @return String
	 * @author Verdient。
	 */
	public function tradePasswordStatus(){
		if($this->enableBank === true){
			list($flag, $data, $error) = $this->getInfo(['custNo' => (string)$custNo]);
			return ($flag && isset($userData['rows'][0]['payPwdType'])) ? (string)$userData['rows'][0]['payPwdType'] : false;
		}
		return '1';
	}

	/**
	 * getCityList(String $type, String $node)
	 * 获取城市列表
	 * ---------------------------------------
	 * @param String $type 城市类型
	 * @param String $node 城市节点
	 * ----------------------------
	 * @return Array
	 * @author Verident。
	 */
	public function getCityList($type, $node){
		if($this->enableBank === true){
			$model = new Banklist;
			list($flag, $result, $error) = $model->getCity(['cityType' => (string)$type, 'cityNode' => (string)$node]);
			return $flag ? $result : [];
		}
		$map = [
			'1' => [
				'' => [
					'0' => [
						'cityName' => '浙江',
						'cityNode' => '3310'
					]
				]
			],
			'2' => [
				'3310' => [
					'0' => [
						'cityType' => '2',
						'cityName' => '杭州市',
						'cityCode' => '331000'
					],
					'1' => [
						'cityType' => '2',
						'cityName' => '宁波市',
						'cityCode' => '332000'
					],
					'2' => [
						'cityType' => '2',
						'cityName' => '温州市',
						'cityCode' => '333000'
					],
					'3' => [
						'cityType' => '2',
						'cityName' => '嘉兴市',
						'cityCode' => '335000'
					],
					'4' => [
						'cityType' => '2',
						'cityName' => '湖州市',
						'cityCode' => '336000'
					],
					'5' => [
						'cityType' => '2',
						'cityName' => '绍兴市',
						'cityCode' => '337000'
					],
					'6' => [
						'cityType' => '2',
						'cityName' => '金华市',
						'cityCode' => '338000'
					],
					'7' => [
						'cityType' => '2',
						'cityName' => '衢州市',
						'cityCode' => '341000'
					],
					'8' => [
						'cityType' => '2',
						'cityName' => '舟山市',
						'cityCode' => '342000'
					],
					'9' => [
						'cityType' => '2',
						'cityName' => '丽水市',
						'cityCode' => '343000'
					],
					'10' => [
						'cityType' => '2',
						'cityName' => '台州市',
						'cityCode' => '345000'
					],
				],
			],
			'3' => [
				'331000' => [
					'0' => [
						'cityType' => '2',
						'cityName' => '杭州市',
						'cityCode' => '3310'
					],
					'1' => [
						'cityType' => '3',
						'cityName' => '淳安县',
						'cityCode' => '3310'
					],
					'2' => [
						'cityType' => '3',
						'cityName' => '建德市',
						'cityCode' => '3310'
					],
					'3' => [
						'cityType' => '3',
						'cityName' => '临安市',
						'cityCode' => '3310'
					],
					'4' => [
						'cityType' => '3',
						'cityName' => '富阳市',
						'cityCode' => '3310'
					],
					'5' => [
						'cityType' => '3',
						'cityName' => '桐庐县',
						'cityCode' => '3310'
					]
				]

			]
		];
		return isset($map[$type][$node]) ? $map[$type][$node] : [];
	}

	/**
	 * getBankList()
	 * 获取银行列表
	 * -------------
	 * @return Array
	 * @author Verdient。
	 */
	public function getBankList(){
		if($this->enableBank === true){
			$model = new Banklist;
			list($flag, $result, $error) = $model->getBank();
			return $flag ? $result : [];
		}
		return [
			'0' => [
				'bankCode'  => '102',
				'bankName'  => '中国工商银行',
				'SBankCode' => '102100099996'
			],
			'1' => [
				'bankCode'  => '103',
				'bankName'  => '中国农业银行',
				'SBankCode' => '103100000026'
			],
			'2' => [
				'bankCode'  => '104',
				'bankName'  => '中国银行',
				'SBankCode' => '104100000004'
			],
			'3' => [
				'bankCode'  => '105',
				'bankName'  => '中国建设银行',
				'SBankCode' => '105100000017'
			],
			'4' => [
				'bankCode'  => '301',
				'bankName'  => '交通银行',
				'SBankCode' => '301290000007'
			],
			'5' => [
				'bankCode'  => '302',
				'bankName'  => '中信银行',
				'SBankCode' => '302100011000'
			]
		];
	}

	/**
	 * getBankBranchList(String $bankCode, String $cityCode)
	 * 获取支行列表
	 * -----------------------------------------------------
	 * @param String $bankCode 银行代码
	 * @param String $cityCode 城市代码
	 * --------------------------------
	 * @return Array
	 * @author Verident。
	 */
	public function getBankBranchList($bankCode, $cityCode){
		if($this->enableBank === true){
			$model = new Banklist;
			list($flag, $result, $error) = $model->getBankBranch(['bankCode' => (string)$bankCode, 'cityCode' => (string)$cityCode]);
			return $flag ? $result : [];
		}
		return [
			'0' => [
				'bankno'   => '102331000014',
				'bankname' => '中国工商银行股份有限公司浙江省分行(不对外办理业务)',
			],
			'1' => [
				'bankno'   => '102331000217',
				'bankname' => '中国工商银行股份有限公司杭州白马支行',
			],
			'2' => [
				'bankno'   => '102331000241',
				'bankname' => '中国工商银行股份有限公司杭州卖鱼桥支行',
			],
			'3' => [
				'bankno'   => '102331000250',
				'bankname' => '中国工商银行股份有限公司杭州中河支行',
			],
			'4' => [
				'bankno'   => '102331000268',
				'bankname' => '中国工商银行股份有限公司杭州康华支行',
			],
		];
	}

	/**
	 * getPaylist(String $custNo, String $startRecord, String $pageNumbe)
	 * 充值获取银行入金列表
	 * ------------------------------------------------------------------
	 * @param  String $custNo  提款企业会员编号
	 * ----------------------------------------
	 * @return Array / Boolean
	 * @author Verdient。
	 */
	public function getPaylist($custNo, $startRecord, $pageNumber){
		if($this->enableBank === true){
			list($flag, $data, $error) = $this->getTransactionDetail([
				'custNo' => (string)$custNo,
				'startDate' => date('Ymd', strtotime("-30 day")),
				'endDate' => date('Ymd'),
				'tranType' => '23',
				'startRecord' => (string)$startRecord,
				'pageNumber' => (string)$pageNumber,
				'opter' => (string)$custNo,
			]);
			return $flag ? $data : false;
		}
		return [];
	}

	/**
	 * updateBalance()
	 * 更新账户余额
	 * ---------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function updateBalance($custNo){
		if($this->enableBank === true){
			$this->setScenario(self::SCENARIO_UPDATEBALANCE);
			$content = $this->_request(['custNo' => (string)$custNo]);
			if(!$error = $this->_getError() && $content['success'] == 1){
				return $content;
			}
			return false;
		}
		return true;
	}

	/**
	 * updateCashInAccount(String $custNo)
	 * 更新入金账户
	 * ----------------------------------
	 * @param String $custNo 会员企业编号
	 * ----------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function updateCashInAccount($custNo){
		if($this->enableBank === true){
			$model = new CashInAccount;
			$model->custNo = $custNo;
			$model->status = 0;
			return $model->save();
		}
		return true;
	}

	/**
	 * accessed(String $custNo)
	 * 将用户设置为已操作
	 * ------------------------
	 * @param String $custNo 会员企业编号
	 * ----------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function accessed($custNo){
		if($this->enableBank === true){
			if(!$model = CashInAccount::findOne(['custNo' => $custNo, 'status' => 0])){
				return false;
			}
			$model->status = 1;
			return $model->save();
		}
		return true;
	}

	/**
	 * cashOut(String $custNo, String $tranAmt, String $payPwd)
	 * 出金
	 * --------------------------------------------------------
	 * @param  String $custNo  提款企业会员编号
	 * @param  String $tranAmt 提款金额
	 * @param  String $payPwd  交易密码
	 * ----------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function cashOut($custNo, $tranAmt, $payPwd){
		if($this->enableBank === true){
			list($flag, $data, $error) = $this->withdraw([
				'custNo'  => (string)$custNo,
				'tranAmt' => (string)$tranAmt,
				'payPwd'  => md5($payPwd),
				'demo'	=> $custNo . '提现' . $tranAmt . '元',
			]);
			return $flag;
		}
		return true;
	}

	/**
	 * 校验开始日期
	 * @author CGA
	 */
	public function checkStartDate($attribute, $params){
		$now = strtotime(date('Y-m-d'));
		$start = strtotime($this->startDate);
		if(!$start){
			$this->addError($attribute, '开始日期是无效的');
		}
		$differ = $now - $start - 30 * 24 * 3600;
		if($differ > 0){
			$this->addError($attribute, '开始日期跨度不能超过30天');
		}
	}

	/**
	 * 校验结算日期
	 * @author CGA
	 */
	public function checkEndDate($attribute, $params){
		$now = strtotime(date('Y-m-d'));
		$end = strtotime($this->endDate);
		if(!$end){
			$this->addError($attribute, '结束日期是无效的');
		}
		$differ = $end - $now;
		if($differ > 0){
			$this->addError($attribute, '结束日期不能超出当前日期');
		}
	}

	/**
	 * 校验冻结编码
	 * @author CGA
	 */
	public function checkFreezeNo($attribute, $params){
		if (($this->tranType == 'BG' || $this->tranType == 'BH') && empty($this->freezeNo)) {
			$this->addError('freezeNo', '该交易类型解冻码不能为空');
		}
	}

	/**
	 * 校验原密码
	 * @author CGA
	 */
	public function checkOldPwd($attribute, $params){
		if (($this->accACtion == '2') && empty($this->oldpayPwd)) {
			$this->addError('oldpayPwd', '原密码不能为空');
		} else {
			if ($this->oldpayPwd == $this->newpayPwd) {
				$this->addError('newpayPwd', '新旧密码不能相同');
			}
		}
	}

	/**
	 * 账户余额查询
	 * @author CGA
	 */
	public function getBalance($posts = []){
		$flag = false;
		$error = '';
		$result = [];
		$this->setScenario(self::SCENARIO_GETBALANCE);
		$content = $this->_request($posts);
		if (!$error = $this->_getError()) {

			if ($content['success'] == 1) {
				$flag = true;
				$result = $content['data']['dataList']['list'][0];
			} else {
				$error = $content['msg'];
			}
		}
		return [$flag, $result, $error];
	}

	/**
	 * 账户冻结信息查询
	 * @author CGA
	 */
	public function getFreeze($posts = []){
		$flag = false;
		$error = '';
		$result = [];
		$this->setScenario(self::SCENARIO_GETFREEZE);
		$content = $this->_request($posts);
		if (!$error = $this->_getError()) {
			if ($content['success'] == 1) {
				$flag = true;
				$result = $content['data']['dataList']['list'][0];
			} else {
				$error = $content['msg'];
			}
		}

		return [$flag, $result, $error];
	}

	/**
	 * 冻结交易状态查询
	 * @author CGA
	 */
	public function getFreezeStatus($posts = []){
		$flag = false;
		$error = '';
		$result = [];
		$this->setScenario(self::SCENARIO_GETFREEZESTATUS);
		$content = $this->_request($posts);
		if (!$error = $this->_getError()) {
			if ($content['success'] == 1) {
				$flag = true;
				$result = $content['data']['dataList']['list'];
			} else {
				$error = $content['msg'];
			}
		}
		return [$flag, $result, $error];
	}

	/**
	 * 交易明细查询
	 * @author CGA
	 */
	public function getTransactionDetail($posts = []){
		$flag = false;
		$error = '';
		$result = [];
		$this->setScenario(self::SCENARIO_GETTRANSACTIONDETAIL);
		$content = $this->_request($posts);
		if(!$error = $this->_getError()){
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
	 * 获取账号信息
	 * @author CGA
	 */
	public function getInfo($posts = []){
		$flag = false;
		$error = '';
		$result = [];
		$this->setScenario(self::SCENARIO_GETINFO);
		$content = $this->_request($posts);
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
	 * 获取交易流水
	 * @author CGA
	 */
	public function getDealflow($posts = []){
		$flag = false;
		$error = '';
		$result = [];
		$this->setScenario(self::SCENARIO_GETDEALFLOW);
		$content = $this->_request($posts);
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
	 * 转账交易
	 * @author CGA
	 */
	protected function trading($posts = []){
		$flag = false;
		$error = '';
		$result = [];
		$this->setScenario(self::SCENARIO_TRADING);
		$content = $this->_request($posts);
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
	 * 出金
	 * @author CGA
	 */
	public function withdraw($posts = []){
		$flag = false;
		$error = '';
		$result = [];
		$this->setScenario(self::SCENARIO_WITHDRAW);
		$content = $this->_request($posts);
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
	 * 更改密码
	 * @author CGA
	 */
	public function editPwd($posts = []){
		$flag = false;
		$error = '';
		$result = [];
		$this->setScenario(self::SCENARIO_EDITPWD);
		$content = $this->_request($posts);
		if (!$error = $this->_getError()) {
			if ($content['success'] == 1) {
				$flag = true;
				$result = $content;
			} else {
				$error = $content['msg'];
			}
		}
		return [$flag, $result, $error];
	}

	/**
	 * 追踪交易
	 * @author CGA
	 */
	public function tracking($posts = []){
		$flag = false;
		$error = '';
		$result = [];
		$this->setScenario(self::SCENARIO_TRACKING);
		$content = $this->_request($posts);
		if (!$error = $this->_getError()) {
			if ($content['success'] == 1) {
				$flag = true;
				$result = $content;
			} else {
				$error = $content['msg'];
			}
		}
		return [$flag, $result, $error];
	}

	/**
	 * 返回模型错误
	 * @author CGA
	 */
	protected function _getError(){
		if($this->hasErrors()){
			$error = null;
			$errors = $this->getErrors();
			foreach ($errors as $value) {
				$error = $value[0];
				break;
			}
			return $error;
		}else{
			return false;
		}
	}

	/**
	 * 访问接口
	 * @author CGA
	 */
	protected function _request($data = []){
		$this->attributes = $data;
		if($this->validate()){
			$activeAttributes = $this->activeAttributes();
			$bodys = [];
			foreach($activeAttributes as $key => $value){
				if(!empty($this->$value)){
					$bodys[$value] = $this->$value;
				}
			}
			$actionMap = eval(self::ACTION_MAP);
			$params = array_merge([
				'opter' => isset($data['opter']) ? $data['opter'] : Yii::$app->OAuth2->userInfo->enterprise_id,
				'action' => $actionMap[$this->scenario],
				'bType' => '1',
			], $bodys);
			$result = $this->_send($params);
			if(isset($result['success'])){
				return $result;
			}else{
				$this->addError('system', '银行接口访问失败');
				return false;
			}
		}
	}

	/**
	 * _encryption([Array $data = []])
	 * 加密数据
	 * --------------------------
	 * @param Array $data 发送的数据
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	protected function _encryption($data = []){
		$post_json = Json::encode($data, JSON_UNESCAPED_UNICODE);
		$post_strArray = str_split(preg_replace('/([\x80-\xff]*)/i', '', $post_json));
		sort($post_strArray);
		$post_str = implode("", $post_strArray);
		$signature = sha1($this->encryptionKey . $post_str);
		$data['signature'] = $signature;
		return $data;
	}

	/**
	 * _send([Array $data = []])
	 * 发送数据
	 * --------------------------
	 * @param Array $data 发送的数据
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	protected function _send(Array $data = []){
		Yii::$app->SOAP->setVarData(['account' => $this->account, 'password' => $this->password]);
		Yii::$app->SOAP->setData($this->_encryption($data), 'JSON');
		return Yii::$app->SOAP->fetch('swopBankInfo', $this->apiUrl, 'JSON');
	}
}
