<?php namespace TrevorWP\Util;

use DateTime;
use DateTimeZone;
use Twig;
use TrevorWP\Exception;
use TrevorWP\Main;

class Tools {
	const MYSQL_DATE_FORMAT = 'Y-m-d';
	const MYSQL_TIME_FORMAT = 'H:i:s';
	const MYSQL_DATE_TIME_FORMAT = self::MYSQL_DATE_FORMAT . ' ' . self::MYSQL_TIME_FORMAT;

	/*
	 * Option Keys
	 */
	const OPTION_KEY_LOG_SALT_PREFIX = 'log_salt_';

	/**
	 * Adds an update notification for admin.
	 *
	 * @param string $msg
	 */
	static public function add_update_msg( $msg ) {
		self::add_msg( $msg, 'updated' );
	}

	/**
	 * @param string $msg Message
	 * @param string $class Message class
	 */
	static public function add_msg( $msg, $class ) {
		Hooks::$admin_notices[] = [
			'class' => $class,
			'msg'   => $msg,
		];
	}

	/**
	 * Adds an error notification for admin.
	 *
	 * @param string $msg
	 */
	static public function add_error_msg( $msg ): void {
		self::add_msg( $msg, 'error' );
	}

	/**
	 * Adds an warning message for admin.
	 *
	 * @param string $msg
	 */
	static public function add_warning_msg( $msg ) {
		self::add_msg( $msg, 'notice-warning' );
	}

