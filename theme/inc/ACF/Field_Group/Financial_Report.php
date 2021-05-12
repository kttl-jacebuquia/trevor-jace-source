<?php namespace TrevorWP\Theme\ACF\Field_Group;

use TrevorWP\CPT;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;

class Financial_Report extends A_Field_Group {
	const FIELD_REPORTS     = 'reports';
	const FIELD_TITLE       = 'report_title';
	const FIELD_FILE        = 'report_file';
	const FIELD_FILE_SOURCE = 'report_file_source';
	const FIELD_FILE_MEDIA  = 'report_file_media';
	const FIELD_FILE_URL    = 'report_file_url';

	const FILE_SOURCE_MEDIA = 'media';
	const FILE_SOURCE_URL   = 'url';

	/** @inheritDoc */
	public static function prepare_register_args(): array {
		$reports            = static::gen_field_key( static::FIELD_REPORTS );
		$title              = static::gen_field_key( static::FIELD_TITLE );
		$file               = static::gen_field_key( static::FIELD_FILE );
		$report_file_source = static::gen_field_key( static::FIELD_FILE_SOURCE );
		$report_file_media  = static::gen_field_key( static::FIELD_FILE_MEDIA );
		$report_file_url    = static::gen_field_key( static::FIELD_FILE_URL );

		return array(
			'title'    => 'Reports',
			'fields'   => array(
				static::FIELD_REPORTS => array(
					'key'          => $reports,
					'name'         => static::FIELD_REPORTS,
					'type'         => 'repeater',
					'button_label' => 'Add Report',
					'sub_fields'   => array(
						array(
							'key'      => $title,
							'label'    => 'Title',
							'name'     => static::FIELD_TITLE,
							'type'     => 'text',
							'required' => 1,
						),
						array(
							'key'        => $file,
							'label'      => 'File',
							'name'       => static::FIELD_FILE,
							'type'       => 'group',
							'wrapper'    => array(
								'width' => '50',
							),
							'layout'     => 'row',
							'sub_fields' => array(
								array(
									'key'           => $report_file_source,
									'label'         => 'Source',
									'name'          => static::FIELD_FILE_SOURCE,
									'type'          => 'radio',
									'choices'       => array(
										static::FILE_SOURCE_MEDIA => 'Media Library',
										static::FILE_SOURCE_URL   => 'URL',
									),
									'default_value' => static::FILE_SOURCE_URL,
									'layout'        => 'horizontal',
									'return_format' => 'value',
								),
								array(
									'key'               => $report_file_media,
									'label'             => 'File',
									'name'              => static::FIELD_FILE_MEDIA,
									'type'              => 'file',
									'conditional_logic' => array(
										array(
											array(
												'field'    => $report_file_source,
												'operator' => '==',
												'value'    => static::FILE_SOURCE_MEDIA,
											),
										),
									),
									'return_format'     => 'url',
								),
								array(
									'key'               => $report_file_url,
									'label'             => 'File',
									'name'              => static::FIELD_FILE_URL,
									'type'              => 'text',
									'conditional_logic' => array(
										array(
											array(
												'field'    => $report_file_source,
												'operator' => '==',
												'value'    => static::FILE_SOURCE_URL,
											),
										),
									),
								),
							),
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => CPT\Financial_Report::POST_TYPE,
					),
				),
			),
		);
	}

	public static function get_reports( \WP_Post $post ): array {
		$val     = new Field_Val_Getter( static::class, $post );
		$reports = array();

		foreach ( (array) $val->get( static::FIELD_REPORTS ) as $report ) {
			$report_file['title'] = $report[ static::FIELD_TITLE ];
			$report_file['url']   = $report[ static::FIELD_FILE ][ static::FIELD_FILE_SOURCE ] === static::FILE_SOURCE_URL ?
								  $report[ static::FIELD_FILE ][ static::FIELD_FILE_URL ] :
								  $report[ static::FIELD_FILE ][ static::FIELD_FILE_MEDIA ];
			$reports[]            = $report_file;
		}

		return $reports;
	}
}
