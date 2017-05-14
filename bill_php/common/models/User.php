<?php
namespace common\models;

use Yii;
use common\behaviors\ExceptionBehavior;
use common\components\ActiveRecord;

/**
 * User Model
 * 用户 模型
 * ----------
 * @version 1.0.0
 * @author Verdient。
 */
class User extends ActiveRecord
{
	/**
	 * @type const TYPE_FINANCE
	 * 财务公司
	 * ------------------------
	 * @author Verdient。
	 */
	const TYPE_FINANCE = 1;

	/**
	 * @type const TYPE_REVIEW
	 * 财务复核
	 * -----------------------
	 * @author Verdient。
	 */
	const TYPE_REVIEW = 2;

	/**
	 * @type const TYPE_MEMBER
	 * 成员公司
	 * -----------------------
	 * @author Verdient。
	 */
	const TYPE_MEMBER = 3;

	/**
	 * @type const TYPE_EXTERNAL
	 * 外部企业
	 * -------------------------
	 * @author Verdient。
	 */
	const TYPE_EXTERNAL = 4;

	/**
	 * @type const TYPE_ADMIN
	 * 管理员
	 * ----------------------
	 * @author Verdient。
	 */
	const TYPE_ADMIN = 5;

	/**
	 * @userTag const USERTAG_FINANCE
	 * 财务公司
	 * ------------------------------
	 * @author Verdient。
	 */
	const USERTAG_FINANCE = 'FINANCE';

	/**
	 * @userTag const USERTAG_REVIEW
	 * 财务复核
	 * -----------------------------
	 * @author Verdient。
	 */
	const USERTAG_REVIEW = 'FINANCE_CHECK';

	/**
	 * @userTag const USERTAG_MEMBER
	 * 成员公司
	 * ------------------------------
	 * @author Verdient。
	 */
	const USERTAG_MEMBER = 'FINANCE_MEMBER';

	/**
	 * @userTag const USERTAG_EXTERNAL
	 * 外部公司
	 * -------------------------------
	 * @author Verdient。
	 */
	const USERTAG_EXTERNAL = 'TICKET';

	/**
	 * @userTag const USERTAG_ADMIN
	 * 管理员
	 * ----------------------------
	 * @author Verdient。
	 */
	const USERTAG_ADMIN = 'ADMIN';

	/**
	 * @var protected $_enterpriseInfo
	 * 企业信息
	 * -------------------------------
	 * @author Verdient。
	 */
	protected $_enterpriseInfo;

	/**
	 * @var protected $_bankCompanyInfo
	 * 银行企业信息
	 * --------------------------------
	 * @author Verdient。
	 */
	protected $_bankCompanyInfo;

	/**
	 * @var protected $_tradeCompanyInfo
	 * 公司交易信息
	 * ---------------------------------
	 * @author Verdient。
	 */
	protected $_tradeCompanyInfo;

	/**
	 * @var protected $_companyBaseInfo
	 * 公司基本信息
	 * --------------------------------
	 * @author Verdient。
	 */
	protected $_companyBaseInfo;

	/**
	 * @var protected $_agentInfo
	 * 代理人信息
	 * --------------------------
	 * @author Verdient。
	 */
	protected $_agentInfo;

	/**
	 * @var protected $_publicKeyInfo
	 * 公钥信息
	 * ------------------------------
	 * @author Verdient。
	 */
	protected $_publicKeyInfo;

	/**
	 * @var protected $_members
	 * 成员公司集合
	 * ------------------------
	 * @author Verdient。
	 */
	protected $_members;

	/**
	 * @var protected $_externals
	 * 外部企业集合
	 * --------------------------
	 * @author Verdient。
	 */
	protected $_externals;

	/**
	 * getDb()
	 * 获取数据库配置
	 * --------------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public static function getDb(){
		return Yii::$app->db2;
	}

	/**
	 * tableName()
	 * 设置表名称
	 * -----------
	 * @inheritdoc
	 * -----------
	 * @return String
	 * @author Verdient。
	 */
	public static function tableName(){
		return '{{t_scf_user}}';
	}

