<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

$basePath = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR;
$vendorPath = $basePath . 'vendor' . DIRECTORY_SEPARATOR;
$apiPath = dirname(__DIR__) . DIRECTORY_SEPARATOR;
$commonConfigPath = $basePath . 'common' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
$apiConfigPath = $apiPath . 'config' . DIRECTORY_SEPARATOR;

require($vendorPath . 'autoload.php');
require($vendorPath . 'yiisoft' . DIRECTORY_SEPARATOR . 'yii2' . DIRECTORY_SEPARATOR . 'Yii.php');
require($commonConfigPath . 'bootstrap.php');
require($apiConfigPath . 'bootstrap.php');

$commonLocalConfig = file_exists($commonConfigPath . 'main-local.php') ? require($commonConfigPath . 'main-local.php') : [];
$apiLocalConfig = file_exists($apiConfigPath . 'main-local.php') ? require($apiConfigPath . 'main-local.php') : [];

$config = yii\helpers\ArrayHelper::merge(
	require($commonConfigPath . 'main.php'),
	$commonLocalConfig,
	require($apiConfigPath . 'main.php'),
	$apiLocalConfig
);

$application = new yii\web\Application($config);
$application->run();