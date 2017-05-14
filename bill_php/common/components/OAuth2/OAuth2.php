<?php
namespace common\components\OAuth2;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use common\components\Exception;
use common\models\User;

/**
 * OAuth2 Model
 * OAuth2 模型
 * ------------
 * @version 1.1.0
 * @author Verdient。
 */
class OAuth2 extends Model
{

	/**
	 * @var public $useOpenID
	 * 是否启用OpenID
	 * ----------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $useOpenID = false;

	/**
	 * @var public $useClient
	 * 是否启用Client
	 * ----------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $useClient = true;

	/**
	 * @var public $useRefreshToken
	 * 是否启用RefreshToken
	 * ----------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $useRefreshToken = true;

	/**
	 * @var public $deleteOldAccessToken
	 * 是否删除以前的AccessToken
	 * ---------------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $deleteOldAccessToken = false;

	/**
	 * @var public $deleteOldRefreshToken
	 * 是否删除以前的RefreshToken
	 * ----------------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $deleteOldRefreshToken = false;

	/**
	 * @var public $deleteOldAuthorizationCode
	 * 是否删除以前的AuthorizationCode
	 * ---------------------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $deleteOldAuthorizationCode = false;

	/**
	 * @var public $accessTokenLength
	 * AccessToken的长度
	 * ------------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $accessTokenLength = 40;

	/**
	 * @var public $refreshTokenLength
	 * RefreshToken的长度
	 * -------------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $refreshTokenLength = 40;

	/**
	 * @var public $authorizationCodeLength
	 * AuthorizationCode的长度
	 * ------------------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $authorizationCodeLength = 40;

	/**
	 * @var public $openIDLength
	 * OpenID的长度
	 * -------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $openIDLength = 40;

	/**
	 * @var public $accessTokenExpires
	 * AccessToken有效时间
	 * -------------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $accessTokenExpires = 2592000;

	/**
	 * @var public $refreshTokenExpires
	 * RefreshToken有效时间
	 * --------------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $refreshTokenExpires = 15552000;

	/**
	 * @var public $authorizationCodeExpires
	 * AuthorizationCode有效时间
	 * -------------------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $authorizationCodeExpires = 30;

	/**
	 * @var public $userModel
	 * 用户模型
	 * ----------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $userModel = 'userModel';

	/**
	 * @var public $clientID
	 * 客户端ID
	 * ---------------------
	 * @method GET
	 * @author Verdient。
	 */
	public $clientID;

	/**
	 * @var public $clientSecret
	 * 客户端密钥
	 * -------------------------
	 * @method GET
	 * @author Verdient。
	 */
	public $clientSecret;

	/**
	 * @var public $redirectUrl
	 * 跳转地址
	 * -------------------------
	 * @method GET
	 * @author Verdient。
	 */
	public $redirectUrl;

	/**
	 * @var public $grantType
	 * 认证方式
	 * ----------------------
	 * @method GET
	 * @author Verdient。
	 */
	public $grantType;

	/**
	 * @var public $authorizationCode
	 * 授权码
	 * ------------------------------
	 * @method POST
	 * @author Verdient。
	 */
	public $authorizationCode;

	/**
	 * @var public $refreshToken
	 * 刷新密钥
	 * -------------------------
	 * @method POST
	 * @author Verdient。
	 */
	public $refreshToken;

	/**
	 * @var public $email
	 * 用户名
	 * ------------------
	 * @method POST
	 * @author Verdient。
	 */
	public $email;

	/**
	 * @var public $password
	 * 密码
	 * ---------------------
	 * @method POST
	 * @author Verdient。
	 */
	public $password;

	/**
	 * @var public $accessToken
	 * 授权密钥
	 * ------------------------
	 * @method GET
	 * @author Verdient。
	 */
	public $accessToken;

	/**
	 * @var public $openID
	 * openID
	 * -------------------
	 * @method GET
	 * @author Verdient。
	 */
	public $openID;

	/**
	 * @var public $scope
	 * 权限
	 * ------------------
	 * @author Verdient。
	 */
	public $scope = 'scope';

	/**
	 * @var public $userId
	 * 用户ID
	 * -------------------
	 * @author Verdient。
	 */
	public $userId;

	/**
	 * @var protected $_authInfo
	 * 认证信息
	 * -------------------------
	 * 只读
	 * ----
	 * @author Verdient。
	 */
	protected $_authInfo;

	/**
	 * @var protected $_userInfo
	 * 用户信息
	 * -------------------------
	 * 只读
	 * ----
	 * @author Verdient。
	 */
	protected $_userInfo;

