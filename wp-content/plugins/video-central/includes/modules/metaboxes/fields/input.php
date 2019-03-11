<?php

/**
 * Abstract input field class which is used for all <input> fields.
 */
abstract class Video_Central_Metaboxes_Input_Field extends Video_Central_Metaboxes_Field {

	/**
	 * Get field HTML
	 *
	 * @param mixed $meta
	 * @param array $field
	 * @return string
	 */
	public static function html( $meta, $field ) {
		$attributes = self::call( 'get_attributes', $field, $meta );
		return sprintf( '<input %s>%s', self::render_attributes( $attributes ), self::datalist( $field ) );
	}

	/**
	 * Normalize parameters for field
	 *
	 * @param array $field
	 * @return array
	 */
	public static function normalize( $field ) {
		$field = parent::normalize( $field );
		$field = wp_parse_args( $field, array(
			'datalist' => false,
			'readonly' => false,
		) );
		if ( $field['datalist'] ) {
			$field['datalist'] = wp_parse_args( $field['datalist'], array(
				'id'      => $field['id'] . '_list',
				'options' => array(),
			) );
		}
		return $field;
	}

	/**
	 * Get the attributes for a field
	 *
	 * @param array $field
	 * @param mixed $value
	 * @return array
	 */
	public static function get_attributes( $field, $value = null ) {
		$attributes = parent::get_attributes( $field, $value );
		$attributes = wp_parse_args( $attributes, array(
			'list'        => $field['datalist'] ? $field['datalist']['id'] : false,
			'readonly'    => $field['readonly'],
			'value'       => $value,
			'placeholder' => $field['placeholder'],
			'type'        => $field['type'],
		) );

		return $attributes;
	}

	/**
	 * Create datalist, if any.
	 *
	 * @param array $field
	 * @return array
	 */
	protected static function datalist( $field ) {
		if ( empty( $field['datalist'] ) ) {
			return '';
		}

		$datalist = $field['datalist'];
		$html     = sprintf( '<datalist id="%s">', $datalist['id'] );
		foreach ( $datalist['options'] as $option ) {
			$html .= sprintf( '<option value="%s"></option>', $option );
		}
		$html .= '</datalist>';
		return $html;
	}
}