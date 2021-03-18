<?php

/*
   Plugin Name: Purdue University Branding 
   Plugin URI: http://www.purdue.edu
   description: Add Purdue University fonts, favicon and logos to WordPress
   Version: 1.6.0
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
            // self::$myURL = WP_CONTENT_URL . '/mu-plugins/boilerup-wp/';
			self::includes();
			self::hooks();
		}

		private static function includes() {
            require_once dirname( __FILE__ ) . '/inc/class-segment-tracker.php';
		}

		private static function hooks() {
            //Brand Fonts
            add_action( 'wp_enqueue_scripts', array( __CLASS__, 'adobeFonts' ) );
            add_action( 'wp_enqueue_scripts', array( __CLASS__, 'unitedsansFont' ) );
            add_action( 'wp_enqueue_scripts', array( __CLASS__, 'sourceSerifPro' ) );
            
            // FavIcon
            add_action( 'wp_head', array( __CLASS__, 'add_header_icons' ) );

            // Login Form Branding
            add_action( 'login_enqueue_scripts', array( __CLASS__, 'my_login_logo') );
            add_filter( 'login_headerurl', array( __CLASS__, 'my_login_logo_url') );
            add_filter( 'login_headertext', array( __CLASS__, 'my_login_logo_url_title') );

        }
        
        public static function adobeFonts() {
            wp_enqueue_style( 
                'brandfonts', 'https://use.typekit.net/hrz3oev.css'
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
            echo "<!-- DIR CONST: " . WP_CONTENT_DIR . '/mu-plugins/' . " -->";
            ?>

            <link rel="shortcut icon" href="<?php echo self::$myURL . 'favicon/favicon.ico'; ?>" type="image/x-icon" />
            <link rel="apple-touch-icon" sizes="57x57" href="<?php echo self::$myURL . 'favicon/apple-icon-57x57.png';?>">
            <link rel="apple-touch-icon" sizes="60x60" href="<?php echo self::$myURL . 'favicon/apple-icon-60x60.png';?>">
            <link rel="apple-touch-icon" sizes="72x72" href="<?php echo self::$myURL . 'favicon/apple-icon-72x72.png';?>">
            <link rel="apple-touch-icon" sizes="76x76" href="<?php echo self::$myURL . 'favicon/apple-icon-76x76.png';?>">
            <link rel="apple-touch-icon" sizes="114x114" href="<?php echo self::$myURL . 'favicon/apple-icon-114x114.png';?>">
            <link rel="apple-touch-icon" sizes="120x120" href="<?php echo self::$myURL . 'favicon/apple-icon-120x120.png';?>">
            <link rel="apple-touch-icon" sizes="144x144" href="<?php echo self::$myURL . 'favicon/apple-icon-144x144.png';?>">
            <link rel="apple-touch-icon" sizes="152x152" href="<?php echo self::$myURL . 'favicon/apple-icon-152x152.png';?>">
            <link rel="apple-touch-icon" sizes="180x180" href="<?php echo self::$myURL . 'favicon/apple-icon-180x180.png';?>">
            <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo self::$myURL . 'favicon/android-icon-192x192.png';?>">
            <link rel="icon" type="image/png" sizes="32x32" href="<?php echo self::$myURL . 'favicon/favicon-32x32.png';?>">
            <link rel="icon" type="image/png" sizes="96x96" href="<?php echo self::$myURL . 'favicon/favicon-96x96.png';?>">
            <link rel="icon" type="image/png" sizes="16x16" href="<?php echo self::$myURL . 'favicon/favicon-16x16.png';?>">
            <link rel="manifest" href="<?php echo self::$myURL . 'favicon/manifest.json';?>">
            <meta name="msapplication-TileColor" content="#ffffff">
            <meta name="msapplication-TileImage" content="<?php echo self::$myURL . 'favicon/ms-icon-144x144.png';?>">
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
                background-image: url(<?php echo  self::$myURL . 'img/purdue-logo.png'; ?>) !important;
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
    }

    new PurdueBranding();
endif;


