<?php

/**
 * Display Rules management panel.
 *
 * @package    WPPopups
 * @author     WPPopups
 * @since 2.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WP Popups LLC
 */
class WPPopups_Builder_Panel_Rules extends WPPopups_Builder_Panel {

	/**
	 * All systems go.
	 *
	 * @since 2.0.0
	 */
	public function init() {

		// Define panel information.
		$this->name    = esc_html__( 'Display Rules', 'wp-popups-lite' );
		$this->slug    = 'display-rules';
		$this->icon    = 'fa-eye';
		$this->order   = 10;
		$this->sidebar = true;
	}

	/**
	 * Outputs the Settings panel sidebar.
	 *
	 * @since 2.0.0
	 */
	public function panel_sidebar() {

		// Sidebar contents are not valid unless we have a popup.
		if ( ! $this->popup ) {
			return;
		}

		$sections = [
			'general' => esc_html__( 'Display Rules', 'wp-popups-lite' ),
		];
		foreach ( $sections as $slug => $section ) {
			$this->panel_sidebar_section( $section, $slug, '', false );
			echo sprintf( $this->panel_sidebar_content_section( $slug ), $this->sidebar_content() ); // phpcs:ignore
		}
		do_action( 'wppopups_rules_panel_sidebar', $this->popup );
	}

	/**
	 * Outputs the Settings panel primary content.
	 *
	 * @since 2.0.0
	 */
	public function panel_content() {

		if ( ! $this->mandatory_popup_exist() ) {
			return;
		}
		do_action( 'wppopups_popup_rules_panel_content_before', $this );

		if ( apply_filters( 'wppopups_cancel_popup_rules_panel_content', false ) ) {
			return;
		}

		echo '<div class="wppopups-panel-content-section wppopups-panel-content-section-general">';
		echo '<div class="wppopups-panel-content-section-title">';
		esc_html_e( 'Show this popup if:', 'wp-popups-lite' );
		echo '</div>';
		$rules = ! empty( $this->popup_data['rules'] ) ? $this->popup_data['rules'] : WPPopups_Rules::defaults();
		if ( is_array( $rules ) ) {
			foreach ( $rules as $group_key => $group ) {
				echo '<div class="rule-group" data-key="' . esc_attr( $group_key ) . '">';
				if ( is_array( $group ) ) {
					echo '<div class="rules-or"><span>OR</span></div>';
					foreach ( $group as $rule_key => $rule ) {
						echo '<div class="rule-tr" data-key="' . esc_attr( $rule_key ) . '">';
						echo '<div class="rule-td rule-option">';
						wppopups_rules_field(
							'select',
							$rule_key,
							$group_key,
							'rule',
							$this->popup_data,
							'',
							[
								'default' => 'page_type',
								'options' => WPPopups_Rules::options(),
								'data' => [ 'sorting' => false ]
							]
						);
						echo '</div>';// rule_td
						echo '<div class="rule-td rule-operator">';
						wppopups_rules_field(
							'select',
							$rule_key,
							$group_key,
							'operator',
							$this->popup_data,
							'',
							[
								'default' => '=',
								'options' => WPPopups_Rules::operators( $rule['rule'] ),
								'data' => [ 'sorting' => false ]
							]
						);
						echo '</div>';// rule_td
						echo '<div class="rule-td rule-value">';
						wppopups_rules_field(
							WPPopups_Rules::field_type( $rule['rule'] ),
							$rule_key,
							$group_key,
							'value',
							$this->popup_data,
							'',
							[
								'default' => '',
								'options' => WPPopups_Rules::values( $rule['rule'] ),
								'data' => [ 'sorting' => false ]
							]
						);
						echo '</div>';// rule_td
						echo '<div class="rule-td rule-actions">';
						echo '<a class="add button-primary" title="' . esc_html__( 'Add a new row on this group', 'wp-popups-lite' ) . '" href="#"><i class="fa fa-plus-circle"></i> AND</a>';
						echo '<a class="remove button"  title="' . esc_html__( 'Delete rule', 'wp-popups-lite' ) . '" href="#">&times;</a>';
						echo '</div>';// rule_td
						echo '</div>'; //rule_tr
					}
				}
				echo '</div>'; //rule_group

			}
		}
		echo '<button class="button-primary add-group">' . esc_html__( 'Add rule group (OR)', 'wp-popups-lite' ) . '</button>';
		// Can't find a way to clone existing choicesjs.
		$this->print_clone_group();

		do_action( 'wppopups_popup_rules_general', $this );
		echo '</div>';


		do_action( 'wppopups_popup_rules_panel_content', $this );
	}

	/**
	 * Helper function to print select fields for rules
	 * @since 2.0.0
	 *
	 */
	private function print_clone_group() {

		echo '<div class="rule-group rule-group-clone" data-key="group_clone_key" style="display: none;">';
		echo '<div class="rules-or"><span>OR</span></div>';
		echo '<div class="rule-tr" data-key="rule_clone_key">';
		echo '<div class="rule-td rule-option">';
		wppopups_rules_field(
			'select',
			'rule_id',
			'group_id',
			'rule',
			'',
			'',
			[
				'clean_select' => true,
				'default'      => 'page_type',
				'attributes'   => [ 'disabled' => 'disabled' ],
				'options'      => WPPopups_Rules::options(),
			]
		);
		echo '</div>';// rule_td
		echo '<div class="rule-td rule-operator">';
		wppopups_rules_field(
			'select',
			'rule_id',
			'group_id',
			'operator',
			'',
			'',
			[
				'clean_select' => true,
				'default'      => '=',
				'attributes'   => [ 'disabled' => 'disabled' ],
				'options'      => WPPopups_Rules::operators(),
			]
		);
		echo '</div>';// rule_td
		echo '<div class="rule-td rule-value">';
		wppopups_rules_field(
			'select',
			'rule_id',
			'group_id',
			'value',
			'',
			'',
			[
				'clean_select' => true,
				'attributes'   => [ 'disabled' => 'disabled' ],
				'default'      => '',
				'options'      => WPPopups_Rules::values(),
			]
		);
		echo '</div>';// rule_td
		echo '<div class="rule-td rule-actions">';
		echo '<a class="add button-primary" title="' . esc_html__( 'Add a new row on this group', 'wp-popups-lite' ) . '" href="#"><i class="fa fa-plus-circle"></i> AND</a>';
		echo '<a class="remove button"  title="' . esc_html__( 'remove row', 'wp-popups-lite' ) . '" href="#">&times;</a>';
		echo '</div>';// rule_td
		echo '</div>'; //rule_tr
		echo '</div>'; //rule_group
	}

	private function sidebar_content() {
		return '<p>Create a set of rules to determine where the popup will show</p>';
	}
}

new WPPopups_Builder_Panel_Rules();