	/**
	 * @var protected $_isOAuth2Request
	 * 是否是OAuth2请求
	 * --------------------------------
	 * 只读
	 * ----
	 * @author Verdient。
	 */
	protected $_isOAuth2Request = false;

	/**
	 * @var protected $_isOAuth2Authorized
	 * OAuth2是否认证通过
	 * -----------------------------------
	 * 只读
	 * ----
	 * @author Verdient。
	 */
	protected $_isOAuth2Authorized = false;

	/**
	 * @var protected $_client
	 * 客户端
	 * -------------------------
	 * 保护
	 * ----
	 * @author Verdient。
	 */
	protected $_client;

	/**
	 * @var protected $_user
	 * 用户
	 * -------------------------
	 * 只读
	 * ----
	 * @author Verdient。
	 */
	protected $_user;

	/**
	 * @var protected $_accessToken
	 * 授权密钥信息
	 * ----------------------------
	 * 保护
	 * ----
	 * @author Verdient。
	 */
	protected $_accessToken;

	/**
	 * @var protected $_grant_type
	 * 认证方式
	 * ---------------------------
	 * 私有
	 * ----
	 * @author Verdient。
	 */
	private $_grant_type = ['authorization_code' => 1, 'refresh_token' => 2, 'user_credentials' => 3];

	/**
	 * init()
	 * 初始化
	 * ------
	 * @inheritdoc
	 * -----------
	 * 在初始化时将传入的数据载入，准备校验
	 * ------------------------------------
	 * @author Verdient。
	 */
	public function init(){
		$this->clientID = Yii::$app->request->get('client_id');
		$this->clientSecret = Yii::$app->request->get('client_secret');
		$this->redirectUrl = Yii::$app->request->get('redirect_url');
		$this->grantType = Yii::$app->request->get('grant_type', 'user_credentials');
		$this->openID = Yii::$app->request->get('open_id');
		$this->authorizationCode = Yii::$app->request->post('authorization_code');
		$this->refreshToken = Yii::$app->request->post('refresh_token');
		$this->email = Yii::$app->request->post('email');
		$this->password = Yii::$app->request->post('password');
		$this->accessToken = Yii::$app->request->getHeaders()->get('Authorization');
		$this->userId = Yii::$app->request->post('user_id');
		if($this->userModel === 'userModel'){
			$this->userModel = Yii::createObject(Yii::$app->user->identityClass);
		}
	}

