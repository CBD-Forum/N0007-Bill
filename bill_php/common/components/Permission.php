<?php
namespace common\components;

use Yii;
use yii\base\Model;

/**
 * Permission Model
 * 权限 模型
 * ----------------
 * @version 1.0.0
 * @author Verdient。
 */
class Permission extends Model
{
	/**
	 * @var public $enableCheck
	 * 是否启用检查
	 * ------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $enableCheck = true;

	/**
	 * check(Array $enabledType[, Array $certificatedType = [], Boolean $authenticated = false])
	 * 检查权限
	 * -----------------------------------------------------------------------------------------
	 * @param Array $enabledType 允许的用户类型
	 * @param Array $certificatedType 需要四要素认证的用户类型
	 * @param Boolean authenticated 是否需要企业认证
	 * -------------------------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function check($enabledType, $certificatedType, $authenticated = false){
		$userInfo = Yii::$app->OAuth2->userInfo;
		if($this->enableCheck === true){
			if(!in_array($userInfo->type, $enabledType)){
				throw new Exception(403);
			}
			if(in_array($userInfo->type, $certificatedType) && !$userInfo->isCertificated){
				throw new Exception(401);
			}
			if($authenticated === true && !$userInfo->isAuthenticated){
				throw new Exception(402);
			}
		}
		return $userInfo;
	}
}