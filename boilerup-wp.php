<?php

/*
   Plugin Name: Purdue University Branding 
   Plugin URI: http://www.purdue.edu
   description: Add Purdue University fonts, favicon and logos to WordPress
   Version: 1.8.4
   Author: Purdue Marketing and Communications
   Author URI: https://marcom.purdue.edu
*/

if ( !defined('ABSPATH') ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

if ( ! class_exists( 'PurdueBranding' ) ) :
	/**
	 *
	 */
	class PurdueBranding {
        private static $myURL;

        public function __construct() {
            if (is_link( WP_CONTENT_DIR . '/mu-plugins' )) {
                self::$myURL = WP_CONTENT_URL . '/mu-plugins/boilerup-wp/';
            } else {
                self::$myURL = plugin_dir_url( __FILE__ );
            }
            
			self::includes();
			self::hooks();
		}

		private static function includes() {
            require_once dirname( __FILE__ ) . '/admin/boilerup-config-options.php';
            new PurdueBranding_Settings_Page();
		}

		private static function hooks() {
            $settings = get_option('bolierup_branding');

            //Brand Fonts
            if ( ! isset($settings['boilerup-brandfonts']) ) {
                add_action( 'wp_enqueue_scripts', array( __CLASS__, 'adobeFonts' ) );
                add_action( 'wp_enqueue_scripts', array( __CLASS__, 'unitedsansFont' ) );
                add_action( 'wp_enqueue_scripts', array( __CLASS__, 'sourceSerifPro' ) );

                add_action( 'admin_enqueue_scripts', array( __CLASS__, 'adobeFonts' ) );
                add_action( 'admin_enqueue_scripts', array( __CLASS__, 'unitedsansFont' ) );
                add_action( 'admin_enqueue_scripts', array( __CLASS__, 'sourceSerifPro' ) );
            }

            // Favicon
            if ( ! isset($settings['boilerup-favicon']) ) {
                add_action( 'wp_head', array( __CLASS__, 'add_header_icons' ) );
            }

            $disableTest = isset($_ENV['PANTHEON_ENVIRONMENT']) ? false : true;
            if ( $disableTest ) {
                add_filter( 'site_status_tests', array( __CLASS__, 'disable_managed_tests') );
            }

            // Login Form Branding
            add_action( 'login_enqueue_scripts', array( __CLASS__, 'my_login_logo') );
            add_filter( 'login_headerurl', array( __CLASS__, 'my_login_logo_url') );
            add_filter( 'login_headertext', array( __CLASS__, 'my_login_logo_url_title') );

        }
        
        public static function adobeFonts() {
            wp_enqueue_style( 
                'brandfonts', 'https://use.typekit.net/ghc8hdz.css'
            );
        }

        public static function unitedsansFont() {
            wp_enqueue_style( 
                'unitedsans', esc_url( self::$myURL . 'unitedsans.css' )
            );
        }
        
        public static function sourceSerifPro() {
            wp_enqueue_style( 
                'sourceserif', 'https://fonts.googleapis.com/css2?family=Source+Serif+Pro:wght@400;600;700&display=swap'
            );
        }

        
        public static function add_header_icons() {
            ?>

            <link rel="shortcut icon" href="<?php echo esc_url( self::$myURL . 'favicon/favicon.ico'); ?>" type="image/x-icon" />
            <link rel="apple-touch-icon" sizes="57x57" href="<?php echo esc_url( self::$myURL . 'favicon/apple-icon-57x57.png');?>">
            <link rel="apple-touch-icon" sizes="60x60" href="<?php echo esc_url( self::$myURL . 'favicon/apple-icon-60x60.png');?>">
            <link rel="apple-touch-icon" sizes="72x72" href="<?php echo esc_url( self::$myURL . 'favicon/apple-icon-72x72.png');?>">
            <link rel="apple-touch-icon" sizes="76x76" href="<?php echo esc_url( self::$myURL . 'favicon/apple-icon-76x76.png');?>">
            <link rel="apple-touch-icon" sizes="114x114" href="<?php echo esc_url( self::$myURL . 'favicon/apple-icon-114x114.png');?>">
            <link rel="apple-touch-icon" sizes="120x120" href="<?php echo esc_url( self::$myURL . 'favicon/apple-icon-120x120.png');?>">
            <link rel="apple-touch-icon" sizes="144x144" href="<?php echo esc_url( self::$myURL . 'favicon/apple-icon-144x144.png');?>">
            <link rel="apple-touch-icon" sizes="152x152" href="<?php echo esc_url( self::$myURL . 'favicon/apple-icon-152x152.png');?>">
            <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( self::$myURL . 'favicon/apple-icon-180x180.png');?>">
            <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo esc_url( self::$myURL . 'favicon/android-icon-192x192.png');?>">
            <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( self::$myURL . 'favicon/favicon-32x32.png');?>">
            <link rel="icon" type="image/png" sizes="96x96" href="<?php echo esc_url( self::$myURL . 'favicon/favicon-96x96.png');?>">
            <link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( self::$myURL . 'favicon/favicon-16x16.png');?>">
            <link rel="manifest" href="<?php echo esc_url( self::$myURL . 'favicon/manifest.json');?>">
            <meta name="msapplication-TileColor" content="#ffffff">
            <meta name="msapplication-TileImage" content="<?php echo esc_url( self::$myURL . 'favicon/ms-icon-144x144.png');?>">
            <meta name="theme-color" content="#ffffff">
            <?php
        }

        public static function my_login_logo_url_title() {
            return esc_html__('Admin Log In: Purdue University', 'purdue');
        }

        public static function my_login_logo_url() {
            return 'https://www.purdue.edu/';
        }

        public static function my_login_logo() { ?>
            <style type="text/css">
            #login h1 a, .login h1 a {
                background-image: url(<?php echo esc_url(self::$myURL . 'img/purdue-logo.png'); ?>) !important;
                height:57px !important;
                width:320px !important;
                background-size: 320px 57px !important;
                background-repeat: no-repeat !important;
                padding-bottom: 20px !important;
                padding-left: 10px !important;
            }
            
            body.login {
                background: #f0f0f0 !important;
            }
            
            .login #login_error, .login .message {
                border-left: 4px solid #98700D !important;
            }
            
            body.login div#login p#nav a, body.login div#login p#backtoblog a {
                color: #000000 !important;
            }
        
            body.login div#login p#nav a:hover, body.login div#login p#backtoblog a:hover {
                color: #C28E0E !important;
            }
            
            .wp-core-ui .button-primary {
                background-color: #98700D !important;
                background: #98700D !important;
                border-color: #98700D !important;
                border-bottom-color: #98700D !important;
                box-shadow: rgba(0, 0, 0, 0.3) 1px 1px 0 !important;
                text-shadow: rgba(0, 0, 0, 0.3) 0 -1px 0 !important;
                font-weight: bold !important;
            }
            
            .wp-core-ui .button-primary:hover {
                background-color: #C28E0E !important;
                background: #C28E0E !important;
                border-color: #98700D !important;
                box-shadow: rgba(0, 0, 0, 0.3) 1px 1px 0 !important;
                text-shadow: rgba(0, 0, 0, 0.3) 0 -1px 0 !important;
                font-weight: bold !important;
            }
            
            .login input:focus {
                border-color: #C28E0E !important;
                box-shadow: 0 0 2px rgba(194, 142, 14, 0.8) !important;
            }
            
            .login input[type=checkbox]:checked:before {
                color: #000000 !important;
            }
            </style>
        <?php }

        /** 
         * Disable health checks for managed features
         *
         * These tests aren't useful in this environment, and distract from
         * legitimate tests.
         */
        public static function disable_managed_tests( $tests ) {
            // The WordPress version is controlled by monthly auto-updates
            unset( $tests['direct']['wordpress_version'] );
            
            // The PHP version is set by the system vendor and cannot be updated
            unset( $tests['direct']['php_version'] );
            
            // The MySQL version is set by the ITIS DBA team and changes slowly
            unset( $tests['direct']['sql_server'] );
            
            // REST isn't available off campus for security reasons
            unset( $tests['direct']['rest_availability'] );
            
            // WordPress isn't allowed to update itself for security reasons
            unset( $tests['async']['background_updates'] );
            
            // Send overrides to WordPress
            return $tests;
        }
    }

    new PurdueBranding();
endif;


