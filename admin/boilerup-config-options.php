<?php

if ( !defined('ABSPATH') ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}


if ( ! class_exists( 'PurdueBranding_Settings_Page' ) ) :
	class PurdueBranding_Settings_Page {

		public function __construct() {
	
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_action( 'admin_init', array( $this, 'init_settings'  ) );
	
		}
	
		public function add_admin_menu() {
	
			add_theme_page(
				esc_html__( 'Purdue Branding Options', 'purdue' ),
				esc_html__( 'Purdue Branding', 'purdue' ),
				'manage_options',
				'boilerup',
				array( $this, 'save_settings' ), 99
			);
	
		}
	
		public function init_settings() {
	
			register_setting(
				'boilerup_group',
				'bolierup_branding'
			);
	
			add_settings_section(
				'bolierup_branding_section',
				'',
				false,
				'bolierup_branding'
			);
	
			add_settings_field(
				'boilerup-favicon',
				__( 'Disable Favicon', 'purdue' ),
				array( $this, 'render_favicon_field' ),
				'bolierup_branding',
				'bolierup_branding_section'
			);
			add_settings_field(
				'boilerup-brandfonts',
				__( 'Disable Brand Fonts', 'purdue' ),
				array( $this, 'render_brandfonts_field' ),
				'bolierup_branding',
				'bolierup_branding_section'
			);
			add_settings_field(
				'boilerup-systemtest',
				__( 'Disable ITaP Requested Tests', 'purdue' ),
				array( $this, 'render_systemtest_field' ),
				'bolierup_branding',
				'bolierup_branding_section'
			);
	
		}
	
		public function save_settings() {
	
			// Check required user capability
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'purdue' ) );
			}
	
			// Admin Page Layout
			echo '<div class="wrap">' . "\n";
			echo '	<h1>' . get_admin_page_title() . '</h1>' . "\n";
			echo '	<form action="options.php" method="post">' . "\n";
	
			settings_fields( 'boilerup_group' );
			do_settings_sections( 'bolierup_branding' );
			submit_button();
	
			echo '	</form>' . "\n";
			echo '</div>' . "\n";
	
		}
	
		function render_favicon_field() {
	
			// Retrieve data from the database.
			$options = get_option( 'bolierup_branding' );
	
			// Set default value.
			$value = isset( $options['boilerup-favicon'] ) ? $options['boilerup-favicon'] : '';
	
			// Field output.
			echo '<input type="checkbox" name="bolierup_branding[boilerup-favicon]" class="boilerup-favicon_field" value="checked" ' . checked( $value, 'checked', false ) . '> ' . __( '', 'purdue' );
	
		}
	
		function render_brandfonts_field() {
	
			// Retrieve data from the database.
			$options = get_option( 'bolierup_branding' );
	
			// Set default value.
			$value = isset( $options['boilerup-brandfonts'] ) ? $options['boilerup-brandfonts'] : '';
	
			// Field output.
			echo '<input type="checkbox" name="bolierup_branding[boilerup-brandfonts]" class="boilerup-brandfonts_field" value="checked" ' . checked( $value, 'checked', false ) . '> ' . __( '', 'purdue' );
	
		}

		function render_systemtest_field() {
	
			// Retrieve data from the database.
			$options = get_option( 'bolierup_branding' );
	
			// Set default value.
			// $value = isset( $options['boilerup-systemtest'] ) ? $options['boilerup-systemtest'] : '';
			$value = isset($_ENV['PANTHEON_ENVIRONMENT']) ? '' : 'checked';

			// Field output.
			echo '<input type="checkbox" name="bolierup_branding[boilerup-systemtest]" class="boilerup-systemtest_field" value="checked" disabled ' . checked( $value, 'checked', false ) . '> ' . __( '', 'purdue' );
	
		}
	
	}


endif;