	/**
	 * scenarios()
	 * 设置场景
	 * -----------
	 * @inheritdoc
	 * -----------
	 * token => 获取token，细分为:authorization_code, refresh_token, user_credentials
	 * apply => 申请授权，获取authorizationCode
	 * OAuth2 => 使用accessToken认证
	 * OAuth2Request => OAuth2请求
	 * ------------------------------------------------------------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function scenarios(){
		return [
			'token' => ArrayHelper::merge(($this->useClient === true ? ['clientID', 'clientSecret', 'redirectUrl'] : []), ['grantType']),
			'apply' => ArrayHelper::merge(($this->useClient === true ? ['clientID', 'clientSecret', 'redirectUrl'] : []) , ['email', 'password']),
			'authorization_code' => ['authorizationCode'],
			'refresh_token' => ['refreshToken'],
			'user_credentials' => ['email', 'password'],
			'OAuth2' => ArrayHelper::merge($this->useOpenID ? ['openID'] : [], ['accessToken', 'scope']),
			'OAuth2Request' => ArrayHelper::merge($this->useOpenID ? ['openID'] : [], ['accessToken']),
			'refresh' => ['userId']
		];
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
			['clientID', 'required', 'message' => 10000, 'on' => ['token', 'apply']],
			['clientSecret', 'required', 'message' => 10015, 'on' => ['token', 'apply']],
			['redirectUrl', 'required', 'message' => 10014, 'on' => ['token', 'apply']],
			['grantType', 'required', 'message' => 10008, 'on' => 'token'],
			['clientID', 'validateClientID', 'on' => ['token', 'apply']],
			['clientSecret', 'validateClientSecret', 'on' => ['token', 'apply']],
			['redirectUrl', 'url', 'message' => 10024, 'on' => ['token', 'apply']],
			['redirectUrl', 'validateRedirectUri', 'on' => ['token', 'apply']],
			['grantType', 'validateGrantType', 'on' => 'token'],
			['authorizationCode', 'required', 'message' => 10010, 'on' => 'authorization_code'],
			['authorizationCode', 'validateAuthorizationCode', 'on' => 'authorization_code'],
			['refreshToken', 'required', 'message' => 10011, 'on' => 'refresh_token'],
			['refreshToken', 'validateRefreshToken', 'on' => 'refresh_token'],
			['email', 'required', 'message' => 10012, 'on' => ['user_credentials', 'apply']],
			['password', 'required', 'message' => 10013, 'on' => ['user_credentials', 'apply']],
			['password', 'validatePassword', 'on' => ['user_credentials', 'apply']],
			['accessToken', 'required', 'message' => 10016, 'on' => 'OAuth2Request'],
			['openID', 'required', 'message' => 10018, 'on' => 'OAuth2Request'],
			['accessToken', 'validateAccessToken', 'on' => 'OAuth2'],
			['openID', 'validateOpenID', 'on' => 'OAuth2'],
			// ['scope', 'validateScope', 'on' => 'OAuth2'],
			['userId', 'required', 'message' => 10025, 'on' => 'refresh'],
			['userId', 'integer', 'message' => 10026, 'on' => 'refresh'],
			['userId', 'validateUserId', 'on' => 'refresh'],
		];
	}

	/**
	 * validateClientID(String $attribute, Mixed $params)
	 * 验证客户端ID
	 * --------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateClientID($attribute, $params){
		if(!$this->hasErrors() && !$this->findClient()){
			$this->addError($attribute, 10001);
		}
	}

	/**
	 * validateClientSecret(String $attribute, Mixed $params)
	 * 验证客户端密钥
	 * ------------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateClientSecret($attribute, $params){
		if(!$this->hasErrors() && !Yii::$app->security->compareString($this->findClient()['client_secret'], $this->clientSecret)){
			$this->addError($attribute, 10002);
		}
	}

	/**
	 * validateRedirectUri(String $attribute, Mixed $params)
	 * 验证跳转链接
	 * -----------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateRedirectUri($attribute, $params){
		if(!$this->hasErrors()){
			$clientRedirectUri = parse_url($this->findClient()['redirect_uri']);
			$clientRedirectUri = isset($clientRedirectUri['host']) ? $clientRedirectUri['host'] : $clientRedirectUri['path'];
			$redirectUrl = parse_url($this->redirectUrl);
			$redirectUrl = isset($redirectUrl['host']) ? $redirectUrl['host'] : $redirectUrl['path'];
			if($redirectUrl != $clientRedirectUri){
				$this->addError($attribute, 10003);
			}
		}
	}

	/**
	 * validateAuthorizationCode(String $attribute, Mixed $params)
	 * 验证授权码
	 * -----------------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateAuthorizationCode($attribute, $params){
		if(!$this->hasErrors()){
			$authorizationCode = AuthorizationCode::getAuthorizationCodeInformationByAuthorizationCode($this->authorizationCode);
			if(!$authorizationCode || $authorizationCode->expires < time() || !$authorizationCode->delete()){
				return $this->addError($attribute, 10005);
			}
			$this->findUser(['id' => $authorizationCode->user_id]);
		}
	}

	/**
	 * validateRefreshToken(String $attribute, Mixed $params)
	 * 验证刷新密钥
	 * ------------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateRefreshToken($attribute, $params){
		if (!$this->hasErrors()){
			if(!$refreshToken = RefreshToken::getRefreshTokenInformationByRefreshToken($this->refreshToken)){
				return $this->addError($attribute, 10006);
			}
			$this->findUser(['id' => $refreshToken->user_id]);
		}
	}

	/**
	 * validatePassword(String $attribute, Mixed $params)
	 * 验证用户名和密码
	 * --------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validatePassword($attribute, $params){
		if(!$this->hasErrors()){
			if (!$this->findUser(['email' => $this->email]) || strtoupper(md5($this->_user->secret_key . $this->password)) != strtoupper($this->_user->encryptPwd)) {
				$this->addError($attribute, 10007);
			}
		}
	}

	/**
	 * validateGrantType(String $attribute, Mixed $params)
	 * 验证认证方式
	 * ---------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateGrantType($attribute, $params){
		if(!$this->hasErrors()){
			if($this->useClient === true){
				$grantType = explode(';', $this->findClient()['grant_type']);
				if($this->useRefreshToken === false){
					$key = array_search('refresh_token', $grantType);
					if($key !== false){
						array_splice($grantType, $key, 1);
					}
				}
			}else{
				$grantType = ($this->useRefreshToken === true ? ['refresh_token'] : []) + ['authorization_code', 'user_credentials'];
			}
			if (!in_array($this->grantType, $grantType)) {
				$this->addError($attribute, 10009);
			}
		}
	}

	/**
	 * validateAccessToken(String $attribute, Mixed $params)
	 * 验证授权密钥
	 * -----------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateAccessToken($attribute, $params){
		if(!$this->hasErrors()){
			if(!$this->findAccessToken()){
				$this->addError($attribute, 10017);
			}
		}
	}

	/**
	 * validateOpenID(String $attribute, Mixed $params)
	 * 验证openID
	 * ------------------------------------------------
	 * 该函数只能在validataAccessToken()之后调用
	 * -----------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * -----------------------------------------
	 * @author Verdient。
	 */
	public function validateOpenID($attribute, $params){
		if(!$this->hasErrors()){
			if(!$openID = OpenID::getOpenIDInformationByUserID($this->_accessToken->user_id, true)){
				$this->addError($attribute, 10023);
			}else if($openID['open_id'] != $this->openID){
				$this->addError($attribute, 10019);
			}
		}
	}

