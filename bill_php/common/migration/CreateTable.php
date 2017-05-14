<?php
namespace common\migration;

use Yii;
use common\helpers\DatabaseHelper;

/**
 * CreateTable Model
 * 创建数据表模型
 * -----------------
 * @version 1.0.0
 * @author Verdient。
 */
class CreateTable {

	/**
	 * OAuth2Log([String $table = 'oauth2_log'])
	 * 创建OAuth2日志表
	 * -----------------------------------------
	 * @param String $table 表的名称
	 * -----------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function OAuth2Log($table = 'oauth2_log'){
		DatabaseHelper::queryBySql("CREATE TABLE `$table` (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志编号',
			`user_id` int(11) unsigned DEFAULT NULL COMMENT '用户ID',
			`oauth2_scenario` varchar(30) DEFAULT NULL COMMENT '认证的场景',
			`is_oauth2_request` smallint(3) unsigned NOT NULL COMMENT '是否是OAuth2请求',
			`is_oauth2_authorized` smallint(3) unsigned NOT NULL COMMENT 'OAuth2是否认证成功',
			`error_code` int(11) unsigned DEFAULT NULL COMMENT '错误码',
			`route` varchar(255) NOT NULL COMMENT '访问的路由',
			`url` varchar(255) NOT NULL COMMENT 'url地址',
			`user_agent` varchar(255) NOT NULL COMMENT '用户浏览器代理字符串',
			`gets` text COMMENT 'get接收的内容',
			`posts` text COMMENT 'post接收的内容',
			`authorization_code` varchar(50) DEFAULT NULL COMMENT '授权码',
			`access_token` varchar(50) DEFAULT NULL COMMENT '授权密钥',
			`refresh_token` varchar(50) DEFAULT NULL COMMENT '刷新密钥',
			`username` varchar(50) DEFAULT NULL COMMENT '用户名',
			`ip` varchar(46) NOT NULL COMMENT 'ip地址',
			`created_at` bigint(20) unsigned NOT NULL COMMENT '创建时间',
			`updated_at` bigint(20) unsigned NOT NULL COMMENT '最后修改时间',
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

	}

	/**
	 * OAuth2AccessToken([String $table = 'oauth2_access_token'])
	 * 创建OAuth2授权密钥表
	 * ----------------------------------------------------------
	 * @param String $table 表的名称
	 * -----------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function OAuth2AccessToken($table = 'oauth2_access_token'){
		DatabaseHelper::queryBySql("CREATE TABLE `$table` (
			`access_token` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '授权密钥',
			`user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
			`ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '申请IP',
			`user_agent` text COLLATE utf8_unicode_ci NOT NULL COMMENT '申请UA',
			`expires` bigint(20) unsigned NOT NULL COMMENT '过期时间',
			`created_at` bigint(20) unsigned NOT NULL COMMENT '创建时间',
			`created_by` smallint(3) unsigned NOT NULL COMMENT '创建自：\r\n1 => authorization_code,\r\n2 => refresh_token,\r\n3 => user_credentials',
			`updated_at` bigint(20) unsigned NOT NULL COMMENT '最后修改时间',
			PRIMARY KEY (`access_token`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
	}

	/**
	 * OAuth2OpenId([String $table = 'oauth2_open_id'])
	 * 创建OAuth2 Open ID表
	 * ------------------------------------------------
	 * @param String $table 表的名称
	 * -----------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function OAuth2OpenId($table = 'oauth2_open_id'){
		DatabaseHelper::queryBySql("CREATE TABLE `$table` (
			`user_id` int(11) unsigned NOT NULL COMMENT '用户id',
			`open_id` varchar(50) NOT NULL COMMENT '用户openID',
			`created_at` bigint(20) unsigned NOT NULL COMMENT '创建时间',
			`updated_at` bigint(20) unsigned NOT NULL COMMENT '最后修改时间',
			PRIMARY KEY (`user_id`),
			UNIQUE KEY `open_id` (`open_id`) USING BTREE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
	}

	/**
	 * OAuth2AuthorizationCode([String $table = 'oauth2_authorization_code'])
	 * 创建OAuth2授权码表
	 * ----------------------------------------------------------------------
	 * @param String $table 表的名称
	 * -----------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function OAuth2AuthorizationCode($table = 'oauth2_authorization_code'){
		DatabaseHelper::queryBySql("CREATE TABLE `$table` (
			`authorization_code` varchar(50) NOT NULL COMMENT '授权码',
			`user_id` int(11) unsigned NOT NULL COMMENT '用户id',
			`expires` bigint(20) unsigned NOT NULL COMMENT '过期时间',
			`created_at` bigint(20) unsigned NOT NULL COMMENT '创建时间',
			`updated_at` bigint(20) unsigned NOT NULL COMMENT '最后修改时间',
			PRIMARY KEY (`authorization_code`),
			KEY `expires` (`expires`) USING BTREE
		) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
	}

	/**
	 * OAuth2RefreshToken([String $table = 'oauth2_refresh_token'])
	 * 创建OAuth2刷新密钥表
	 * ------------------------------------------------------------
	 * @param String $table 表的名称
	 * -----------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function OAuth2RefreshToken($table = 'oauth2_refresh_token'){
		DatabaseHelper::queryBySql("CREATE TABLE `$table` (
			`refresh_token` varchar(50) NOT NULL COMMENT '刷新密钥',
			`user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
			`expires` bigint(20) unsigned NOT NULL COMMENT '过期时间',
			`created_at` bigint(20) unsigned NOT NULL COMMENT '创建时间',
			`updated_at` bigint(20) unsigned NOT NULL COMMENT '最后修改时间',
			PRIMARY KEY (`refresh_token`),
			UNIQUE KEY `refresh_token` (`refresh_token`) USING BTREE,
			KEY `expires` (`expires`) USING BTREE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
	}

	/**
	 * OAuth2Client([String $table = 'oauth2_client'])
	 * 创建OAuth2客户端表
	 * -----------------------------------------------
	 * @param String $table 表的名称
	 * -----------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function OAuth2Client($table = 'oauth2_client'){
		DatabaseHelper::queryBySql("CREATE TABLE `$table` (
			`client_id` int(11) unsigned NOT NULL COMMENT '客户端ID',
			`status` tinyint(5) unsigned NOT NULL COMMENT '状态',
			`client_secret` varchar(80) NOT NULL COMMENT '客户端密钥',
			`redirect_uri` varchar(255) NOT NULL COMMENT '回调地址',
			`grant_type` varchar(255) DEFAULT NULL COMMENT '认证类型',
			`created_at` bigint(20) unsigned NOT NULL COMMENT '创建时间',
			`updated_at` bigint(20) unsigned NOT NULL COMMENT '最后修改时间',
			`created_by` int(11) unsigned DEFAULT NULL COMMENT '创建人ID',
			PRIMARY KEY (`client_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
		DatabaseHelper::queryBySql("INSERT INTO `$table` VALUES (
			'1', '1', '-PvlcREI5eB_b3gh9RtoNa-TekFESqva5W9l-UgXs', 'api.basic.com',
			'authorization_code;refresh_token;user_credentials', '1473650851', '1473650851', '1')"
		);
	}

	/**
	 * HaipiaohuiLog([String $table = 'haipiaohui_log'])
	 * 创建海票惠日志表
	 * -------------------------------------------------
	 * @param String $table 表的名称
	 * -----------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function HaipiaohuiLog($table = 'haipiaohui_log'){
		DatabaseHelper::queryBySql("CREATE TABLE `$table` (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
			`status` tinyint(5) unsigned NOT NULL COMMENT '状态',
			`url` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'URL',
			`route` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '路由',
			`gets` text COLLATE utf8_unicode_ci COMMENT 'get数据',
			`posts` text COLLATE utf8_unicode_ci COMMENT 'post数据',
			`datas` text COLLATE utf8_unicode_ci NOT NULL COMMENT '数据',
			`created_at` bigint(20) unsigned NOT NULL COMMENT '创建时间',
			`updated_at` bigint(20) unsigned NOT NULL COMMENT '更新时间',
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
	}

	/**
	 * BlockChainLog([String $table = 'block_chain_log'])
	 * 创建区块链日志表
	 * --------------------------------------------------
	 * @param String $table 表的名称
	 * -----------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function BlockChainLog($table = 'block_chain_log'){
		DatabaseHelper::queryBySql("CREATE TABLE `$table` (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
			`status` tinyint(5) unsigned NOT NULL COMMENT '状态',
			`url` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'URL',
			`route` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '路由',
			`error_info` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '错误信息',
			`gets` text COLLATE utf8_unicode_ci COMMENT 'get数据',
			`posts` text COLLATE utf8_unicode_ci COMMENT 'post数据',
			`responses` text COLLATE utf8_unicode_ci NOT NULL COMMENT '响应数据',
			`created_at` bigint(20) unsigned NOT NULL COMMENT '创建时间',
			`updated_at` bigint(20) unsigned NOT NULL COMMENT '更新时间',
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
	}

	/**
	 * MessageLog([String $table = 'message_log'])
	 * 创建信息日志表
	 * -------------------------------------------
	 * @param String $table 表的名称
	 * -----------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function MessageLog($table = 'message_log'){
		DatabaseHelper::queryBySql("CREATE TABLE `$table` (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
			`status` int(5) unsigned NOT NULL COMMENT '状态',
			`type` int(5) unsigned NOT NULL COMMENT '类型',
			`scenario` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '场景',
			`account` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '账号',
			`sid` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '确认ID',
			`operator` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '运营商',
			`posts` text COLLATE utf8_unicode_ci NOT NULL,
			`datas` text COLLATE utf8_unicode_ci,
			`created_at` bigint(20) unsigned NOT NULL COMMENT '创建时间',
			`updated_at` bigint(20) unsigned NOT NULL COMMENT '更新时间',
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
	}

	/**
	 * FourElementsLog([String $table = 'four_elements_log'])
	 * 创建四要素认证日志表
	 * ------------------------------------------------------
	 * @param String $table 表的名称
	 * -----------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function FourElementsLog($table = 'four_elements_log'){
		DatabaseHelper::queryBySql("CREATE TABLE `$table` (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
			`status` tinyint(5) unsigned NOT NULL COMMENT '状态',
			`name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '姓名',
			`persion_id` varchar(18) COLLATE utf8_unicode_ci NOT NULL COMMENT '身份证号',
			`bank_card_number` varchar(19) COLLATE utf8_unicode_ci NOT NULL COMMENT '银行卡号',
			`mobile` varchar(14) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号码',
			`send` text COLLATE utf8_unicode_ci NOT NULL,
			`result` text COLLATE utf8_unicode_ci NOT NULL,
			`created_at` bigint(20) unsigned NOT NULL COMMENT '创建时间',
			`updated_at` bigint(20) unsigned NOT NULL COMMENT '更新时间',
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
	}

	/**
	 * DbLog([String $table = 'db_log'])
	 * 创建数据库日志表
	 * ---------------------------------
	 * @param String $table 表的名称
	 * -----------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function DbLog($table = 'db_log'){
		DatabaseHelper::queryBySql("CREATE TABLE `$table` (
			`id` int(11) unsigned NOT NULL COMMENT 'ID',
			`sql` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'sql语句',
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
	}
}