	/**
	 * behaviors()
	 * 行为设置
	 * -----------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public function behaviors(){
		return [
			'ExceptionBehavior' => ExceptionBehavior::className()
		];
	}

	/**
	 * validatePassword(String $attribute, Mixed $params)
	 * 验证密码
	 * --------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validatePassword($attribute, $params){
		if(!$this->hasErrors()){
			$userInfo = Yii::$app->OAuth2->userInfo;
			if($this->encryptPassword($this->password, $userInfo->secret_key) != strtoupper($userInfo->encryptPwd)){
				$this->addError($attribute, 31008);
			}
		}
	}

	/**
	 * encryptPassword(String $password, String $secretKey)
	 * 加密密码
	 * ----------------------------------------------------
	 * @param String $password 密码
	 * @param String $secretKey 密钥
	 * -----------------------------
	 * @return String
	 * @author Verdient。
	 */
	public function encryptPassword($password, $secretKey){
		return strtoupper(md5($secretKey . $password));
	}

	/**
	 * getType()
	 * 获取用户类型
	 * ------------
	 * @return Integer / Boolean
	 * @author Verdient。
	 */
	public function getType(){
		$map = [self::USERTAG_FINANCE => self::TYPE_FINANCE, self::USERTAG_REVIEW => self::TYPE_REVIEW, self::USERTAG_MEMBER => self::TYPE_MEMBER, self::USERTAG_EXTERNAL => self::TYPE_EXTERNAL, self::USERTAG_ADMIN => self::TYPE_ADMIN];
		return isset($map[$this->userTag]) ? $map[$this->userTag] : false;
	}

	/**
	 * getAddress()
	 * 获取公钥地址
	 * ------------
	 * @return Integer / Boolean
	 * @author Verdient。
	 */
	public function getAddress(){
		return $this->publicKeyInfo ? $this->publicKeyInfo->address : false;
	}

	/**
	 * getRandom()
	 * 获取随机码
	 * -----------
	 * @return Integer / Boolean
	 * @author Verdient。
	 */
	public function getRandom(){
		return $this->publicKeyInfo ? $this->publicKeyInfo->random : false;
	}

	/**
	 * getStatus()
	 * 获取状态
	 * -----------
	 * @return Integer / Boolean
	 * @author Verdient。
	 */
	public function getStatus(){
		return $this->publicKeyInfo ? $this->publicKeyInfo->status : false;
	}

	/**
	 * getIsCertificated()
	 * 获取四要素验证状态
	 * -------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function getIsCertificated(){
		return $this->agentInfo ? true : false;
	}

	/**
	 * getIsAuthenticated()
	 * 获取企业认证状态
	 * --------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function getIsAuthenticated(){
		return $this->enterpriseInfo && $this->enterpriseInfo->status == Enterprise::STATUS_REGULAR;
	}

	/**
	 * getEnterpriseInfo()
	 * 获取企业信息
	 * -------------------
	 * @return Object / Boolean
	 * @author Verdient。
	 */
	public function getEnterpriseInfo(){
		if(!$this->_enterpriseInfo){
			$this->_enterpriseInfo = Enterprise::findOne(['enterprise_id' => $this->enterprise_id]);
		}
		return $this->_enterpriseInfo;
	}

	/**
	 * getPublicKeyInfo()
	 * 获取公钥信息
	 * ------------------
	 * @return Integer / Boolean
	 * @author Verdient。
	 */
	public function getPublicKeyInfo(){
		if(!$this->_publicKeyInfo){
			$this->_publicKeyInfo = PublicKey::findOne(['user_id' => $this->id]);
		}
		return $this->_publicKeyInfo;
	}

	/**
	 * getBankCompanyInfo()
	 * 获取公司银行信息
	 * --------------------
	 * @return Object / Boolean
	 * @author Verdient。
	 */
	public function getBankCompanyInfo(){
		if(!$this->_bankCompanyInfo){
			$this->_bankCompanyInfo = BankCompany::findOne(['companyId' => $this->enterprise_id]);
		}
		return $this->_bankCompanyInfo;
	}

	/**
	 * getTradeCompanyInfo()
	 * 获取公司交易信息
	 * ---------------------
	 * @return Object / Boolean
	 * @author Verdient。
	 */
	public function getTradeCompanyInfo(){
		if(!$this->_tradeCompanyInfo){
			$this->_tradeCompanyInfo = TradeCompany::findOne(['id' => $this->enterprise_id]);
		}
		return $this->_tradeCompanyInfo;
	}

	/**
	 * getCompanyBaseInfo()
	 * 获取基本信息
	 * --------------------
	 * @return Object / Boolean
	 * @author Verdient。
	 */
	public function getCompanyBaseInfo(){
		if(!$this->_companyBaseInfo){
			$this->_companyBaseInfo = CompanyBaseInfo::findOne(['enterprise_id' => $this->enterprise_id]);
		}
		return $this->_companyBaseInfo;
	}