	/**
	 * validateScope(String $attribute, Mixed $params)
	 * 验证权限
	 * -----------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateScope($attribute, $params){
		if(!$this->hasErrors()){
			$actionId = explode('-', Yii::$app->id)[1] . '/' . Yii::$app->controller->module->requestedRoute;
			if(!Yii::$app->authManager->checkAccess($this->_accessToken['user_id'], '/' . $actionId)){
				$this->addError($attribute, 10004);
			}
		}
	}

	/**
	 * validateUserId(String $attribute, Mixed $params)
	 * 验证用户ID
	 * ------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateUserId($attribute, $params){
		if(!$this->hasErrors()){
			if(!$this->isOAuth2Authorized){
				$errors = $this->getFirstErrors();
				$this->addError($attribute, reset($errors));
			}
		}
	}

	/**
	 * createAccessToken()
	 * 创建accessToken
	 * -------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function createAccessToken(){
		$accessToken = new AccessToken;
		$accessToken->created_by = $this->_grant_type[$this->grantType];
		$accessToken->user_id = $this->_user->id;
		$accessToken->expires = time() + $this->accessTokenExpires;
		$accessToken->ip = Yii::$app->request->userIP;
		$accessToken->user_agent = Yii::$app->request->userAgent;
		$accessToken->createAccessToken($this->accessTokenLength, $this->deleteOldAccessToken);
		$accessToken = ['access_token' => $accessToken->access_token, 'access_token_expires' => $accessToken->expires];
		if($this->useRefreshToken && $this->scenario != 'refresh_token'){
			$refreshToken = $this->createRefreshToken();
		}
		if($this->useOpenID){
			$openID = $this->createOpenID($this->openIDLength);
		}
		$accessToken = (isset($refreshToken) && $refreshToken) ? ArrayHelper::merge($accessToken, $refreshToken) : $accessToken;
		$accessToken = (isset($openID) && $openID) ? ArrayHelper::merge($accessToken, $openID) : $accessToken;
		return $accessToken;
	}

	/**
	 * createAuthorizationCode()
	 * 创建authorizationCode
	 * -------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function createAuthorizationCode(){
		$authorizationCode = new AuthorizationCode;
		$authorizationCode->user_id = $this->_user->id;
		$authorizationCode->expires = time() + $this->authorizationCodeExpires;
		$authorizationCode->createAuthorizationCode($this->authorizationCodeLength, $this->deleteOldAuthorizationCode);
		return ['authorization_code' => $authorizationCode->authorization_code, 'expires' => $authorizationCode->expires];
	}

	/**
	 * createRefreshToken()
	 * 创建refreshToken
	 * --------------------
	 * @return Array
	 * @author Verdient。
	 */
	protected function createRefreshToken(){
		$refreshToken = new RefreshToken;
		$refreshToken->user_id = $this->_user->id;
		$refreshToken->expires = time() + $this->refreshTokenExpires;
		$refreshToken->createRefreshToken($this->refreshTokenLength, $this->deleteOldRefreshToken);
		return ['refresh_token' => $refreshToken->refresh_token, 'refresh_token_expires' => $refreshToken->expires];
	}

	/**
	 * createOpenID()
	 * 创建openID
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	protected function createOpenID(){
		$OpenID = new OpenID;
		$OpenID->user_id = $this->_user->id;
		$OpenID->createOpenID($this->openIDLength);
		return ['open_id' => $OpenID['open_id']];
	}

	/**
	 * refresh()
	 * 刷新
	 * --------------
	 * @return Array
	 * @author Verdient。
	 */
	public function refresh(){
		$acessToken = new AccessToken;
		$refreshToken = new RefreshToken;
		$authorizationCode = new AuthorizationCode;
		$acessToken->deleteAll(['user_id' => $this->userId]);
		$refreshToken->deleteAll(['user_id' => $this->userId]);
		$authorizationCode->deleteAll(['user_id' => $this->userId]);
		return true;
	}

