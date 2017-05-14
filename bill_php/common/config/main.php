<?php
return [
	'vendorPath' => dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'vendor',
	'language' => 'zh-CN',
	'sourceLanguage' => 'en-US',
	'components' => [
		'cache' => [
			'class' => 'yii\caching\FileCache'
		],
		'cUrl' => [
			'class' => 'common\components\CUrl'
		],
		'SOAP' => [
			'class' => 'common\components\SOAP'
		],
		'db' => [
			'class' => 'yii\db\Connection',
			'dsn' => 'mysql:host=192.168.1.200;dbname=haihang_haipiaohui',
			'username' => 'root',
			'password' => 'hehehehe',
			'charset' => 'utf8',
			'tablePrefix' => 'hph_'
		],
		'db2' => [
			'class' => 'yii\db\Connection',
			'dsn' => 'mysql:host=192.168.1.200;dbname=users',
			'username' => 'root',
			'password' => 'hehehehe',
			'charset' => 'utf8'
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false
		],
		'i18n' => [
			'translations' => [
				'*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@messages'
				],
			],
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				'system' => [
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
				'soap-trace' => [
					'class' => 'common\log\FileTarget',
					'categories' => ['common\components\SOAP::*'],
					'logFile' => '@common/runtime/logs/SOAP-trace-' . date('Y-m') . '.log',
					'logVars' => [],
					'levels' => ['trace']
				],
				'db-execute' => [
					'class' => 'common\log\FileTarget',
					'categories' => ['yii\db\Command::execute'],
					'logFile' => '@common/runtime/logs/db-execute-' . date('Y-m') . '.log',
					'logVars' => [],
					'levels' => ['profile']
				],
				'block-chain' => [
					'class' => 'common\log\FileTarget',
					'categories' => ['common\components\blockChain\*'],
					'logFile' => '@common/runtime/logs/block-chain-' . date('Y-m') . '.log',
					'logVars' => [],
					'levels' => ['error']
				],
				'console-property' => [
					'class' => 'common\log\FileTarget',
					'categories' => ['console\controllers\PropertyController\*'],
					'logFile' => '@common/runtime/logs/console-property-' . date('Y-m') . '.log',
					'logVars' => [],
					'levels' => ['error']
				]
			],
		],
		'fourElements' => [
			'class' => 'common\components\fourElements\FourElements',
			'enableValidate' => true,
			'apiUrl' => 'https://auth.uubee.com/uubee_authserver/api/bankandall_check.htm',
			'authKey' => 'C2EC10817A9F2738E1BF7C88D419916BECBE5D6CE30C86B87C8AD82454FA785066'
		],
		'validate' => [
			'class' => 'common\components\Validate',
			'enableValidate' => true
		],
		'export2Excel' => [
			'class' => 'common\components\Export2Excel',
			'path' => 'export' . DIRECTORY_SEPARATOR . 'excel'
		],
		'message' => [
			'class' => 'common\components\message\Message',
			'enableSMS' => true,
			'key' => '432666',
			'secret' => '1gr264ic1nphlque9vzavqb9dxibrzeo',
			'protocol' => 'http',
			'host' => 'sms909.33.cn',
			'routes' => [
				'sendSMS' => '/api/web/index.php?r=message/sms-send',
				'validateCaptcha' => '/api/web/index.php?r=message/validate',
				'_getAccessToken' => '/api/web/index.php?r=user/get-token'
			]
		],
		'staticResource' => [
			'class' => 'common\components\StaticResource',
			'port' => '9082'
		],
		'haipiaohui' => [
			'class' => 'common\components\haipiaohui\Haipiaohui',
			'protocol' => 'http',
			'host' => '120.132.124.88',
			'routes' => [
				'generateMemberAccount' => '/users/users/generateChildCompanyAccount',
				'changePassword' => '/users/users/modifyCompanyAccount',
				'resetPassword' => '/users/users/resetCompanyAccount',
				'generateMemberHistory' => '/users/users/historyCreateCompanyAccount',
				'sendBaseCompanyInfo' => '/users/ticket/company/addBaseInfo',
				'sendBankInfo' => '/users/ticket/company/addBankInfo',
				'openAccount' => '/users/ticket/openAccount/openZXAccount'
			]
		],
		'blockChain' => [
			'class' => 'common\components\blockChain\BlockChain',
			'protocol' => 'http',
			'host' => '114.55.3.58',
			'port' => '3577',
			'routes' => [
				'getPublicKey' => '/user/getPublicKey',
				'getKeys' => '/user/getKeys',
				'getBill' => '/note/getNotes',
				'getBalance' => '/note/queryWithoutSign',
				'cashIn' => '/note/userDeposit',
				'cashOut' => '/note/userWithdraw',
				// 'increaseBankRmb' => '/note/increaseBankRmb',
				'userRegister' => '/note/userRegister',
				'userRegisterBySystem' => '/note/userRegisterWithoutSign',
				// 'setDepositRate' => '/note/setDepositRate',
				// 'userAccessMoney' => '/note/userAccessMoney',
				'createBill' => '/note/createNote',
				'listingBill' => '/note/sellNote',
				'operateNote' => '/note/operateNote',
				'transferAccount' => '/note/transferAsset',
				'deleteBill' => '/note/deleteNote',
				'discountBill' => '/note/discountNote',
				'endorseBill' => '/note/endorseNote',
				'setAdmin' => '/note/setAdmin',
				'execute' => '/note/operate',
				'query' => '/note/query',
				// 'editNote' => '/note/editNote'
			],
			'beforeSend' => function($value){
				ksort($value);
				return array_merge($value, ['signid' => strtoupper(md5(urldecode(http_build_query($value)) . 'zQPj2YhwJUnCro0z'))]);
			}
		],
		'blockChainResolve' => [
			'class' => 'common\components\BlockChainResolve',
			'protocol' => 'http',
			'host' => '122.224.124.250',
			'port' => '8812',
			'routes' => [
				'getBalance' => '/bill/default/walletinfo'
			]
		],
		'config' => [
			'class' => 'common\components\Config'
		],
		'bank' => [
			'class' => 'common\components\bank\Bank',
			'account' => 'PJSYS',
			'password' => '863C1F499252D0078F0D9236539A4437',
			'encryptionKey' => '33BBF17148C620B1413B0EFF4508504E',
			'apiUrl' => 'http://192.168.0.18:58081/services/BankService?wsdl'
		],
		'permission' => [
			'class' => 'common\components\Permission'
		]
	]
];
