<?php namespace TrevorWP\Util;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

/**
 * Logging Utils
 *
 * @method static debug( $message, array $context = [] )
 * @method static info( $message, array $context = [] )
 * @method static notice( $message, array $context = [] )
 * @method static warning( $message, array $context = [] )
 * @method static error( $message, array $context = [] )
 * @method static critical( $message, array $context = [] )
 * @method static alert( $message, array $context = [] )
 * @method static emergency( $message, array $context = [] )
 */
class Log {
	/*
	 * Logger Names
	 */
	const LOGGER_NAME_GENERAL = 'general';
	const LOGGER_NAME_AUDIT   = 'audit';

	/*
	 * File Names
	 */
	const FILE_NAME_GENERAL = 'general';
	const FILE_NAME_AUDIT   = 'audit';

	/*
	 * Error Log Levels
	 */
	const ERROR_LOG_LEVEL_DEBUG     = 'debug';
	const ERROR_LOG_LEVEL_INFO      = 'info';
	const ERROR_LOG_LEVEL_NOTICE    = 'notice';
	const ERROR_LOG_LEVEL_WARNING   = 'warning';
	const ERROR_LOG_LEVEL_ERROR     = 'error';
	const ERROR_LOG_LEVEL_CRITICAL  = 'critical';
	const ERROR_LOG_LEVEL_ALERT     = 'alert';
	const ERROR_LOG_LEVEL_EMERGENCY = 'emergency';

	/**
	 * Level values.
	 *
	 * @var int[]
	 * @internal
	 */
	static protected $_map_levels = array(
		self::ERROR_LOG_LEVEL_DEBUG     => Logger::DEBUG,
		self::ERROR_LOG_LEVEL_INFO      => Logger::INFO,
		self::ERROR_LOG_LEVEL_NOTICE    => Logger::NOTICE,
		self::ERROR_LOG_LEVEL_WARNING   => Logger::WARNING,
		self::ERROR_LOG_LEVEL_ERROR     => Logger::ERROR,
		self::ERROR_LOG_LEVEL_CRITICAL  => Logger::CRITICAL,
		self::ERROR_LOG_LEVEL_ALERT     => Logger::ALERT,
		self::ERROR_LOG_LEVEL_EMERGENCY => Logger::EMERGENCY,
	);

	/**
	 * @var Logger
	 */
	protected static $_error_logger;

	/**
	 * @var Logger
	 */
	protected static $_audit_logger;

	/**
	 * @param string $name
	 * @param array $args
	 *
	 * @return bool
	 */
	static function __callStatic( $name, $args = array() ) {
		try {
			return call_user_func_array( array( self::get_error_logger(), $name ), $args );
		} catch ( \UnexpectedValueException $e ) {
			error_log( 'Logger error: ' . $e->getMessage() );

			return false;
		}
	}

	/**
	 * General logger.
	 *
	 * @return Logger
	 */
	public static function get_error_logger() {
		if ( is_null( self::$_error_logger ) ) {
			// Convert log level to int equivalent
			$log_level     = static::get_log_level();
			$int_log_level = array_key_exists( $log_level, static::$_map_levels )
				? static::$_map_levels[ $log_level ]
				: static::$_map_levels[ static::ERROR_LOG_LEVEL_DEBUG ];

			# Rotating File Handler
			$file_handler = new RotatingFileHandler(
				Tools::get_log_file( self::FILE_NAME_GENERAL ),
				static::get_max_error_files(),
				$int_log_level,
				true,
				0700,
				false
			);

			$logger = new Logger( self::LOGGER_NAME_GENERAL );
			$logger->setTimezone( wp_timezone() );
			$logger->pushHandler( $file_handler );

			if ( WP_DEBUG ) {
				$logger->pushHandler( new ErrorLogHandler( ErrorLogHandler::SAPI, self::ERROR_LOG_LEVEL_DEBUG ) );
			}

			self::$_error_logger = $logger;
		}

		return self::$_error_logger;
	}

	/**
	 * Returns log level.
	 *
	 * @return string
	 */
	static public function get_log_level() {
		$default = WP_DEBUG
			? self::ERROR_LOG_LEVEL_DEBUG
			: self::ERROR_LOG_LEVEL_ERROR;

		/**
		 * You can set default log level.
		 *
		 * @arg string Log level.
		 */
		return apply_filters( 'trevor_log_level', $default );
	}

	/**
	 * Returns maximum file count for error log files.
	 *
	 * @return int
	 */
	static public function get_max_error_files() {
		/**
		 * You can set max error files count.
		 *
		 * @arg int Max files count. Default: 7
		 */
		return apply_filters( 'trevor_max_error_files', 7 );
	}

	/**
	 * Audit logger. Automatically saves the current user id.
	 *
	 * @param string $message The log message
	 * @param array $context The log context
	 */
	public static function audit( $message, array $context = array() ) {
		if ( is_null( self::$_audit_logger ) ) {
			$days = self::get_max_audit_files();

			# Rotating File Handler
			$file_handler = new RotatingFileHandler( Tools::get_log_file( self::FILE_NAME_AUDIT ), $days, Logger::INFO, true, 0700, false );

			$logger = new Logger( self::LOGGER_NAME_AUDIT );
			$logger->setTimezone( wp_timezone() );
			$logger->pushHandler( $file_handler );
			$logger->pushProcessor( array( self::class, 'audit_log_user_processor' ) );

			self::$_audit_logger = $logger;
		}

		self::$_audit_logger->info( $message, $context );
	}

	/**
	 * Returns maximum file count for audit log files.
	 *
	 * @return int
	 */
	static public function get_max_audit_files() {
		/**
		 * You can set max audit files count.
		 *
		 * @arg int Max files count. Default: 30
		 */
		return apply_filters( 'trevor_max_audit_files', 30 );
	}


	/**
	 * Processor for the audit log.
	 *
	 * @param array $record
	 *
	 * @return array
	 */
	public static function audit_log_user_processor( $record ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$record['extra']['ajax'] = true;
		}

		if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
			$record['extra']['cron'] = true;
		} else {
			$user = wp_get_current_user();

			if ( empty( $record['extra']['user'] ) ) {
				$record['extra']['user'] = array();
			}

			$record['extra']['user']['id']    = $user->ID;
			$record['extra']['user']['login'] = $user->user_login;
			$record['extra']['user']['ip']    = Tools::get_ip_address();
		}

		return $record;
	}
}