	/**
	 * Determine if a given string ends with a given substring.
	 *
	 * @param string $haystack
	 * @param string|array $needles
	 *
	 * @return bool
	 */
	public static function ends_with( $haystack, $needles ) {
		foreach ( (array) $needles as $needle ) {
			if ( (string) $needle === substr( $haystack, - strlen( $needle ) ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Creates a nonce by given action and extra data.
	 *
	 * @param string $action Action string, like 'edit-item', case sensitive.
	 * @param array $args Extra information about action, like 'item_id => 11' etc. Order is not important.
	 *
	 * @return string Nonce token
	 */
	static public function create_nonce( $action, $args = [] ) {
		$extra_args = self::create_nonce_action( $args );

		return wp_create_nonce( "{$action}_{$extra_args}" );
	}

	/**
	 * Creates a nonce action from the given array.
	 *
	 * @param array $extra Extra information about action, like 'item_id => 11' etc. Order is not important.
	 *
	 * @return string
	 */
	static public function create_nonce_action( $extra = [] ) {
		// Convert all values to string
		$extra = array_map( 'strval', $extra );

		// Sort alphabetical
		if ( empty( $extra ) ) {
			return '';
		} else {
			ksort( $extra );
		}

		return _http_build_query( $extra, null, '_', '', false );

	}

	/**
	 * Validates nonce, throws \TrevorWP\Exception\Unauthorized() exception if fails.
	 *
	 * @param string $action Action string, like 'edit-item', case sensitive.
	 * @param string $nonce Nonce text to verify.
	 * @param array $args Extra information about action, like 'item_id => 11' etc. Order is not important.
	 *
	 * @return false|int False if the nonce is invalid, 1 if the nonce is valid and generated between
	 *                   0-12 hours ago, 2 if the nonce is valid and generated between 12-24 hours ago.
	 * @throws Exception\Unauthorized
	 */
	static public function verify_nonce( $action, $nonce, array $args = [] ) {
		$args       = (array) $args;
		$extra_args = self::create_nonce_action( $args );
		$nonce_key  = "{$action}_{$extra_args}";

		if ( ! ( $result = wp_verify_nonce( $nonce, $nonce_key ) ) ) {
			throw new Exception\Unauthorized();
		} else {
			return $result;
		}
	}

	/**
	 * Get user id from a variable.
	 *
	 * @param \WP_User|int|null $user
	 *
	 * @return int
	 * @throws Exception\Internal If $user argument is not recognizable.
	 */
	static public function get_user_id( $user = null ) {
		$user = self::get_user( $user );

		if ( $user instanceof \WP_User ) {
			return $user->ID;
		} elseif ( is_numeric( $user ) ) {
			return $user;
		} else {
			return null;
		}
	}

	/**
	 * Get user object from a variable.
	 *
	 * @param \WP_User|int|null $user
	 *
	 * @return \WP_User
	 * @throws Exception\Internal If $user argument is not recognizable.
	 */
	static public function get_user( $user = null ) {
		if ( $user instanceof \WP_User ) {
			return $user;
		} elseif ( is_numeric( $user ) ) {
			return intval( $user ) == 0
				? wp_get_current_user()
				: get_user_by( 'id', $user );
		} elseif ( ! is_null( $user ) ) {
			throw new Exception\Internal( '$user argument is not recognizable' );
		} else {
			return wp_get_current_user();
		}
	}

	/**
	 * Sort an array by a column.
	 *
	 * @param array $array An array being sorted.
	 * @param string|callable $column Column to short items by it's value. Default is order.
	 * @param int $sort_flag Short flags.
	 * @param int $order The order used to sort the array.
	 *
	 * @return bool Returns TRUE on success or FALSE on failure.
	 */
	static public function sort_by( array &$array, $column = 'order', $sort_flag = SORT_NUMERIC, $order = SORT_ASC ) {
		$columns = [];
		if ( is_callable( $column ) ) {
			foreach ( $array as $key => $row ) {
				$columns[ $key ] = call_user_func( $column, $row );
			}
		} else {
			foreach ( $array as $key => $row ) {
				if ( is_object( $row ) ) {
					$columns[ $key ] = empty( $row->$column ) ? null : $row->$column;
				} else {
					$columns[ $key ] = empty( $row[ $column ] ) ? null : $row[ $column ];
				}
			}
		}

		return array_multisort( $columns, $order, $sort_flag, $array );
	}

	/**
	 * Sends rendered email by given template.
	 * It strips the any extension on the $template argument that starts with a dot
	 * and tries these extension on the following order: .html.twig , .html , .txt.twig, .txt
	 * If it finds the template with html then sends the email as html, txt otherwise.
	 *
	 * @param $template
	 * @param array $mail_args
	 *  - string|array $to          Array or comma-separated list of email addresses to send message.
	 *  - string       $subject     Email subject
	 *  - string|array $headers     Optional. Additional headers.
	 *  - string|array $attachments Optional. Files to attach.
	 * @param array $context
	 * @param string|null $filter If filter specified, then it applies `trevor_email_{$filter}` filter.
	 *
	 * @return bool
	 */
	static public function send_email_with_template( $template, array $mail_args, array $context = [], $filter = null ) {
		if ( ! is_string( $template ) ) {
			throw new Exception\Internal( '$template parameter must be file path of the file.' );
		}

		$mail_args = array_merge( [
			'subject'     => '',
			'headers'     => '',
			'message'     => '',
			'attachments' => [],
		], $mail_args );

		if ( empty( $mail_args['to'] ) ) {
			Log::error( 'Parameter $to is required to send an email.', [
				'template' => $template,
				'subject'  => $mail_args['subject'],
			] );

			return false;
		}

		$dirname  = dirname( $template );
		$basename = explode( '.', basename( $template ) );
		$basename = $basename[0]; // Remove all parts after the first dot

		$twig = Main::get_twig();

		# Resolve html template
		try {
			$html = $twig->resolveTemplate( [
				$dirname . '/' . $basename . '.html.twig',
				$dirname . '/' . $basename . '.html',
			] );
		} catch ( Twig\Error\LoaderError $e ) {
			$html = false;
		}

		if ( $html ) {
			# Render html message
			$mail_args['message'] = $twig->render( $html, $context = array_merge( $context, [
				'mail_args' => $mail_args,
				'is_html'   => true,
			] ) );
		} else {
			# Resolve txt template
			try {
				$txt = $twig->resolveTemplate( [
					$dirname . '/' . $basename . '.txt.twig',
					$dirname . '/' . $basename . '.txt',
				] );
			} catch ( Twig\Error\LoaderError $e ) {
				$txt = false;
			}

			if ( $txt ) {
				# Render txt message
				$mail_args['message'] = $twig->render( $txt, $context = array_merge( $context, [
					'mail_args' => $mail_args,
					'is_txt'    => true,
				] ) );
			}
		}

		$mail_args = [
			$mail_args['to'],
			$mail_args['subject'],
			$mail_args['message'],
			$mail_args['headers'],
			$mail_args['attachments'],
		];

		if ( $filter ) {
			$mail_args = apply_filters( "trevor_email_{$filter}", $mail_args, $context, $template );
		}

		if ( $html ) {
			return self::send_html_email( $mail_args );
		} elseif ( $txt ) {
			return call_user_func_array( 'wp_mail', $mail_args );
		} else {
			Log::error( "Could not find any email email templates for : " . $template );

			return false;
		}
	}

	/**
	 * A basic way to send a html email.
	 *
	 * @param array $args wp_mail() arguments.
	 *
	 * @return bool Whether the email contents were sent successfully.
	 * @see wp_mail()
	 *
	 */
	static public function send_html_email( array $args ) {
		$returnHtmlMime = function () {
			return "text/html";
		};

		add_filter( 'wp_mail_content_type', $returnHtmlMime );
		$return = call_user_func_array( 'wp_mail', $args );
		remove_filter( 'wp_mail_content_type', $returnHtmlMime );

		return $return;
	}

	/**
	 * Write Exception\Internal messages to error log file.
	 *
	 * @param \Exception $e
	 *
	 * @return void
	 */
	static public function log_exception( \Exception $e ) {
		if ( defined( 'TREVOR_NO_EXCEPTION_LOG' ) && constant( 'TREVOR_NO_EXCEPTION_LOG' ) ) {
			return;
		}

		$context = [ 'trace' => $e->getTrace() ];

		if ( $e instanceof Exception\Internal ) {
			if ( ! empty( $e->detail_message ) ) {
				$context['detail_message'] = $e->detail_message;
			}
			if ( ! empty( $e->data ) ) {
				$context['data'] = $e->data;
			}
		}

		Log::critical( $e->getMessage(), $context );
	}

	/**
	 * Returns array list of attributes to $key="$value" format.
	 *
	 * @param array $attr Attributes list as an array.
	 * @param bool $skip_empty Skips entries which have empty values.
	 *
	 * @return string Formatted html attributes.
	 */
	static public function flat_attr( $attr = [], $skip_empty = false ) {
		if ( empty( $attr ) ) {
			return '';
		}

		$out = [];
		foreach ( $attr as $attr_key => $attr_value ) {

			if ( $skip_empty && empty( $attr_value ) ) {
				continue;
			}

			$out[] = sprintf( '%s="%s"', $attr_key, esc_attr( $attr_value ) );
		}

		return join( ' ', $out );
	}

	/**
	 * Returns UTC current time for mysql.
	 *
	 * @param bool $only_date Exclude time, return only date.
	 *
	 * @return string Current time in "Y-m-d H:i:s" format.
	 */
	static public function get_utc_for_mysql( $only_date = false ) {
		$now = new DateTime( 'now', new DateTimeZone( 'UTC' ) );

		return $now->format( $only_date ? self::MYSQL_DATE_FORMAT : self::MYSQL_DATE_TIME_FORMAT );
	}

	/**
	 * Converts mysql time text to \DateTime object.
	 *
	 * @param string $time_str
	 *
	 * @return DateTime|null If DateTime class can not converts string, then it returns null.
	 */
	static public function mysql_2_datetime( $time_str ) {
		if ( empty( $time_str ) || $time_str == '0000-00-00 00:00:00' ) {
			return null;
		}

		try {
			return new DateTime( $time_str, new DateTimeZone( 'UTC' ) );
		} catch ( \Exception $e ) {
			return null;
		}
	}

	/**
	 * Extracts a list of property values.
	 *
	 * @param array $array
	 * @param string|array $pluck_keys
	 * @param callable|callable[] $filters
	 * @param bool $prevent_keys
	 *
	 * @return array
	 */
	static public function pluck( $array, $pluck_keys, $filters = [], $prevent_keys = false ) {
		$out           = [];
		$array         = (array) $array;
		$filters       = (array) $filters;
		$is_single_key = ! is_array( $pluck_keys );
		$has_filter    = ! empty( $filters );
		foreach ( $array as $key => $_val ) {
			if ( $is_single_key ) {
				$val = is_object( $_val ) ? @$_val->$pluck_keys : @$_val[ $pluck_keys ];
				if ( $has_filter ) {
					foreach ( $filters as $filter ) {
						$val = call_user_func( $filter, $val );
					}
				}
			} else {
				$val = [];
				foreach ( $pluck_keys as $pluck_key ) {
					$val[ $pluck_key ] = is_object( $_val ) ? @$_val->$pluck_key : @$_val[ $pluck_key ];
				}
				if ( $has_filter ) {
					foreach ( $filters as $filter_key => $filter_val ) {
						if ( is_numeric( $filter_key ) ) {
							array_map( $filter_val, $val );
						} else {
							$val[ $filter_key ] = call_user_func( $filter_val, $val[ $filter_key ] );
						}
					}
				}
			}

			if ( $prevent_keys ) {
				$out[ $key ] = $val;
			} else {
				$out[] = $val;
			}
		}

		return $out;
	}

	/**
	 * Returns file extensions and mime types of requested file types.
	 *
	 * @param $allowed_types $types
	 *
	 * @return array
	 */
	static public function get_allowed_file_types( array $allowed_types ) {
		$allowed_types = array_intersect( $allowed_types, [
			'image',
			'video',
			'audio',
			'archive',
			'document',
			'spreadsheet',
			'interactive',
			'text',
		] );

		$allowed_extensions = [];
		$wp_ext_types       = wp_get_ext_types();
		foreach ( $allowed_types as $type ) {
			if ( array_key_exists( $type, $wp_ext_types ) ) {
				$allowed_extensions = array_merge( $allowed_extensions, $wp_ext_types[ $type ] );
			}
		}
		$allowed_extensions = array_unique( $allowed_extensions );

		$wp_mime_types = wp_get_mime_types();
		$allowed_mime  = [];
		foreach ( $wp_mime_types as $ext => $mime_type ) {
			foreach ( explode( '|', $ext ) as $sub_ext ) {
				if ( in_array( $sub_ext, $allowed_extensions ) ) {
					$allowed_mime[] = $mime_type;
					continue;
				}
			}
		}
		$allowed_mime = array_unique( $allowed_mime );

		return [
			'ext'  => $allowed_extensions,
			'mime' => array_values( $allowed_mime ),
		];
	}

	/**
	 * Returns absolute path of requested cache folder.
	 *
	 * @param string|null $name Folder name.
	 *
	 * @return string Absolute path of the cache folder.
	 */
	static public function get_cache_folder( $name ) {
		return path_join( TREVOR_CACHE_DIR, $name );
	}

	/**
	 * Returns absolute path of logs file.
	 *
	 * @param string $name Log file name
	 * @param bool $salt Adds an unique prefix to file name.
	 *
	 * @return string Absolute path of the log file.
	 */
	static public function get_log_file( $name, $salt = true ) {
		$name = str_replace( '-', '_', self::slugify( $name ) );

		if ( $salt ) {
			$key = self::OPTION_KEY_LOG_SALT_PREFIX . $name;

			$salt = get_option( $key );

			if ( empty( $salt ) ) {
				$salt = uniqid();
				add_option( $key, $salt );
			}

			$name = $name . '_' . $salt;
		}

		return path_join( TREVOR_LOGS_DIR, "${name}.log" );
	}

	/**
	 * Creates slug from given text.
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	static public function slugify( $text ) {
		return sanitize_title_with_dashes( remove_accents( $text ) );
	}

	/**
	 * Change array keys with one of the column's data.
	 *
	 * @param array $arrays
	 * @param string $key
	 *
	 * @return array[]|object[]
	 */
	static public function index_by( $arrays, $key ) {
		$out = [];
		foreach ( $arrays as $ao ) {
			if ( is_object( $ao ) ) {
				if ( isset( $ao->$key ) ) {
					$out[ $ao->$key ] = $ao;
				} elseif ( method_exists( $ao, $key ) ) {
					$out[ $ao->$key() ] = $ao;
				}
			} elseif ( is_array( $ao ) ) {
				if ( ! isset( $ao[ $key ] ) ) {
					continue;
				}
				$out[ $ao[ $key ] ] = $ao;
			}
		}

		return $out;
	}

	/**
	 * Retrieve the date in localized format.
	 *
	 * @param int|DateTime $date Unix timestamp or DateTime object.
	 *
	 * @return string Formatted date.
	 */
	static public function format_date( $date ) {
		return self::_datetime_formatter( get_option( 'date_format' ), $date );
	}

	/**
	 * @param string $format
	 * @param int|DateTime $time
	 *
	 * @return string
	 * @throws Exception\Internal
	 */
	static protected function _datetime_formatter( $format, $time ) {
		if ( is_integer( $time ) ) {
			$time = new DateTime( "@{$time}" );
		} elseif ( ! $time instanceof DateTime ) {
			throw new Exception\Internal( 'Malformed $time argument.' );
		}
		$tz_offset = wp_timezone()->getOffset( $time );
		$ts        = (int) $time->format( 'U' );

		return date_i18n( $format, $tz_offset + $ts );
	}

	/**
	 * Retrieve the time in localized format.
	 *
	 * @param int|DateTime $time Unix timestamp or DateTime object.
	 *
	 * @return string Formatted time.
	 */
	static public function format_time( $time ) {
		return self::_datetime_formatter( get_option( 'time_format' ), $time );
	}

	/**
	 * Retrieve the date and time in localized format.
	 *
	 * @param int|DateTime $time Unix timestamp or DateTime object.
	 *
	 * @return string Formatted date time.
	 */
	static public function format_datetime( $time ) {
		return self::_datetime_formatter( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $time );
	}

	/**
	 * Returns current user's ip address.
	 *
	 * @param bool $no_private
	 *
	 * @return string IP address
	 */
	static public function get_ip_address( $no_private = false ) {
		foreach (
			[
				'HTTP_CLIENT_IP',
				'HTTP_X_FORWARDED_FOR',
				'HTTP_X_FORWARDED',
				'HTTP_X_CLUSTER_CLIENT_IP',
				'HTTP_FORWARDED_FOR',
				'HTTP_FORWARDED',
				'REMOTE_ADDR',
			] as $key
		) {
			if ( array_key_exists( $key, $_SERVER ) === true ) {
				foreach ( explode( ',', $_SERVER[ $key ] ) as $ip ) {
					$ip = trim( $ip ); // just to be safe

					if ( false !== filter_var( $ip, FILTER_VALIDATE_IP, ( $no_private ? FILTER_FLAG_NO_PRIV_RANGE : 0 ) ) ) {
						return $ip;
					}
				}
			}
		}

		return null;
	}

	/**
	 * Determines the difference between two dates.
	 *
	 * @param int|DateTime $from
	 * @param int|DateTime|null $to
	 *
	 * @return string
	 * @uses human_time_diff()
	 *
	 */
	static public function human_time_diff( $from, $to = null ): string {
		$tz = wp_timezone();

		if ( ! $from instanceof DateTime ) {
			$from = new DateTime( "@{$from}" );
		}

		if ( empty( $to ) ) {
			$to = new DateTime( 'now' );
		} elseif ( ! $to instanceof DateTime ) {
			$to = new DateTime( "@{$to}" );
		}

		$from = intval( $from->format( 'U' ) ) + $tz->getOffset( $from );
		$to   = intval( $to->format( 'U' ) ) + $tz->getOffset( $to );

		return human_time_diff( $from, $to );
	}

	/**
	 * Adds context to queue to print on footer action.
	 *
	 * @param $context
	 */
	static public function footer_print( $context ): void {
		if ( is_admin() ) {
			Hooks::$admin_footer_buffer[] = $context;
		} else {
			Hooks::$frontend_footer_buffer[] = $context;
		}
	}

	/**
	 * Creates a directory if not exist or checks permission if already exist.
	 *
	 * @param string $dir
	 * @param bool $log Logs if there is an error.
	 * @param bool $recursive
	 * @param int $mode
	 *
	 * @return bool
	 */
	static public function maintain_dir( $dir, $log = true, $recursive = false, $mode = 0700 ): bool {
		if ( is_dir( $dir ) ) {
			if ( ! @chmod( $dir, $mode ) ) {
				if ( $log ) {
					Log::error( "Permission of the '${dir}' directory could not be changed." );

					return false;
				}
			}
		} else {
			if ( ! @mkdir( $dir, $mode, $recursive ) ) {
				if ( $log ) {
					Log::error( "Directory '${dir}' could not be created." );

					return false;
				}
			}
		}

		$index_file = path_join( $dir, 'index.php' );

		if ( file_exists( $index_file ) ) {
			@unlink( $index_file );
		}

		# Add an empty file to prevent directory listings
		@file_put_contents( $index_file, "<?php\n// Silence is golden." );

		return is_dir( $dir ) && is_writable( $dir );
	}

	/**
	 * @param string $t HTML tag, e.g. `p`
	 * @param string $html
	 *
	 * @return array [string $inside, array $attributes]
	 */
	public static function parse_simple_block_tag( string $t, string $html ): array {
		# Parse p tag
		$result = preg_match( "#^\s+<{$t}\b(?<attrs>[^>]*)>(?<inside>.*?)<\/{$t}>\s+$#", $html, $matches );

		if ( $result === false ) {
			return [ false, false ];
		}

		# Parse attributes
		$attributes = [];
		if ( ! empty( $matches['attrs'] ) ) {
			preg_match_all( "#\s*(?<name>\w+)(=(\"(?<value1>.*)\")|('(?<value2>.*)'))?\s*#", $matches['attrs'], $attr_matches );
			foreach ( $attr_matches['name'] as $idx => $attr_key ) {
				$attributes[ $attr_key ] = $attr_matches["value1"][ $idx ] ?: $attr_matches["value2"][ $idx ];
			}
		}

		return [
			@$matches['inside'],
			$attributes
		];
	}

	/**
	 * @param int $object_id
	 * @param string $meta_key
	 * @param array $new_list
	 * @param string $meta_type
	 *
	 * @return array [$added, $deleted]
	 */
	public static function multi_meta_handler( int $object_id, string $meta_key, array $new_list, string $meta_type = 'post' ): array {
		$current = get_metadata( $meta_type, $object_id, $meta_key, false );

		$to_del = array_diff( $current, $new_list );
		$to_add = array_diff( $new_list, $current );

		foreach ( $to_del as $meta_value ) {
			delete_metadata( $meta_type, $object_id, $meta_key, $meta_value );
		}

		foreach ( $to_add as $meta_value ) {
			add_metadata( $meta_type, $object_id, $meta_key, $meta_value );
		}

		return [ $to_add, $to_del ];
	}

	/**
	 * Returns the environment name.
	 *
	 * @return string
	 */
	public static function get_env(): string {
		return (string) ( TREVOR_ON_DEV ? 'dev' : constant( 'PANTHEON_ENVIRONMENT' ) );
	}

	/**
	 * Returns the path of the Google OAuth configuration file.
	 *
	 * @return string
	 * @throws Exception\Internal If the config file is missing.
	 * @deprecated
	 */
	public static function get_g_oauth_config(): string {
		$env = self::get_env();

		if ( $env == 'test' ) {
			$env = 'dev';
		}

		$name = "g_oauth-{$env}.json";
		$path = path_join( TREVOR_PRIVATE_DATA_DIR, $name );
		if ( ! file_exists( $path ) ) {
			throw new Exception\Internal( 'Google OAuth Client Id file is missing.' );
		}

		return $path;
	}
}