	/**
	 * getUserInfo()
	 * 获取用户信息
	 * -------------
	 * @return Object
	 * @author Verdient。
	 */
	public function getUserInfo(){
		if($this->isOAuth2Authorized){
			if(!$this->_userInfo){
				if(!$this->_userInfo = $this->findUser(['id' => $this->_accessToken['user_id']])){
					throw new Exception(10027);
				}
			}
			return $this->_userInfo;
		}
		$errors = $this->getFirstErrors();
		throw new Exception(reset($errors));
	}

	/**
	 * getIsOAuth2Request()
	 * 获取是否是OAuth2Request
	 * -----------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function getIsOAuth2Request(){
		if(!$this->_isOAuth2Request){
			$this->setScenario('OAuth2Request');
			return $this->_isOAuth2Request = $this->validate();
		}
		return $this->_isOAuth2Request;
	}

	/**
	 * getIsOAuth2Authorized()
	 * 获取是否认证通过
	 * -----------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function getIsOAuth2Authorized(){
		if(!$this->_isOAuth2Authorized){
			if($this->isOAuth2Request){
				$this->setScenario('OAuth2');
				$this->_isOAuth2Authorized = $this->validate() ? true : false;
			}
			$this->addLog();
		}
		return $this->_isOAuth2Authorized;
	}

	/**
	 * addLog()
	 * 添加日志
	 * --------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function addLog(){
		$log = new Log();
		$errors = $this->getFirstErrors();
		$log = [
			'user_id' => $this->_user ? $this->_user->id : ($this->_accessToken ? $this->_accessToken['user_id'] : null),
			'oauth2_scenario' => $this->getScenario(),
			'is_oauth2_request' => $this->_isOAuth2Request,
			'is_oauth2_authorized' => $this->_isOAuth2Authorized,
			'error_code' => $errors ? reset($errors) : null,
			'authorization_code' => $this->authorizationCode,
			'access_token' => $this->accessToken,
			'refresh_token' => $this->refreshToken,
			'username' => $this->email,
			'route' => Yii::$app->controller->module->requestedRoute,
			'url' => Yii::$app->request->absoluteUrl,
			'user_agent' =>  Yii::$app->request->headers->has('User-Agent') ? Yii::$app->request->headers->get('User-Agent') : null,
			'gets' => Yii::$app->request->get(),
			'posts' => Yii::$app->request->post(),
			'ip' => Yii::$app->request->userIP,
		];
		Log::addLog($log);
	}

	/**
	 * getUser()
	 * 获取用户
	 * ---------
	 * @return Object
	 * @author Verdient。
	 */
	public function getUser(){
		return $this->_user;
	}

	/**
	 * getAccessTokenInfo()
	 * 获取授权密钥信息
	 * --------------------
	 * @return Object
	 * @author Verdient。
	 */
	public function getAccessTokenInfo(){
		return $this->_accessToken;
	}

	/**
	 * findUser([Array $query = []])
	 * 查找用户信息
	 * -----------------------------
	 * @param Array $query 查询条件
	 * ----------------------------
	 * @return Object
	 * @author Verdient。
	 */
	protected function findUser(Array $query = []){
		if(!$this->_user){
			$this->_user = $this->userModel->findOne(array_merge($query, ['userTag' => [User::USERTAG_FINANCE, User::USERTAG_REVIEW, User::USERTAG_MEMBER, User::USERTAG_EXTERNAL, User::USERTAG_ADMIN]]));
		}
		return $this->_user;
	}

	/**
	 * findClient()
	 * 获取客户端信息
	 * --------------
	 * @return Object
	 * @author Verdient。
	 */
	protected function findClient(){
		if(!$this->_client){
			$this->_client = Client::getClientInformationByClientID($this->clientID, true);
		}
		return $this->_client;
	}

	/**
	 * findAccessToken()
	 * 查找授权信息
	 * -----------------
	 * @return Object
	 * @author Verdient。
	 */
	protected function findAccessToken(){
		if (!$this->_accessToken) {
			if($this->_accessToken = AccessToken::getAccessTokenInformationByAccessToken(str_replace('Bearer ', '', $this->accessToken), true)){
				if($this->_accessToken['expires'] < time() || $this->_accessToken['ip'] != Yii::$app->request->userIP || $this->_accessToken['user_agent'] != Yii::$app->request->userAgent){
					$this->_accessToken = null;
				}
			}
		}
		return $this->_accessToken;
	}
}