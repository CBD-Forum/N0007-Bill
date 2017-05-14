<?php
namespace api\v1\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use api\v1\components\ApiController;
use api\v1\components\Exception;
use api\v1\models\BankCompany;
use api\v1\models\CompanyBaseInfo;
use api\v1\models\Enterprise;
use api\v1\models\PublicKey;
use api\v1\models\TradeCompany;
use api\v1\models\User;

/**
 * Enterprise Controller
 * 企业控制器
 * ---------------------
 * @version 1.0.0
 * @author Verdient。
 */
class EnterpriseController extends ApiController
{
	/**
	 * actionEnterpriseSubmit()
	 * 提交银行信息
	 * ------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionBankSubmit(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new BankCompany;
		$model->setScenario('create');
		$model->load(['BankCompany' => Yii::$app->request->post()]);
		$model->companyId = $userInfo->enterprise_id;
		if($model->validate() && $model->sendBankInfo()){
			if(!$model->instance){
				$model->instance = new Enterprise;
				$model->instance->status = Enterprise::STATUS_WAITING_ENTERPRISE_SUBMIT;
				$model->instance->enterprise_id = $model->companyId;
			}
			if($model->instance->status == Enterprise::STATUS_AUDIT_FAILED || $model->instance->status == Enterprise::STATUS_REMIT_FAILED){
				$model->instance->status = Enterprise::STATUS_WAITING_APPLY;
			}
			$model->instance->name = $model->name;
			$model->instance->bank_card_number = $model->bank_card_number;
			$model->instance->bank_code = $model->bank_code;
			$model->instance->save(false);
			return $this->sendResponse(['message' => '银行信息提交成功']);
		}
		$model->handleError();
	}

	/**
	 * actionEnterpriseSubmit()
	 * 提交企业信息
	 * ------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionEnterpriseSubmit(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new CompanyBaseInfo;
		$model->setScenario('create');
		$model->load(['CompanyBaseInfo' => Yii::$app->request->post()]);
		$model->enterprise_id = $userInfo->enterprise_id;
		if($model->validate() && $model->sendBaseInfo()){
			$model->instance->status = Enterprise::STATUS_WAITING_APPLY;
			$model->instance->business_license_path = $model->business_license_path;
			$model->instance->organization_code_certificate_path = $model->organization_code_certificate_path;
			$model->instance->licence_for_opening_accounts_path = $model->licence_for_opening_accounts_path;
			$model->instance->corporate_ID_card_path = $model->corporate_ID_card_path;
			$model->instance->power_of_attorney_path = $model->power_of_attorney_path;
			$model->instance->save(false);
			return $this->sendResponse(['message' => '企业信息提交成功']);
		}
		$model->handleError();
	}

	/**
	 * actionAuthorizerUpdate()
	 * 授权人信息修改
	 * ------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionAuthorizerUpdate(){
		$enterpriseId = Yii::$app->OAuth2->userInfo->enterprise_id;
		$model = new BaseCompany;
		$model->setScenario('authorizerUpdate');
		$model->load(['BaseCompany' => Yii::$app->request->post()]);
		$model->enterprise_id = $enterpriseId;
		if($model->validate()){
			$baseCompany = BaseCompany::getActiveRecordInformation(['enterprise_id' => $enterpriseId]);
			$baseCompany->legalPersonNm = $model->legalPersonNm;
			$baseCompany->contacts = $model->contacts;
			$baseCompany->contact_number = $model->contact_number;
			$baseCompany->mailAddress = $model->mailAddress;
			if($baseCompany->sendBaseInfo()){
				$model->instance->corporate_ID_card_id = $model->corporate_ID_card_id;
				$model->instance->power_of_attorney_id = $model->power_of_attorney_id;
				$model->instance->save(false);
				return $this->sendResponse(['message' => '修改成功']);
			}
		}
		$model->handleError();
	}

	/**
	 * actionBankInfo()
	 * 获取银行信息
	 * ----------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionBankInfo(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_ADMIN, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new BankCompany;
		$model->setScenario('bankInfo');
		switch($userInfo->type){
			case User::TYPE_ADMIN:
				$model->load(['BankCompany' => Yii::$app->request->get()]);
				break;

			default:
				$model->companyId = $userInfo->enterprise_id;
				break;
		}
		if($model->validate()){
			$data = ArrayHelper::toArray($model->instance);
			return $this->sendResponse([
				'userName' => $data['userName'],
				'account' => $data['account'],
				'bankName' => $data['bankName'],
				'sBankCode' => $data['sBankCode'],
			]);
		}
		$model->handleError();
	}

	/**
	 * actionEnterpriseInfo()
	 * 获取企业信息
	 * ----------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionEnterpriseInfo(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_ADMIN, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Enterprise;
		$model->setScenario('enterpriseInfo');
		switch($userInfo->type){
			case User::TYPE_ADMIN:
				$model->load(['Enterprise' => Yii::$app->request->get()]);
				break;

			default:
				$model->enterprise_id = $userInfo->id;
				break;
		}
		if($model->validate()){
			if($result = Enterprise::find()
				->select(['enterprise_id', 'business_license_path', 'organization_code_certificate_path'])
				->where(['enterprise_id' => $model->enterprise_id])
				->with('companyBaseInfo')
				->asArray()
				->one()
			){
				$result['enterprise_type'] = isset($result['companyBaseInfo']) ? $result['companyBaseInfo']['enterprise_type'] : null;
				$result['socialCreditCode'] = isset($result['companyBaseInfo']) ? $result['companyBaseInfo']['socialCreditCode'] : null;
				$result['address'] = isset($result['companyBaseInfo']) ? $result['companyBaseInfo']['address'] : null;
				$result['licence'] = isset($result['companyBaseInfo']) ? $result['companyBaseInfo']['licence'] : null;
				$result['name'] = isset($result['companyBaseInfo']) ? $result['companyBaseInfo']['name'] : null;
				$result['area'] = isset($result['companyBaseInfo']) ? $result['companyBaseInfo']['area'] : null;
				$result['industry'] = isset($result['companyBaseInfo']) ? $result['companyBaseInfo']['industry'] : null;
				$result['business_license_url'] = Yii::$app->staticResource->path2url($result['business_license_path']);
				$result['organization_code_certificate_url'] = Yii::$app->staticResource->path2url($result['organization_code_certificate_path']);
				unset($result['enterprise_id'], $result['companyBaseInfo']);
				return $this->sendResponse($result);
			}
			throw new Exception(26001);
		}
		$model->handleError();
	}

	/**
	 * acitonBusinessInfo()
	 * 工商信息
	 * --------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionBusinessInfo(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_ADMIN, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Enterprise();
		$model->setScenario('businessInfo');
		switch($userInfo->type){
			case User::TYPE_ADMIN:
				$model->load(['Enterprise' => Yii::$app->request->get()]);
				break;

			default:
				$model->enterprise_id = $userInfo->id;
				break;
		}
		if($model->validate()){
			if($result = Enterprise::find()
				->select(['enterprise_id', 'licence_for_opening_accounts_path'])
				->where(['enterprise_id' => $model->enterprise_id])
				->with('companyBaseInfo')
				->asArray()
				->one()){
				$result['reg_date'] = isset($result['companyBaseInfo']) ? $result['companyBaseInfo']['reg_date'] : null;
				$result['reg_capital'] = isset($result['companyBaseInfo']) ? $result['companyBaseInfo']['reg_capital'] : null;
				$result['currency'] = isset($result['companyBaseInfo']) ? $result['companyBaseInfo']['currency'] : null;
				$result['licence_for_opening_accounts_url'] = Yii::$app->staticResource->path2url($result['licence_for_opening_accounts_path']);
				unset($result['enterprise_id'], $result['companyBaseInfo']);
				return $this->sendResponse($result);
			}
			throw new Exception(26001);
		}
		$model->handleError();
	}

	/**
	 * legalPersonInfo()
	 * 法人信息
	 * -----------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionLegalPersonInfo(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_ADMIN, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new Enterprise();
		$model->setScenario('legalPersonInfo');
		switch($userInfo->type){
			case User::TYPE_ADMIN:
				$model->load(['Enterprise' => Yii::$app->request->get()]);
				break;

			default:
				$model->enterprise_id = $userInfo->id;
				break;
		}
		if($model->validate()){
			if($result = Enterprise::find()
				->select(['enterprise_id', 'corporate_ID_card_path', 'power_of_attorney_path'])
				->where(['enterprise_id' => $model->enterprise_id])
				->with('companyBaseInfo')
				->asArray()
				->one()){
				$result['legalPersonNm'] = isset($result['companyBaseInfo']) ? $result['companyBaseInfo']['legalPersonNm'] : null;
				$result['mailAddress'] = isset($result['companyBaseInfo']) ? $result['companyBaseInfo']['mailAddress'] : null;
				$result['contacts'] = isset($result['companyBaseInfo']) ? $result['companyBaseInfo']['contacts'] : null;
				$result['contact_number'] = isset($result['companyBaseInfo']) ? $result['companyBaseInfo']['contact_number'] : null;
				$result['corporate_ID_card_url'] = Yii::$app->staticResource->path2url($result['corporate_ID_card_path']);
				$result['power_of_attorney_url'] = Yii::$app->staticResource->path2url($result['power_of_attorney_path']);
				unset($result['enterprise_id'], $result['companyBaseInfo']);
				return $this->sendResponse($result);
			}
			throw new Exception(26001);
		}
		$model->handleError();
	}

	/**
	 * actionEcdsInfo()
	 * ECDS信息
	 * ----------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionEcdsInfo(){
		$userInfo = $this->checkPermission([User::TYPE_ADMIN, User::TYPE_EXTERNAL, User::TYPE_FINANCE, User::TYPE_MEMBER], [User::TYPE_EXTERNAL, User::TYPE_MEMBER]);
		$model = new Enterprise();
		$model->setScenario('ecdsInfo');
		$model->load(['Enterprise' => Yii::$app->request->get()]);
		if($model->validate()){
			if($result = Enterprise::find()
				->select(['name', 'bank_card_number', 'bank_code'])
				->where(['enterprise_id' => $model->enterprise_id])
				->asArray()
				->one()){
				return $this->sendResponse($result);
			}
			throw new Exception(26001);
		}
		$model->handleError();
	}

	/**
	 * actionInfo()
	 * 企业信息
	 * ------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionInfo(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		if($result = Enterprise::find()
			->select(['enterprise_id', 'name', 'bank_card_number', 'bank_code', 'business_license_path', 'organization_code_certificate_path', 'licence_for_opening_accounts_path', 'corporate_ID_card_path', 'power_of_attorney_path'])
			->where(['enterprise_id' => $userInfo->enterprise_id])
			->with('companyBaseInfo', 'bankCompany')
			->asArray()
			->one()
		){
			if(!isset($result['companyBaseInfo'])){
				throw new Exception(26012);
			}
			$result['userName'] = $result['bankCompany']['userName'];
			$result['account'] = $result['bankCompany']['account'];
			$result['bankName'] = $result['bankCompany']['bankName'];
			$result['enterprise_name'] = $result['companyBaseInfo']['name'];
			$result['enterprise_type'] = $result['companyBaseInfo']['enterprise_type'];
			$result['address'] = $result['companyBaseInfo']['address'];
			$result['industry'] = $result['companyBaseInfo']['industry'];
			$result['area'] = $result['companyBaseInfo']['area'];
			$result['licence'] = $result['companyBaseInfo']['licence'];
			$result['socialCreditCode'] = $result['companyBaseInfo']['socialCreditCode'];
			$result['code_org'] = $result['companyBaseInfo']['code_org'];
			$result['reg_date'] = $result['companyBaseInfo']['reg_date'];
			$result['reg_capital'] = $result['companyBaseInfo']['reg_capital'];
			$result['currency'] = $result['companyBaseInfo']['currency'];
			$result['legalPersonNm'] = $result['companyBaseInfo']['legalPersonNm'];
			$result['mailAddress'] = $result['companyBaseInfo']['mailAddress'];
			$result['contact_number'] = $result['companyBaseInfo']['contact_number'];
			$result['contacts'] = $result['companyBaseInfo']['contacts'];
			$result['business_license_url'] = Yii::$app->staticResource->path2url($result['business_license_path']);
			$result['organization_code_certificate_url'] = Yii::$app->staticResource->path2url($result['organization_code_certificate_path']);
			$result['licence_for_opening_accounts_url'] = Yii::$app->staticResource->path2url($result['licence_for_opening_accounts_path']);
			$result['corporate_ID_card_url'] = Yii::$app->staticResource->path2url($result['corporate_ID_card_path']);
			$result['power_of_attorney_url'] = Yii::$app->staticResource->path2url($result['power_of_attorney_path']);
			unset($result['enterprise_id'], $result['companyBaseInfo'], $result['bankCompany']);
			return $this->sendResponse($result);
		}
		throw new Exception(26011);
	}

	/**
	 * actionPublicKeyByEnterpriseName()
	 * 通过企业名称获取公钥
	 * ---------------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionPublicKeyByEnterpriseName(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_MEMBER], [User::TYPE_MEMBER]);
		$model = new User;
		$model->setScenario('publicKeyByEnterpriseName');
		$model->load(['User' => Yii::$app->request->get()]);
		if($model->validate()){
			if(!$user = User::find()->select(['id'])->where(['name' => $model->enterprise_name])->asArray()->one()){
				throw new Exception(31029);
			}
			if(!$publicKey = PublicKey::find()->select(['public_key', 'status'])->where(['user_id' => $user['id']])->asArray()->one()){
				throw new Exception(31030);
			}
			if($publicKey['status'] != PublicKey::STATUS_REGULAR){
				throw new Exception(31031);
			}
			return $this->sendResponse(['public_key' => $publicKey['public_key']]);
		}
		$model->handleError();
	}

	/**
	 * actionEnterpriseNameByAddress()
	 * 获取企业名称
	 * -------------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionEnterpriseNameByAddress(){
		$userInfo = $this->checkPermission([User::TYPE_ADMIN]);
		$model = new TradeCompany;
		$model->setScenario('enterpriseName');
		$model->load(['TradeCompany' => Yii::$app->request->get()]);
		if($model->validate()){
			return $this->sendResponse($model->instance);
		}
		$model->handleError();
	}

	/**
	 * actionEnterpriseList()
	 * 获取企业列表
	 * ----------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionEnterpriseList(){
		$model = new TradeCompany;
		$model->setScenario('enterpriseList');
		$model->load(['Company' => Yii::$app->request->get()]);
		if($model->validate()){
			return $this->sendResponse($model->enterpriseList());
		}
		$model->handleError();
	}
}