	/**
	 * getAgentInfo()
	 * 获取经办人信息
	 * --------------
	 * @return Object / Boolean
	 * @author Verdient。
	 */
	public function getAgentInfo(){
		if(!$this->_agentInfo){
			$this->_agentInfo = Agent::findOne(['access_token' => Yii::$app->OAuth2->accessTokenInfo['access_token']]);
		}
		return $this->_agentInfo;
	}

	/**
	 * getBankCardNumber()
	 * 获取银行卡号
	 * -------------------
	 * @return String / Boolean
	 * @author Verdient。
	 */
	public function getBankCardNumber(){
		return $this->bankCompanyInfo ?  $this->bankCompanyInfo->account : null;
	}

	/**
	 * getMembers()
	 * 获取成员公司集合
	 * ----------------
	 * @return Array / Boolean
	 * @author Verdient。
	 */
	public function getMembers(){
		if(!$this->_members){
			switch($this->type){
				case self::TYPE_FINANCE:
					$belong = $this->enterprise_id;
					$this->_members = [];
					break;

				case self::TYPE_REVIEW:
					$belong = $this->belong_com;
					$this->_members = [];
					break;

				default:
					return false;
					break;
			}
			if($members = self::find()
				->select('id')
				->where(['belong_com' => $belong, 'userTag' => User::USERTAG_MEMBER])
				->asArray()
				->all()
			){
				foreach($members as $key => $value){
					$this->_members[] = $value['id'];
				}
			}
		}
		return $this->_members;
	}

	/**
	 * getSelfAndMembers()
	 * 获取自己ID和成员公司集合
	 * ------------------------
	 * @return Array / Integer
	 * @author Verdient。
	 */
	public function getSelfAndMembers(){
		if($this->members){
			switch($this->type){
				case self::TYPE_FINANCE:
					$self = $this->id;
					break;

				case self::TYPE_REVIEW:
					if($self = self::find()->select('id')->where(['enterprise_id' => $this->belong_com])->asArray()->one()){
						$self = $self['id'];
					}else{
						$self = [];
					}
					break;

				default:
					$self = [];
					break;
			}
			$selfAndMembers = $this->members;
			array_push($selfAndMembers, $self);
			return $selfAndMembers;
		}
		return $this->id;
	}

	/**
	 * getEnabledUser()
	 * 获取允许操作的用户集合
	 * ----------------------
	 * @return Array / Integer
	 * @author Verdient。
	 */
	public function getEnabledUser(){
		switch($this->type){
			case self::TYPE_FINANCE:
				return $this->selfAndMembers;
				break;

			case self::TYPE_REVIEW:
				return $this->belong_com;
				break;

			case self::TYPE_MEMBER: case self::TYPE_EXTERNAL:
				return $this->id;
				break;

			default:
				return false;
				break;
		}
	}

	/**
	 * getExternals()
	 * 获取外部公司集合
	 * ----------------
	 * @return Array / Boolean
	 * @author Verdient。
	 */
	public function getExternals(){
		if(!$this->_externals){
			if($externals = self::find()
				->select('id')
				->where(['userTag' => User::USERTAG_EXTERNAL])
				->asArray()
				->all()
			){
				foreach($externals as $key => $value){
					$this->_externals[] = $value['id'];
				}
			}else{
				$this->_externals = [];
			}
		}
		return $this->_externals;
	}

	/**
	 * getParent()
	 * 获取父ID
	 * -----------
	 * @return Integer / Boolean
	 * @author Verdient。
	 */
	public function getParent(){
		switch($this->type){
			case self::TYPE_MEMBER: case self::TYPE_REVIEW: case self::TYPE_EXTERNAL:
				if($user = self::find()
					->select(['id'])
					->where(['enterprise_id' => $this->belong_com, 'userTag' => self::USERTAG_FINANCE])
					->asArray()
					->one()
				){
					return $user['id'];
				}
				return false;
				break;

			case self::TYPE_FINANCE:
				return $this->id;
				break;
		}
	}

	/**
	 * getTradeCompany()
	 * 关联交易公司表
	 * -----------------
	 * @return Object
	 * @author Verdient。
	 */
	public function getTradeCompany(){
		return $this->hasOne(TradeCompany::className(), ['id' => 'enterprise_id']);
	}

	/**
	 * getPublicKey()
	 * 关联公钥表
	 * ------------
	 * @return Object
	 * @author Verdient。
	 */
	public function getPublicKey(){
		return $this->hasOne(PublicKey::className(), ['user_id' => 'id']);
	}
}
