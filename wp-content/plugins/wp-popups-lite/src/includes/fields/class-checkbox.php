<?php

/**
 * Checkbox field.
 *
 * @since 1.0.0
 */
class WPPopups_Field_Checkbox extends WPPopups_Field {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define field type information.
		$this->name     = esc_html__( 'Checkboxes', 'wp-popups-lite' );
		$this->type     = 'checkbox';
		$this->icon     = 'fa-check-square-o';
		$this->order    = 110;
		$this->defaults = [
			1 => [
				'label'   => esc_html__( 'First Choice', 'wp-popups-lite' ),
				'value'   => '',
				'image'   => '',
				'default' => '',
			],
			2 => [
				'label'   => esc_html__( 'Second Choice', 'wp-popups-lite' ),
				'value'   => '',
				'image'   => '',
				'default' => '',
			],
			3 => [
				'label'   => esc_html__( 'Third Choice', 'wp-popups-lite' ),
				'value'   => '',
				'image'   => '',
				'default' => '',
			],
		];

		// Customize HTML field values.
		add_filter( 'wppopups_html_field_value', array( $this, 'field_html_value' ), 10, 4 );

		// Define additional field properties.
		add_filter( 'wppopups_field_properties_checkbox', array( $this, 'field_properties' ), 5, 3 );
	}

	/**
	 * Return images, if any, for HTML supported values.
	 *
	 * @since 1.4.5
	 *
	 * @param string $value     Field value.
	 * @param array  $field     Field settings.
	 * @param array  $form_data Form data and settings.
	 * @param string $context   Value display context.
	 *
	 * @return string
	 */
	public function field_html_value( $value, $field, $form_data = array(), $context = '' ) {

		// Only use HTML formatting for checkbox fields, with image choices
		// enabled, and exclude the entry table display. Lastly, provides a
		// filter to disable fancy display.
		if (
			isset( $field['type'] ) &&
			! empty( $field['value'] ) &&
			$this->type === $field['type'] &&
			! empty( $field['images'] ) &&
			'entry-table' !== $context &&
			apply_filters( 'wppopups_checkbox_field_html_value_images', true, $context )
		) {

			$items  = array();
			$values = explode( "\n", $field['value'] );

			foreach ( $values as $key => $val ) {

				if ( ! empty( $field['images'][ $key ] ) ) {
					$items[] = sprintf(
						'<span style="max-width:200px;display:block;margin:0 0 5px 0;"><img src="%s" style="max-width:100%%;display:block;margin:0;"></span>%s',
						esc_url( $field['images'][ $key ] ),
						$val
					);
				} else {
					$items[] = $val;
				}
			}

			return implode( '<br><br>', $items );
		}

		return $value;
	}

	/**
	 * Define additional field properties.
	 *
	 * @since 1.4.5
	 *
	 * @param array $properties Field properties.
	 * @param array $field      Field settings.
	 * @param array $form_data  Form data and settings.
	 *
	 * @return array
	 */
	public function field_properties( $properties, $field, $form_data ) {

		// Define data.
		$form_id  = absint( $form_data['id'] );
		$field_id = absint( $field['id'] );
		$choices  = $field['choices'];
		$dynamic  = wppopups_get_field_dynamic_choices( $field, $form_id, $form_data );

		if ( $dynamic ) {
			$choices              = $dynamic;
			$field['show_values'] = true;
		}

		// Remove primary input.
		unset( $properties['inputs']['primary'] );

		// Set input container (ul) properties.
		$properties['input_container'] = array(
			'class' => array( ! empty( $field['random'] ) ? 'wppopups-randomize' : '' ),
			'data'  => array(),
			'attr'  => array(),
			'id'    => "wppopups-{$form_id}-field_{$field_id}",
		);

		$field['choice_limit'] = empty( $field['choice_limit'] ) ? 0 : (int) $field['choice_limit'];
		if ( $field['choice_limit'] > 0 ) {
			$properties['input_container']['data']['choice-limit'] = $field['choice_limit'];
		}

		// Set input properties.
		foreach ( $choices as $key => $choice ) {

			// Used for dynamic choices.
			$depth = isset( $choice['depth'] ) ? absint( $choice['depth'] ) : 1;

			// Choice labels should not be left blank, but if they are we
			// provide a basic value.
			$value = isset( $field['show_values'] ) ? $choice['value'] : $choice['label'];
			if ( '' === $value ) {
				if ( 1 === count( $choices ) ) {
					$value = esc_html__( 'Checked', 'wp-popups-lite' );
				} else {
					/* translators: %s - choice number. */
					$value = sprintf( esc_html__( 'Choice %s', 'wp-popups-lite' ), $key );
				}
			}

			$properties['inputs'][ $key ] = array(
				'container' => array(
					'attr'  => array(),
					'class' => array( "choice-{$key}", "depth-{$depth}" ),
					'data'  => array(),
					'id'    => '',
				),
				'label'     => array(
					'attr'  => array(
						'for' => "wppopups-{$form_id}-field_{$field_id}_{$key}",
					),
					'class' => array( 'wppopups-field-label-inline' ),
					'data'  => array(),
					'id'    => '',
					'text'  => $choice['label'],
				),
				'attr'      => array(
					'name'  => "wppopups[fields][{$field_id}][]",
					'value' => $value,
				),
				'class'     => array(),
				'data'      => array(),
				'id'        => "wppopups-{$form_id}-field_{$field_id}_{$key}",
				'image'     => isset( $choice['image'] ) ? $choice['image'] : '',
				'required'  => ! empty( $field['required'] ) ? 'required' : '',
				'default'   => isset( $choice['default'] ),
			);

			// Rule for validator only if needed.
			if ( $field['choice_limit'] > 0 ) {
				$properties['inputs'][ $key ]['data']['rule-check-limit'] = 'true';
			}
		}

		// Required class for pagebreak validation.
		if ( ! empty( $field['required'] ) ) {
			$properties['input_container']['class'][] = 'wppopups-field-required';
		}

		// Custom properties if image choices is enabled.
		if ( ! $dynamic && ! empty( $field['choices_images'] ) ) {

			$properties['input_container']['class'][] = 'wppopups-image-choices';
			$properties['input_container']['class'][] = 'wppopups-image-choices-' . sanitize_html_class( $field['choices_images_style'] );

			foreach ( $properties['inputs'] as $key => $inputs ) {
				$properties['inputs'][ $key ]['container']['class'][] = 'wppopups-image-choices-item';

				if ( in_array( $field['choices_images_style'], array( 'modern', 'classic' ), true ) ) {
					$properties['inputs'][ $key ]['class'][] = 'wppopups-screen-reader-element';
				}
			}
		}

		// Custom properties for disclaimer format display.
		if ( ! empty( $field['disclaimer_format'] ) ) {

			$properties['description']['class'][] = 'wppopups-disclaimer-description';
			$properties['description']['value']   = nl2br( $properties['description']['value'] );
		}

		// Add selected class for choices with defaults.
		foreach ( $properties['inputs'] as $key => $inputs ) {
			if ( ! empty( $inputs['default'] ) ) {
				$properties['inputs'][ $key ]['container']['class'][] = 'wppopups-selected';
			}
		}

		return $properties;
	}

	/**
	 * Field options panel inside the builder.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field Field settings.
	 */
	public function field_options( $field ) {
		/*
		 * Basic field options.
		 */

		// Options open markup.
		$this->field_option(
			'basic-options',
			$field,
			array(
				'markup' => 'open',
			)
		);

		// Label.
		$this->field_option( 'label', $field );

		// Choices.
		$this->field_option( 'choices', $field );

		// Choices Images.
		//$this->field_option( 'choices_images', $field );

		// Description.
		$this->field_option( 'description', $field );

		// Required toggle.
		$this->field_option( 'required', $field );

		// Options close markup.
		$this->field_option(
			'basic-options',
			$field,
			array(
				'markup' => 'close',
			)
		);

		/*
		 * Advanced field options
		 */

		// Options open markup.
		$this->field_option(
			'advanced-options',
			$field,
			array(
				'markup' => 'open',
			)
		);

		// Randomize order of choices.
		/*$this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'random',
				'content' => $this->field_element(
					'checkbox',
					$field,
					array(
						'slug'    => 'random',
						'value'   => isset( $field['random'] ) ? '1' : '0',
						'desc'    => esc_html__( 'Randomize Choices', 'wp-popups-lite' ),
						'tooltip' => esc_html__( 'Check this option to randomize the order of the choices.', 'wp-popups-lite' ),
					),
					false
				),
			)
		);*/

		// Show Values toggle option. This option will only show if already used
		// or if manually enabled by a filter.
		if ( ! empty( $field['show_values'] ) || wppopups_show_fields_options_setting() ) {
			$this->field_element(
				'row',
				$field,
				array(
					'slug'    => 'show_values',
					'content' => $this->field_element(
						'checkbox',
						$field,
						array(
							'slug'    => 'show_values',
							'value'   => isset( $field['show_values'] ) ? $field['show_values'] : '0',
							'desc'    => esc_html__( 'Show Values', 'wp-popups-lite' ),
							'tooltip' => esc_html__( 'Check this to manually set form field values.', 'wp-popups-lite' ),
						),
						false
					),
				)
			);
		}

		// Choices Images Style (theme).
		//$this->field_option( 'choices_images_style', $field );

		// Display format.
		//$this->field_option( 'input_columns', $field );

		// Choice Limit.
		$field['choice_limit'] = empty( $field['choice_limit'] ) ? 0 : (int) $field['choice_limit'];
		$this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'choice_limit',
				'content' =>
					$this->field_element(
						'label',
						$field,
						array(
							'slug'    => 'choice_limit',
							'value'   => esc_html__( 'Choice Limit', 'wp-popups-lite' ),
							'tooltip' => esc_html__( 'Limit the number of checkboxes a user can select. Leave empty for unlimited.', 'wp-popups-lite' ),
						),
						false
					) . $this->field_element(
						'text',
						$field,
						array(
							'slug'  => 'choice_limit',
							'value' => $field['choice_limit'] > 0 ? $field['choice_limit'] : '',
							'type'  => 'number',
						),
						false
					),
			)
		);

		// Hide label.
		$this->field_option( 'label_hide', $field );

		// Custom CSS classes.
		$this->field_option( 'css', $field );

		// Dynamic choice auto-populating toggle.
		//$this->field_option( 'dynamic_choices', $field );

		// Dynamic choice source.
		//$this->field_option( 'dynamic_choices_source', $field );

		// Enable Disclaimer formatting.
		/*$this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'disclaimer_format',
				'content' => $this->field_element(
					'checkbox',
					$field,
					array(
						'slug'    => 'disclaimer_format',
						'value'   => isset( $field['disclaimer_format'] ) ? '1' : '0',
						'desc'    => esc_html__( 'Enable Disclaimer / Terms of Service Display', 'wp-popups-lite' ),
						'tooltip' => esc_html__( 'Check this option to adjust the field styling to support Disclaimers and Terms of Service type agreements.', 'wp-popups-lite' ),
					),
					false
				),
			)
		);*/

		// Options close markup.
		$this->field_option(
			'advanced-options',
			$field,
			array(
				'markup' => 'close',
			)
		);


		// Options Open markup.
		$this->field_option(
			'provider-options',
			$field,
			[ 'markup' => 'open' ]
		);

		do_action( 'wppopups_field_options_provider', $field, $this );

		// Options close markup.
		$this->field_option(
			'provider-options',
			$field,
			[ 'markup' => 'close' ]
		);
	}

	/**
	 * Field preview inside the builder.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field Field settings.
	 */
	public function field_preview( $field ) {

		// Label.
		$this->field_preview_option( 'label', $field );

		// Choices.
		$this->field_preview_option( 'choices', $field );

		// Description.
		$this->field_preview_option(
			'description',
			$field,
			array(
				'class' => ! empty( $field['disclaimer_format'] ) ? 'disclaimer nl2br' : false,
			)
		);
	}

	/**
	 * Field display on the form front-end.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field      Field settings.
	 * @param array $deprecated Deprecated array.
	 * @param array $form_data  Form data and settings.
	 */
	public function field_display( $field, $deprecated, $form_data ) {
		$using_image_choices = empty( $field['dynamic_choices'] ) && ! empty( $field['choices_images'] );

		// Define data.
		$container = $field['properties']['input_container'];
		$choices   = $field['properties']['inputs'];

		$amp_state_id = '';

		printf(
			'<ul %s>',
			wppopups_html_attributes( $container['id'], $container['class'], $container['data'], $container['attr'] )
		);

			foreach ( $choices as $key => $choice ) {

				// If the field is required, has the label hidden, and has
				// disclaimer mode enabled, so the required status in choice
				// label.
				$required = '';
				if ( ! empty( $field['disclaimer_format'] ) && ! empty( $choice['required'] ) && ! empty( $field['label_hide'] ) ) {
					$required = wppopups_get_field_required_label();
				}

				printf(
					'<li %s>',
					wppopups_html_attributes( $choice['container']['id'], $choice['container']['class'], $choice['container']['data'], $choice['container']['attr'] )
				);

					// The required constraint in HTML5 form validation does not work with checkbox groups, so omit in AMP.
					$required_attr = $choice['required'];

					if ( $using_image_choices ) {

						// Make sure the image choices are keyboard-accessible.
						$choice['label']['attr']['tabindex'] = 0;

						// Image choices.
						printf(
							'<label %s>',
							wppopups_html_attributes( $choice['label']['id'], $choice['label']['class'], $choice['label']['data'], $choice['label']['attr'] )
						);

							if ( ! empty( $choice['image'] ) ) {
								printf(
									'<span class="wppopups-image-choices-image"><img src="%s" alt="%s"%s></span>',
									esc_url( $choice['image'] ),
									esc_attr( $choice['label']['text'] ),
									! empty( $choice['label']['text'] ) ? ' title="' . esc_attr( $choice['label']['text'] ) . '"' : ''
								);
							}

							if ( 'none' === $field['choices_images_style'] ) {
								echo '<br>';
							}

							$choice['attr']['tabindex'] = '-1';

							printf(
								'<input type="checkbox" %s %s %s>',
								wppopups_html_attributes( $choice['id'], $choice['class'], $choice['data'], $choice['attr'] ),
								esc_attr( $required_attr ),
								checked( '1', $choice['default'], false )
							);

							echo '<span class="wppopups-image-choices-label">' . wp_kses_post( $choice['label']['text'] ) . '</span>';

						echo '</label>';

					} else {

						// Normal display.
						printf(
							'<input type="checkbox" %s %s %s>',
							wppopups_html_attributes( $choice['id'], $choice['class'], $choice['data'], $choice['attr'] ),
							esc_attr( $required_attr ),
							checked( '1', $choice['default'], false )
						);

						printf(
							'<label %s>%s%s</label>',
							wppopups_html_attributes( $choice['label']['id'], $choice['label']['class'], $choice['label']['data'], $choice['label']['attr'] ),
							wp_kses_post( $choice['label']['text'] ),
							$required
						); // phpcs:ignore
					}

				echo '</li>';
			}

		echo '</ul>';
	}

	/**
	 * Validate field on form submit.
	 *
	 * @since 1.5.2
	 *
	 * @param int   $field_id       field ID.
	 * @param array $field_submit   submitted data.
	 * @param array $form_data      form data.
	 */
	public function validate( $field_id, $field_submit, $form_data ) {

		$field_submit  = (array) $field_submit;
		$choice_limit  = empty( $form_data['fields'][ $field_id ]['choice_limit'] ) ? 0 : (int) $form_data['fields'][ $field_id ]['choice_limit'];
		$count_choices = count( $field_submit );

		if ( $choice_limit > 0 && $count_choices > $choice_limit ) {
			// Generating the error.
			$error = wppopups_setting( 'validation-check-limit', esc_html__( 'You have exceeded the number of allowed selections: {#}.', 'wp-popups-lite' ) );
			$error = str_replace( '{#}', $choice_limit, $error );
		}

		// Basic required check - If field is marked as required, check for entry data.
		if (
			! empty( $form_data['fields'][ $field_id ]['required'] ) &&
			(
				empty( $field_submit ) ||
				(
					count( $field_submit ) === 1 &&
					( ! isset( $field_submit[0] ) || (string) $field_submit[0] === '' )
				)
			)
		) {
			$error = wppopups_get_required_label();
		}

		if ( ! empty( $error ) ) {
			wppopups()->process->errors[ $form_data['id'] ][ $field_id ] = $error;
		}
	}

	/**
	 * Format and sanitize field.
	 *
	 * @since 1.0.2
	 *
	 * @param int   $field_id     Field ID.
	 * @param array $field_submit Submitted form data.
	 * @param array $form_data    Form data and settings.
	 */
	public function format( $field_id, $field_submit, $form_data ) {

		$field_submit = (array) $field_submit;
		$field        = $form_data['fields'][ $field_id ];
		$dynamic      = ! empty( $field['dynamic_choices'] ) ? $field['dynamic_choices'] : false;
		$name         = sanitize_text_field( $field['label'] );
		$value_raw    = wppopups_sanitize_array_combine( $field_submit );

		$data = array(
			'name'      => $name,
			'value'     => '',
			'value_raw' => $value_raw,
			'id'        => absint( $field_id ),
			'type'      => $this->type,
		);

		if ( 'post_type' === $dynamic && ! empty( $field['dynamic_post_type'] ) ) {

			// Dynamic population is enabled using post type.
			$value_raw                 = implode( ',', array_map( 'absint', $field_submit ) );
			$data['value_raw']         = $value_raw;
			$data['dynamic']           = 'post_type';
			$data['dynamic_items']     = $value_raw;
			$data['dynamic_post_type'] = $field['dynamic_post_type'];
			$posts                     = array();

			foreach ( $field_submit as $id ) {
				$post = get_post( $id );

				if ( ! is_wp_error( $post ) && ! empty( $post ) && $data['dynamic_post_type'] === $post->post_type ) {
					$posts[] = esc_html( $post->post_title );
				}
			}

			$data['value'] = ! empty( $posts ) ? wppopups_sanitize_array_combine( $posts ) : '';

		}
		elseif ( 'taxonomy' === $dynamic && ! empty( $field['dynamic_taxonomy'] ) ) {

			// Dynamic population is enabled using taxonomy.
			$value_raw                = implode( ',', array_map( 'absint', $field_submit ) );
			$data['value_raw']        = $value_raw;
			$data['dynamic']          = 'taxonomy';
			$data['dynamic_items']    = $value_raw;
			$data['dynamic_taxonomy'] = $field['dynamic_taxonomy'];
			$terms                    = array();

			foreach ( $field_submit as $id ) {
				$term = get_term( $id, $field['dynamic_taxonomy'] );

				if ( ! is_wp_error( $term ) && ! empty( $term ) ) {
					$terms[] = esc_html( $term->name );
				}
			}

			$data['value'] = ! empty( $terms ) ? wppopups_sanitize_array_combine( $terms ) : '';

		}
		else {

			// Normal processing, dynamic population is off.
			$choice_keys = array();

			// If show_values is true, that means values posted are the raw values
			// and not the labels. So we need to set label values. Also store
			// the choice keys.
			if ( ! empty( $field['show_values'] ) && '1' == $field['show_values'] ) {

				foreach ( $field_submit as $item ) {
					foreach ( $field['choices'] as $key => $choice ) {
						if ( $item === $choice['value'] || ( empty( $choice['value'] ) && (int) str_replace( 'Choice ', '', $item ) === $key ) ) {
							$value[]       = $choice['label'];
							$choice_keys[] = $key;
							break;
						}
					}
				}

				$data['value'] = ! empty( $value ) ? wppopups_sanitize_array_combine( $value ) : '';

			} else {

				$data['value'] = $value_raw;

				// Determine choices keys, this is needed for image choices.
				foreach ( $field_submit as $item ) {
					foreach ( $field['choices'] as $key => $choice ) {
						/* translators: %s - choice number. */
						if ( $item === $choice['label'] || $item === sprintf( esc_html__( 'Choice %s', 'wp-popups-lite' ), $key ) ) {
							$choice_keys[] = $key;
							break;
						}
					}
				}
			}

			// Images choices are enabled, lookup and store image URLs.
			if ( ! empty( $choice_keys ) && ! empty( $field['choices_images'] ) ) {

				$data['images'] = array();

				foreach ( $choice_keys as $key ) {
					$data['images'][] = ! empty( $field['choices'][ $key ]['image'] ) ? esc_url_raw( $field['choices'][ $key ]['image'] ) : '';
				}
			}
		}

		// Push field details to be saved.
		wppopups()->process->fields[ $field_id ] = $data;
	}
}

new WPPopups_Field_Checkbox();
