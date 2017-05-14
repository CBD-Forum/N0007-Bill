<?php
namespace common\components\fourElements;

use Yii;
use yii\base\Model;

/**
 * FourElements Model
 * 四要素 模型
 * ------------------
 * @version 1.0.0
 * @author Verdient。
 */
class FourElements extends Model
{
	/**
	 * @var public $enableValidate
	 * 是否允许校验
	 * ---------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $enableValidate = true;

	/**
	 * @var public $apiUrl
	 * 接口地址
	 * -------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $apiUrl = '';

	/**
	 * @var public $apiUrl
	 * 接口地址
	 * -------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $authKey = '';

	/**
	 * init()
	 * 初始化
	 * ------
	 * @author Verdient。
	 */
	public function init(){
		parent::init();
		if(empty($this->apiUrl)){
			throw new Exception(12002);
		}
		if(empty($this->authKey)){
			throw new Exception(12003);
		}
	}

	/**
	 * validateFourElements(String $name, String $persionId, String $bankCardNumber, String $mobile)
	 * 校验四要素
	 * ---------------------------------------------------------------------------------------------
	 * @param String $name 姓名
	 * @param String $persionId 身份证号
	 * @param String $bankCardNumber 银行卡号
	 * @param String String $mobile 手机号码
	 * --------------------------------------
	 * @return Array / false
	 * @author Verdient。
	 */
	public function validateFourElements($name, $persionId, $bankCardNumber, $mobile){
		if($this->enableValidate === true){
			return $this->_doValidate($name, $persionId, $bankCardNumber, $mobile);
		}
		return true;
	}

	/**
	 * _doValidate()
	 * 进行校验
	 * -------------
	 * @return Array / String
	 * @author Verident。
	 */
	private function _doValidate($name, $persionId, $bankCardNumber, $mobile){
		$data = [
			'id_no' => $persionId,
			'id_name' => $name,
			'card_no' => $bankCardNumber,
			'mobile_no' => $mobile,
			'type_bankcard' => 2,
			'auth_key' => $this->authKey,
		];
		$result = $this->_send($data);
		$flag = isset($result['ret_code']) && isset($result['result_auth']) && $result['ret_code'] == '0000' && $result['result_auth'] == 'T';
		Log::addLog([
			'status' => $flag ? Log::STATUS_SUCCESS : Log::STATUS_FAILED,
			'name' => $name,
			'persion_id' => $persionId,
			'bank_card_number' => $bankCardNumber,
			'mobile' => $mobile,
			'send' => $data,
			'result' => $result
		]);
		return $flag;
	}

	/**
	 * _send()
	 * 发送
	 * -------
	 * @return Array / String
	 * @author Verident。
	 */
	private function _send($data){
		Yii::$app->cUrl->reset();
		Yii::$app->cUrl->setData($data);
		return Yii::$app->cUrl->post($this->apiUrl, 'JSON');
	}
}