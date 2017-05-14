<?php
$params = array_merge(
	require(__DIR__ . '/../../../common/config/params.php'),
	require(__DIR__ . '/params.php')
);

return [
	'id' => 'app-api/v1',
	'basePath' => dirname(__DIR__),
	'controllerNamespace' => 'api\v1\controllers',
	'bootstrap' => ['log'],
	'modules' => [],
	'components' => [
		'request' => [
			'csrfParam' => '_csrf-api',
			'cookieValidationKey' => 'kH_wfGTR72Fcu-FWNQp-mpjV9YJ2pP8h',
			'parsers' => [
				'application/json' => 'yii\web\JsonParser'
			],
			'enableCsrfValidation' => false
		],
		'user' => [
			'identityClass' => 'common\models\User',
			'enableAutoLogin' => true,
			'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
		],
		'session' => [
			'name' => 'haipiaohui-finance-api',
		],
		'errorHandler' => [
			'class' => 'api\v1\components\ErrorHandler'
		],
		'OAuth2' => [
			'class' => 'common\components\OAuth2\OAuth2',
			'useClient' => false,
			'useRefreshToken' => false
		]
	],
	'params' => $params,
];
