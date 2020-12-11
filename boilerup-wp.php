<?php

/*
   Plugin Name: Purdue University Branding 
   Plugin URI: http://www.purdue.edu
   description: Add Purdue University fonts, favicon and logos to WordPress
   Version: 1.4.2
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

        public function __construct() {
			self::includes();
			self::hooks();
		}

		private static function includes() {
            
		}

		private static function hooks() {
            add_action( 'wp_enqueue_scripts', array( __CLASS__, 'adobeFonts' ) );
            add_action( 'wp_enqueue_scripts', array( __CLASS__, 'unitedsansFont' ) );
            add_action( 'wp_enqueue_scripts', array( __CLASS__, 'sourceSerifPro' ) );
            // add_action( 'wp_footer', array( __CLASS__, 'add_segment_form_identify' ), 5 );
            add_action( 'wp_footer', array( __CLASS__, 'add_segment_body_code' ));
            add_action( 'wp_head', array( __CLASS__, 'add_segment_code' ), 5 );
            add_action( 'wp_head', array( __CLASS__, 'add_header_icons' ) );
            add_action( 'login_enqueue_scripts', array( __CLASS__, 'my_login_logo') );
            add_filter( 'login_headerurl', array( __CLASS__, 'my_login_logo_url') );
            add_filter( 'login_headertitle', array( __CLASS__, 'my_login_logo_url_title') );

        }
        
        public static function adobeFonts() {
            wp_enqueue_style( 
                'brandfonts', 'https://use.typekit.net/hrz3oev.css'
            );
        }

        public static function unitedsansFont() {
            wp_enqueue_style( 
                'unitedsans', esc_url( plugins_url( 'unitedsans.css', __FILE__ ) )
            );
        }
        
        public static function sourceSerifPro() {
            wp_enqueue_style( 
                'sourceserif', 'https://fonts.googleapis.com/css2?family=Source+Serif+Pro:wght@400;600;700&display=swap'
            );
        }

        public static function add_segment_code() {
            $segment = 'hFnjjDlw7Ww7VAWxQavhY4wUFAs3uxaF';
            $segment_prod = 'ELTWNTShdcGAnRQ6bzbp2GoHInijLSpx';
            if (isset($_ENV['PANTHEON_ENVIRONMENT']) && php_sapi_name() != 'cli') {
                if ($_ENV['PANTHEON_ENVIRONMENT'] === 'live') {
                  $segment = $segment_prod;
                } 
            } elseif ($_SERVER['HTTP_HOST'] === 'www.purdue.edu') {
                $segment = $segment_prod;
            }


            ?>
            <!-- Segment.com Analytics -->
            <script>
            !function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics.SNIPPET_VERSION="4.13.1";
            analytics.load("<?php echo $segment; ?>");
            analytics.page();
            }}();
            </script>
            <!-- END Segment.com Analytics -->

            <?php
        }

        public static function add_segment_form_identify() {
            ?>
            
            <script>
                var firstName = document.querySelector('.name_first > input').value || null
                var lastName = document.querySelector('.name_last > input').value || null
                var email = document.querySelector('.name_last > input').value || null


                var submit = document.querySelector('.gform_button[type="submit"]') || null

                if(submit !== null) {
                    submit.addEventListener('click', () => {
                        analytics.identify({
                            first_name: firstName,
                            last_name: lastName,
                            email: email
                        })
                    })
                }

            </script>

            <?php
        }
        public static function add_segment_body_code() {
            ?>
            
            <script>
                var segment_purdue = {
                    formSubmitted: function(event){
  
                        event.preventDefault();
                        timer=Math.floor((Date.now()-timerStart)/1000);
                        let form=event.target;
                        let formName=form.querySelector('.gform_heading')?form.querySelector('.gform_heading>.gform_title').innerHTML:document.querySelector('h1').innerHTML;
                        let messages=Array.prototype.slice.call(form.querySelectorAll('.validation_message'),0);
                        if(messages&&messages.length>0){
                            let messageText='';
                            messages.forEach((message)=>{
                                messageText=messageText+message.innerHTML+"\n";
                            })
                            let properties = {
                                form_name : formName,
                                time_on_page:timer,
                                validation_message:messageText
                            }
                            analytics.track('Form Submit Failed', properties);
                        }else{
                            let traits = {
                                first_name : form.querySelector('.name_first > input').value || null,
                                last_name : form.querySelector('.name_last > input').value || null,
                                email : form.querySelector('.name_last > input').value || null
                            };
                            analytics.identify(traits); 
                            let properties = {
                                form_name : formName,
                                time_on_page:timer
                            }
                            analytics.track('Form Submitted', properties);
                        }   
                        setTimeout(function(){ 
                            form.submit()
                        }, 300);                 
                    },
                    init: function() {
                        //G-forms
                        var gFormWrappers = Array.prototype.slice.call(document.querySelectorAll('.gform_wrapper'), 0);
                        if(gFormWrappers&&gFormWrappers.length>0){
                            gFormWrappers.forEach((wrapper)=>{
                                let form=wrapper.querySelector('form')
                                form.addEventListener("submit",this.formSubmitted);
                            })
                        }
                        // this code will result in a Segment track event firing when the link is clicked
                        var links = Array.prototype.slice.call(document.getElementsByTagName('a'), 0);
                        const windowHeight=window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight
                        if(links&&links.length>0){
                            links.forEach((link)=>{
                                let href=link.href;
                                let ext=href.substring(href.lastIndexOf("/")+1).split('.').pop();
                                let scrollDepth=link.getBoundingClientRect().top>=windowHeight?link.getBoundingClientRect().top-windowHeight:0;
                                link.addEventListener('click',function(){
                                    event.preventDefault();
                                    timer=Math.floor((Date.now()-timerStart)/1000);
                                    if(ext&&ext!=="edu"&&ext!=="com"&&ext!=="org"&&ext!=="net"&&ext!=="php"&&ext!=="html"){
                                        analytics.track('Download Link Clicked', {
                                            text: link.innerText,
                                            destination_href:href,
                                            file_type: ext,
                                            time_on_page:timer,
                                            scroll_depth:scrollDepth
                                        });
                                    }
                                    if(href.substring(0,href.indexOf(":")+1)==="mailto:"){
                                        analytics.track('Email Link Clicked', {
                                            destination_href:href,
                                            time_on_page:timer
                                        });
                                    }else if(href.substring(0,href.indexOf(":")+1)==="tel:"){
                                        analytics.track('Phone Link Clicked', {
                                            destination_href:href,
                                            time_on_page:timer
                                        });
                                    }
                                    if(link.host&&link.host!==""&&link.host!==window.location.host){
                                        if(link.host.indexOf("www.facebook.com")!==-1||
                                            link.host.indexOf("www.twitter.com")!==-1||
                                            link.host.indexOf("www.instagram.com")!==-1||
                                            link.host.indexOf("www.snapchat.com")!==-1||
                                            link.host.indexOf("www.linkedin.com")!==-1||
                                            link.host.indexOf("www.youtube.com")!==-1||
                                            link.host.indexOf("www.pinterest.com")!==-1||
                                            link.host.indexOf("www.amazon.com")!==-1){
                                                analytics.track('social Link Clicked', {
                                                destination_href:href,
                                                time_on_page:timer
                                            });
                                        }else{
                                            analytics.track('External Link Clicked', {
                                                text: link.innerText,
                                                destination_href:href,
                                                time_on_page:timer,
                                                scroll_depth:scrollDepth
                                            });
                                        }

                                    }
                                    if(link.classList.contains('pu-cta-banner-gray__desc')||
                                        link.classList.contains('pu-cta-banner-image__button')||
                                        link.classList.contains('pu-cta-banner-gold__button')||
                                        link.classList.contains('pu-cta-banner-black__button')||
                                        link.classList.contains('cta-card__button')||
                                        link.classList.contains('cta-card-small')||
                                        link.classList.contains('pu-cta-hero__button')||
                                        link.classList.contains('pu-feature-story__button')||
                                        link.classList.contains('pu-proofpoint__button')||
                                        link.classList.contains('cta-button')){
                                        analytics.track('CTA Link Clicked', {
                                            text: link.innerText,
                                            destination_href:link.href,
                                            time_on_page:timer,
                                            scroll_depth:scrollDepth
                                        });
                                    }
                                    setTimeout(function(){ 
                                        window.open(link.href, link.target&&link.target==="_blank"?"_blank":"_self")
                                    }, 300);
                                })
                            })
                        }
                        //404 page 
                        const h1=document.querySelector('h1').innerHTML;
                        if(h1==="Page Not Found"){
                            analytics.track('404 Page Viewed', {
                                page_href: window.location.href,
                                referrer: document.referrer
                            });
                        }
                    }
                }
                var timer;
                var timerStart;
                window.onload=function(){
                    timer=0;
                    timerStart=Date.now();
                }
                analytics.ready(
                segment_purdue.init()
                );

            </script>

            <?php
        }
        public static function add_header_icons() {
            ?>

            <link rel="shortcut icon" href="<?php echo esc_url( plugins_url( 'favicon/favicon.ico', __FILE__ ) ); ?>" type="image/x-icon" />
            <link rel="apple-touch-icon" sizes="57x57" href="<?php echo esc_url( plugins_url( 'favicon/apple-icon-57x57.png', __FILE__ ) );?>">
            <link rel="apple-touch-icon" sizes="60x60" href="<?php echo esc_url( plugins_url( 'favicon/apple-icon-60x60.png', __FILE__ ) );?>">
            <link rel="apple-touch-icon" sizes="72x72" href="<?php echo esc_url( plugins_url( 'favicon/apple-icon-72x72.png', __FILE__ ) );?>">
            <link rel="apple-touch-icon" sizes="76x76" href="<?php echo esc_url( plugins_url( 'favicon/apple-icon-76x76.png', __FILE__ ) );?>">
            <link rel="apple-touch-icon" sizes="114x114" href="<?php echo esc_url( plugins_url( 'favicon/apple-icon-114x114.png', __FILE__ ) );?>">
            <link rel="apple-touch-icon" sizes="120x120" href="<?php echo esc_url( plugins_url( 'favicon/apple-icon-120x120.png', __FILE__ ) );?>">
            <link rel="apple-touch-icon" sizes="144x144" href="<?php echo esc_url( plugins_url( 'favicon/apple-icon-144x144.png', __FILE__ ) );?>">
            <link rel="apple-touch-icon" sizes="152x152" href="<?php echo esc_url( plugins_url( 'favicon/apple-icon-152x152.png', __FILE__ ) );?>">
            <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( plugins_url( 'favicon/apple-icon-180x180.png', __FILE__ ) );?>">
            <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo esc_url( plugins_url( 'favicon/android-icon-192x192.png', __FILE__ ) );?>">
            <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( plugins_url( 'favicon/favicon-32x32.png', __FILE__ ) );?>">
            <link rel="icon" type="image/png" sizes="96x96" href="<?php echo esc_url( plugins_url( 'favicon/favicon-96x96.png', __FILE__ ) );?>">
            <link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( plugins_url( 'favicon/favicon-16x16.png', __FILE__ ) );?>">
            <link rel="manifest" href="<?php echo esc_url( plugins_url( 'favicon/manifest.json', __FILE__ ) );?>">
            <meta name="msapplication-TileColor" content="#ffffff">
            <meta name="msapplication-TileImage" content="<?php echo esc_url( plugins_url( 'favicon/ms-icon-144x144.png', __FILE__ ) );?>">
            <meta name="theme-color" content="#ffffff">
            <?php
        }

        public static function my_login_logo_url_title() {
            return 'Purdue University';
        }

        public static function my_login_logo_url() {
            return 'https://www.purdue.edu/';
        }

        public static function my_login_logo() { ?>
            <style type="text/css">
            #login h1 a, .login h1 a {
                background-image: url(<?php echo  esc_url( plugins_url( 'img/purdue-logo.png', __FILE__ ) );?>) !important;
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


