<?php

/*
   Plugin Name: Purdue University Branding 
   Plugin URI: http://www.purdue.edu
   description: Add Purdue University fonts, favicon and logos to WordPress
   Version: 1.5.0
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
            add_action( 'admin_enqueue_scripts', array( __CLASS__, 'adobeFonts' ) );
            add_action( 'admin_enqueue_scripts', array( __CLASS__, 'unitedsansFont' ) );
            add_action( 'admin_enqueue_scripts', array( __CLASS__, 'sourceSerifPro' ) );
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
            var timestamp;
            !function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics.SNIPPET_VERSION="4.13.1";
            var SMW1 = function({ payload, next, integrations }) {
                timestamp = payload.obj.timestamp;
                if(payload.obj.properties)
                    payload.obj.properties.timestamp = timestamp;
                next(payload);
            };
            analytics.addSourceMiddleware(SMW1);
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
                var timer;
                var timerStart;
                var winheight, docheight, trackLength;
                var segment_purdue = {
                    formSubmitted: function(event){
  
                        event.preventDefault();
                        let form=event.target;
                        let formId=form.id.substring(form.id.lastIndexOf("_")+1)
                        let item_time="gform_time_"+formId
                        let item_referrer="gform_referrer_"+formId  
                        let item_depth="gform_depth_"+formId  
                        let item_fname="gform_fname_"+formId  
                        let item_lname="gform_lname_"+formId  
                        let item_email="gform_email_"+formId  
                        let item_phone="gform_phone_"+formId  
                        let item_state="gform_state_"+formId  
                        let item_zip="gform_zip_"+formId  
                        let item_country="gform_country_"+formId  
                        let item_fail="gform_fail_"+formId  
                        let item_submit="gform_submit_"+formId  
                        let item_userType="gform_userType_"+formId

                        timer=Math.floor((Date.now()-timerStart)/1000);
                        sessionStorage.setItem(item_time, timer)  
                        sessionStorage.setItem(item_referrer, document.referrer)

                        let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                        let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                        sessionStorage.setItem(item_depth, scrollDepth)
  
                        // Select the first firstname, lastname, email, phone, state, postcode, and county as user's traits
                        let fname = form.querySelector('.name_first > input')?form.querySelector('.name_first > input').value : null
                        let lname = form.querySelector('.name_last > input')?form.querySelector('.name_last > input').value : null
                        let email = form.querySelector('.ginput_container_email > input')?form.querySelector('.ginput_container_email > input').value : null
                        let phone = form.querySelector('.ginput_container_phone > input')?form.querySelector('.ginput_container_phone > input').value : null
                        let state=form.querySelector('.address_state>input')?form.querySelector('.address_state>input').value : null
                        let postcode=form.querySelector('.address_zip>input')?form.querySelector('.address_zip>input').value : null
                        let country=form.querySelector('.address_country>select')?form.querySelector('.address_country>select').value : null

                        let userType=sessionStorage.getItem(item_userType);  
                        Array.prototype.slice.call(form.querySelectorAll('label'),0).forEach((label)=>{

                            if(label.textContent.indexOf("User Type")!==-1){
                                if(userType === "null"){
                                    sessionStorage.setItem(item_userType, label.nextElementSibling.querySelector('select').value)
                                }
                            }
                        })

                        sessionStorage.setItem(item_fname, fname)
                        sessionStorage.setItem(item_lname, lname)
                        sessionStorage.setItem(item_email, email)
                        sessionStorage.setItem(item_phone, phone)
                        sessionStorage.setItem(item_state, state)
                        sessionStorage.setItem(item_zip, postcode)
                        sessionStorage.setItem(item_country, country)
                        sessionStorage.setItem(item_fail, "submitted")
                        sessionStorage.setItem(item_submit, "submitted")

                        setTimeout(function(){ 
                            form.submit()
                        }, 300);                 
                    },
                    init: function() {

                        window.onload=function(){
                            timer=0;
                            timerStart=Date.now(); 
                            getmeasurements();
                        }
                       
                        //G-forms
                        const gFormWrappers = Array.prototype.slice.call(document.querySelectorAll('.gform_wrapper'), 0);
                        
                        if(gFormWrappers&&gFormWrappers.length>0){
                            gFormWrappers.forEach((wrapper,index)=>{
                            let form=wrapper.querySelector('form')
                            let formName=form.querySelector('.gform_heading')?form.querySelector('.gform_heading>.gform_title').innerHTML:document.querySelector('h1').innerHTML;
                            let formId=form.id.substring(form.id.lastIndexOf("_")+1)

                            var item_formName="gform_formName_"+formId
                            var session_gform_formName=sessionStorage.getItem(item_formName);
                                !session_gform_formName?sessionStorage.setItem(item_formName, formName):'';  

                            var item_userType="gform_userType_"+formId
                            var session_gform_userType=sessionStorage.getItem(item_userType);
                                !session_gform_userType?sessionStorage.setItem(item_userType, null):'';  
                           
                            var item_time="gform_time_"+formId  
                            var session_item_time=sessionStorage.getItem(item_time);  
                                !session_item_time?sessionStorage.setItem(item_time, ""):'';  
                                
                            var item_referrer="gform_referrer_"+formId  
                            var session_item_referrer=sessionStorage.getItem(item_referrer);  
                                !session_item_referrer?sessionStorage.setItem(item_referrer, ""):''; 

                            var item_depth="gform_depth_"+formId  
                            var session_item_depth=sessionStorage.getItem(item_depth);  
                                !session_item_depth?sessionStorage.setItem(item_depth, ""):'';  

                            var item_fname="gform_fname_"+formId  
                            var session_item_fname=sessionStorage.getItem(item_fname);  
                                !session_item_fname?sessionStorage.setItem(item_fname, null):'';  

                            var item_lname="gform_lname_"+formId  
                            var session_item_lname=sessionStorage.getItem(item_lname);  
                                !session_item_lname?sessionStorage.setItem(item_lname, null):''; 

                            var item_email="gform_email_"+formId  
                            var session_item_email=sessionStorage.getItem(item_email);  
                                !session_item_email?sessionStorage.setItem(item_email, null):'';  

                            var item_phone="gform_phone_"+formId  
                            var session_item_phone=sessionStorage.getItem(item_phone);  
                                !session_item_phone?sessionStorage.setItem(item_phone, null):''; 
                                
                            var item_state="gform_state_"+formId  
                            var session_item_state=sessionStorage.getItem(item_state);  
                                !session_item_state?sessionStorage.setItem(item_state, null):'';      

                            var item_zip="gform_zip_"+formId  
                            var session_item_zip=sessionStorage.getItem(item_zip);  
                                !session_item_zip?sessionStorage.setItem(item_zip, null):'';   

                            var item_country="gform_country_"+formId  
                            var session_item_country=sessionStorage.getItem(item_country);  
                                !session_item_country?sessionStorage.setItem(item_country, null):'';  

                            var item_fail="gform_fail_"+formId  
                            var session_item_fail=sessionStorage.getItem(item_fail);  
                                !session_item_fail?sessionStorage.setItem(item_fail, ""):'';  

                            var item_submit="gform_submit_"+formId  
                            var session_item_submit=sessionStorage.getItem(item_submit);  
                                !session_item_submit?sessionStorage.setItem(item_submit, ""):'';  

                                //form submit
                                form.addEventListener("submit", this.formSubmitted)

                                //Form submit failed
                                jQuery(document).on('gform_post_render', function(e, form_id) {   
                                    console.log('submit')
                                    
                                    if(form_id === parseInt(formId)) {
                                        let messages=Array.prototype.slice.call(form.querySelectorAll('.validation_message'),0);
                    
                                        if(messages&&messages.length>0&&session_item_fail!==""){
                                            let messageText='';
                                            messages.forEach((message)=>{
                                                messageText=messageText+message.innerHTML+"\n";
                                            })
                                            let properties = {
                                                total_form_submit_attempts:1,
                                                form_name:formName,
                                                time_on_page:session_item_time,
                                                scroll_depth:session_item_depth,
                                                timestamp:JSON.stringify(timestamp),
                                                referrer:session_item_referrer,
                                                validation_message:messageText,
                                                category:"Form submit failed",
                                                action:formName,
                                                label:messageText,
                                                value:1
                                            }
                                            analytics.track('Form Submit Failed', properties);
                                            sessionStorage.setItem(item_time, "")  
                                            sessionStorage.setItem(item_referrer, "")
                                            sessionStorage.setItem(item_depth, "")  
                                            sessionStorage.setItem(item_fname, null)
                                            sessionStorage.setItem(item_lname, null)
                                            sessionStorage.setItem(item_email, null)
                                            sessionStorage.setItem(item_phone, null)
                                            sessionStorage.setItem(item_state, null)
                                            sessionStorage.setItem(item_zip, null)
                                            sessionStorage.setItem(item_country, null)
                                            sessionStorage.setItem(item_fail, "")
                                            sessionStorage.setItem(item_submit, "")
                                        }                                        
                                    }
                                })

                            })
                        }
                        //form submit succeeded
                        var confirm_messages=Array.prototype.slice.call(document.querySelectorAll('.gform_confirmation_message'),0);
                        if(confirm_messages&&confirm_messages.length>0){
                            
                            confirm_messages.forEach((message)=>{
                                let formId=message.id.substring(message.id.lastIndexOf("_")+1)

                                let item_formName="gform_formName_"+formId
                                let session_gform_formName=sessionStorage.getItem(item_formName);

                                let item_userType="gform_userType_"+formId
                                let session_gform_userType=sessionStorage.getItem(item_userType);

                                let item_time="gform_time_"+formId  
                                let session_item_time=sessionStorage.getItem(item_time);  
                                    
                                var item_referrer="gform_referrer_"+formId  
                                var session_item_referrer=sessionStorage.getItem(item_referrer);  

                                var item_depth="gform_depth_"+formId  
                                var session_item_depth=sessionStorage.getItem(item_depth);  

                                var item_fname="gform_fname_"+formId  
                                var session_item_fname=sessionStorage.getItem(item_fname);  

                                var item_lname="gform_lname_"+formId  
                                var session_item_lname=sessionStorage.getItem(item_lname);  

                                var item_email="gform_email_"+formId  
                                var session_item_email=sessionStorage.getItem(item_email);  

                                var item_phone="gform_phone_"+formId  
                                var session_item_phone=sessionStorage.getItem(item_phone);  
                                    
                                var item_state="gform_state_"+formId  
                                var session_item_state=sessionStorage.getItem(item_state);  

                                var item_zip="gform_zip_"+formId  
                                var session_item_zip=sessionStorage.getItem(item_zip);  

                                var item_country="gform_country_"+formId  
                                var session_item_country=sessionStorage.getItem(item_country); 

                                var item_submit="gform_submit_"+formId  
                                var session_item_submit=sessionStorage.getItem(item_submit);  

                                var item_fail="gform_fail_"+formId  
                                var session_item_fail=sessionStorage.getItem(item_fail);  

                                if(session_item_submit&&session_item_submit!==""){
                                    let traits = {
                                        first_name : session_item_fname,
                                        last_name : session_item_lname,
                                        email : session_item_email,
                                        phone : session_item_phone,
                                        state: session_item_state,
                                        postCode: session_item_zip,
                                        country: session_item_country
                                    }; 
                                    analytics.identify(traits); 
                                    let properties = {
                                        form_name:session_gform_formName,
                                        time_on_page:session_item_time,
                                        scroll_depth:session_item_depth,
                                        timestamp:JSON.stringify(timestamp),
                                        referrer:session_item_referrer,
                                        user_type:session_gform_userType,
                                        total_form_submits:1,
                                        category:"Form submitted",
                                        action:session_gform_formName,
                                        label:session_gform_userType,
                                        value:1
                                    }
                                    analytics.track('Form Submitted', properties)
                                    sessionStorage.setItem(item_time, "")  
                                    sessionStorage.setItem(item_referrer, "")
                                    sessionStorage.setItem(item_depth, "")  
                                    sessionStorage.setItem(item_fname, null)
                                    sessionStorage.setItem(item_lname, null)
                                    sessionStorage.setItem(item_email, null)
                                    sessionStorage.setItem(item_phone, null)
                                    sessionStorage.setItem(item_state, null)
                                    sessionStorage.setItem(item_zip, null)
                                    sessionStorage.setItem(item_country, null)
                                    sessionStorage.setItem(item_fail, "")
                                    sessionStorage.setItem(item_submit, "")
                                    sessionStorage.setItem(item_userType, null)
                                } 
                            })
                        }

                        // this code will result in a Segment track event firing when the link is clicked
                        const links = Array.prototype.slice.call(document.getElementsByTagName('a'), 0);
                        const windowHeight=window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight
                        const h1=document.querySelector('h1');
                        const h1Text=h1?h1.innerHTML:'';

                        if(links&&links.length>0){
                            links.forEach((link)=>{
                                let href=link.href;
                                let ext_name=href.split('?')[0].split('/').pop()
                                let ext=ext_name.indexOf(".")!==-1?ext_name.substring(ext_name.lastIndexOf('.')+1):null
                                if(!link.classList.contains('ewd-ufaq-post-margin')){
                                    link.addEventListener('click',function(){
                                        event.preventDefault();
                                        timer=Math.floor((Date.now()-timerStart)/1000);
                                        let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                        let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                        let is_download=false
                                        
                                        if(ext){
                                            let total_downloads=1;
                                            let file_type;
                                            if(ext==="pdf"){
                                                is_download=true
                                                file_type="pdf";
                                                trackDownloadLink(link.innerText,href,file_type,timer,scrollDepth,timestamp,document.referrer,total_downloads,"Download",file_type,href,total_downloads);
                                            }else if(ext==="jpg"||ext==="png"||ext==="gif"||ext==="jpeg"||ext==="tiff"||ext==="tif"||ext==="svg"||ext==="psd"||ext==="ps"||ext==="ico"||ext==="bmp"||ext==="ai"||ext==="eps"){
                                                is_download=true
                                                file_type="image";
                                                trackDownloadLink(link.innerText,href,file_type,timer,scrollDepth,timestamp,document.referrer,total_downloads,"Download",file_type,href,total_downloads);
                                            }else if(ext==="doc"||ext==="docx"||ext==="xls"||ext==="xlsx"||ext==="ppt"||ext==="pptx"||ext==="key"||ext==="pages"||ext==="txt"||ext==="rtf"||ext==="odt"||ext==="ods"||ext==="csv"||ext==="tab"||ext==="vsd"){
                                                is_download=true
                                                file_type="other doc";
                                                trackDownloadLink(link.innerText,href,file_type,timer,scrollDepth,timestamp,document.referrer,total_downloads,"Download",file_type,href,total_downloads);
                                            }else if(ext==="aif"||ext==="mp3"||ext==="mpa"||ext==="wav"||ext==="wma"){
                                                is_download=true
                                                file_type="audio";
                                                trackDownloadLink(link.innerText,href,file_type,timer,scrollDepth,timestamp,document.referrer,total_downloads,"Download",file_type,href,total_downloads);
                                            }else if(ext==="pkg"||ext==="rar"||ext==="zip"||ext==="dmg"||ext==="exe"||ext==="dat"||ext==="xml"){
                                                is_download=true
                                                file_type="files";
                                                trackDownloadLink(link.innerText,href,file_type,timer,scrollDepth,timestamp,document.referrer,total_downloads,"Download",file_type,href,total_downloads);
                                            }else if(ext==="avi"||ext==="fiv"||ext==="h264"||ext==="h265"||ext==="m4v"||ext==="mov"||ext==="mp4"||ext==="mpg"||ext==="mpeg"||ext==="wmv"){
                                                is_download=true
                                                file_type="video";
                                                trackDownloadLink(link.innerText,href,file_type,timer,scrollDepth,timestamp,document.referrer,total_downloads,"Download",file_type,href,total_downloads);
                                            }
                                        }
                                        if(href.substring(0,href.indexOf(":")+1)==="mailto:"){
                                            let total_email_clicks=1
                                            trackEmailLink('Email Link Clicked',href,timer,scrollDepth,timestamp,document.referrer,total_email_clicks,"Clicks","Email links",href)
                                        }else if(href.substring(0,href.indexOf(":")+1)==="tel:"){
                                            let total_phone_clicks=1
                                            trackPhoneLink('Phone Link Clicked',href,timer,scrollDepth,timestamp,document.referrer,total_phone_clicks,"Clicks","Phone links",href)
                                        }
                                        if(link.host&&link.host!==""&&link.host!==window.location.host){
                                            if(link.host.indexOf("facebook.com")!==-1||
                                                link.host.indexOf("twitter.com")!==-1||
                                                link.host.indexOf("instagram.com")!==-1||
                                                link.host.indexOf("snapchat.com")!==-1||
                                                link.host.indexOf("linkedin.com")!==-1||
                                                link.host.indexOf("youtube.com")!==-1||
                                                link.host.indexOf("pinterest.com")!==-1||
                                                link.host.indexOf("amazon.com")!==-1){
                                                    trackOtherLink('Social Link Clicked',href,timer,scrollDepth,timestamp,document.referrer,"Clicks","Social links",href)
                                            }else if(!is_download){
                                                trackLink('External Link Clicked',link.innerText,href,timer,scrollDepth,timestamp,document.referrer,"Clicks","Outbound links",href)
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
                                            link.classList.contains('cta-button')||
                                            link.parentElement.parentElement.parentElement.classList.contains('navbar-end')){
                                                let label=link.innerText+" - "+href;
                                                trackLink('CTA Link Clicked',link.innerText,href,timer,scrollDepth,timestamp,document.referrer,"Clicks","CTA links",label)
                                        }
                                        //Search Results Page
                                        if(h1Text.substring(0,h1Text.indexOf(' '))==="Search"&&h1.nextElementSibling.classList.contains('search-box')&&h1Text!=="Search All Purdue"){
                                            if(link.parentElement.classList.contains("search-post-title")){
                                                let pageN=1;
                                                let query=getParameterByName('s')
                                                let total_search_result_clicks=1;
                                                if(document.querySelector('.pagination>.nav-links>.current')){
                                                    pageN=document.querySelector('.pagination>.nav-links>.current').innerHTML;
                                                }
                                                let label=link.innerText+" - "+pageN;
                                                trackSearchLink(link.innerText,query,pageN,timer,scrollDepth,timestamp,document.referrer,total_search_result_clicks,"Site search click",query,label,pageN)
                                            }
                                        }
                                        setTimeout(function(){ 
                                            window.open(link.href, link.target&&link.target==="_blank"?"_blank":"_self")
                                        }, 300);
                                    })
                                }
                            })
                        }
                        //404 page 
                        if(h1Text==="Page Not Found"){
                            analytics.track('404 Page Viewed', {
                                page_url: window.location.href,
                                timestamp:JSON.stringify(timestamp),
                                referrer: document.referrer,
                                category: "404 error",
                                action:window.location.href,
                                label:document.referrer
                            });
                        }
                        //Search performed
                        const searchForms=Array.prototype.slice.call(document.getElementsByName('searchform'),0);
                        searchForms.forEach((form)=>{
                            form.addEventListener('submit',function(event){
                                event.preventDefault();
                                timer=Math.floor((Date.now()-timerStart)/1000);
                                let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                let phrase=event.target.querySelector('.search-field').value || null;
                                let total_searches=1;

                                analytics.track("Site Search Performed", {
                                    query:phrase,
                                    total_searches:total_searches,
                                    time_on_page:timer,
                                    timestamp:JSON.stringify(timestamp),
                                    referrer:document.referrer,
                                    category:"site search performed",
                                    action:phrase,
                                    value:total_searches
                                })
                                setTimeout(function(){ 
                                    form.submit()
                                }, 300);  
                            })
                        })
                        //Google search result page
                        if(h1Text==="Search All Purdue"){
                            let checkLink = setInterval(function () {
                                let googleSearchLoaded=document.querySelector(".gsc-results-wrapper-visible")
                                if(googleSearchLoaded){
                                    getmeasurements();
                                    var clickLink=function(){
                                        let searchLinks = Array.prototype.slice.call(googleSearchLoaded.querySelectorAll('a.gs-title'), 0);
                                        if(searchLinks&&searchLinks.length>0){
                                            searchLinks.forEach((link)=>{
                                                link.addEventListener('click',function(){
                                                    event.preventDefault();
                                                    timer=Math.floor((Date.now()-timerStart)/1000);
                                                    let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                                    let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                                    let pageN=1;
                                                    let query=getParameterByName('q')
                                                    let total_search_result_clicks=1;
                                                    if(googleSearchLoaded.querySelector('.gsc-cursor-current-page')){
                                                        pageN=googleSearchLoaded.querySelector('.gsc-cursor-current-page').innerHTML;
                                                    }
                                                    let label=link.innerText+" - "+pageN;
                                                    trackSearchLink(link.innerText,query,pageN,timer,scrollDepth,timestamp,document.referrer,total_search_result_clicks,"Site search click",query,label,pageN)
                                                    console.log(link)
                                                    setTimeout(function(){ 
                                                        window.open(link.href, link.target&&link.target==="_blank"?"_blank":"_self")
                                                    }, 300);
                                                })
                                            })
                                        }
                                    }
                                    clickLink()
                                    let pageNos=Array.prototype.slice.call(googleSearchLoaded.querySelectorAll('.gsc-cursor-page'), 0);
                                    if(pageNos&&pageNos.length>0){
                                        pageNos.forEach((pageNo)=>{
                                            pageNo.addEventListener('click',function(){
                                                let checkloading = setInterval(function () {
                                                    let loading=document.querySelector('.gsc-loading-fade')
                                                    if(!loading){
                                                        clickLink()                                  
                                                        clearInterval(checkloading);
                                                    }
                                                }, 100);
                                                checkloading;
                                            })
                                        })
                                    } 
                                    clearInterval(checkLink);
                                }
                            }, 100);
                            checkLink;

                        }
                        //accordions
                        const accordions=Array.prototype.slice.call(document.querySelectorAll('.accordion-title'),0);
                        if(accordions&&accordions.length>0){
                            accordions.forEach((accordion) => {
                                accordion.addEventListener('click',(event)=>{
                                    if(!accordion.parentElement.classList.contains('is-open')){
                                        timer=Math.floor((Date.now()-timerStart)/1000);
                                        let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                        let scrollDepth=Math.floor(scrollTop/trackLength * 100)

                                        trackFAQ(accordion.innerHTML,window.location.pathname,timer,scrollDepth,timestamp,document.referrer,window.location.pathname,accordion.innerHTML)
                                    }
                                })

                            }) 
                        }
                        //FAQs on protect purdue site
                        const uFAQs=Array.prototype.slice.call(document.querySelectorAll('.ufaq-faq-title,.ewd-ufaq-faq-title'),0);
                        if(uFAQs&&uFAQs.length>0){
                            uFAQs.forEach((uFAQ) => {
                                uFAQ.addEventListener('click',(event)=>{
                                    console.log(uFAQ.parentElement.classList)
                                    if(uFAQ.nextElementSibling.classList.contains('ewd-ufaq-hidden')){
                                        timer=Math.floor((Date.now()-timerStart)/1000);
                                        let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                        let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                        let clickText=uFAQ.querySelector('.ufaq-faq-title-text,.ewd-ufaq-faq-title-text').textContent.trim()

                                        trackFAQ(clickText,window.location.pathname,timer,scrollDepth,timestamp,document.referrer,window.location.pathname,clickText)
                                    }
                                })

                            }) 
                        }
                        //Embeded videos
                        var youtubePlayers=[];
                        var vimeoPlayers=[];
                        const youtube=Array.prototype.slice.call(document.querySelectorAll('.wp-block-embed-youtube iframe'),0);
                        const vimeo=Array.prototype.slice.call(document.querySelectorAll('.wp-block-embed-vimeo iframe'),0);
                        const dmotion=Array.prototype.slice.call(document.querySelectorAll('.wp-block-embed-dailymotion iframe'),0);

                        //YouTube videos
                        if(youtube&&youtube.length>0){
                            let tag = document.createElement('script');
                            tag.src = "https://www.youtube.com/iframe_api";
                            let firstScriptTag = document.getElementsByTagName('script')[0];
                            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                            let checkYT = setInterval(function () {
                                if(typeof YT !== 'undefined'&&YT.loaded){
                                    youtube.forEach((iframe)=>{                                           
                                        if(iframe.src.indexOf("&origin=")!==-1){
                                                iframe.src=iframe.src.substring(0,iframe.src.indexOf("&origin"))
                                        }
                                        if(iframe.src.indexOf("&enablejsapi=1")===-1){
                                                iframe.src=iframe.src+"&enablejsapi=1"
                                        }
                                        
                                        if(!iframe.id){
                                            iframe.id="youtube"+iframe.src.split( 'embed/' )[1].split( '?' )[0]
                                        }                                 
                                        var player=new YT.Player( iframe.id, {
                                            videoId:iframe.src.split( 'embed/' )[1].split( '?' )[0],
                                            events: { 
                                                'onReady': onPlayerReady,
                                                'onStateChange': onPlayerStateChange 
                                            }
                                        }); 
                                        youtubePlayers.push({
                                            "id" :iframe.id,
                                            "player" : player
                                        });                             
                                    })
                                    
                                    clearInterval(checkYT);
                                }
                            }, 100);
                            checkYT;
                        }
                        //Vimeo videos
                        if(vimeo&&vimeo.length>0){
                            let tag = document.createElement('script');
                            tag.src = "https://player.vimeo.com/api/player.js";
                            let firstScriptTag = document.getElementsByTagName('script')[0];
                            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                            let checkVimeo = setInterval(function () {
                                if(typeof Vimeo !== 'undefined'){
                                    vimeo.forEach((iframe)=>{ 
                                        var url=iframe.src
                                        var percent=0;
                                        var player = new Vimeo.Player(iframe);
                                    
                                        async function viemoPlay(){
                                            try {
                                                const [title, duration] = await Promise.all([
                                                    player.getVideoTitle(),
                                                    player.getDuration(),
                                                ]);
                                                let label=title+" - "+url
                                                player.on('play', function(event) {
                                                    timer=Math.floor((Date.now()-timerStart)/1000);
                                                    let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                                    let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                                    let total_videos_started=1
                                                                
                                                    trackPlay("Vimeo",title,Math.round(event.seconds),duration,url,timer,scrollDepth,timestamp,document.referrer,total_videos_started,"video","Play",label);
                                                });
                                                player.on('pause', function(event) {
                                                    timer=Math.floor((Date.now()-timerStart)/1000);
                                                    let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                                    let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                                    if(Math.round(event.seconds)!==duration){
                                                        trackPause("Vimeo",title,Math.round(event.seconds),duration,url,timer,scrollDepth,timestamp,document.referrer,"video","Pause",label);
                                                    }
                                                    
                                                });
                                                player.on('ended', function(event) {
                                                    timer=Math.floor((Date.now()-timerStart)/1000);
                                                    let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                                    let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                                    let total_videos_completed=1
                                                    trackComplete("Vimeo",title,Math.round(event.seconds),duration,url,timer,scrollDepth,timestamp,document.referrer,total_videos_completed,"video","100%",label);
                                                });
                                                
                                                var lastTime=0;
                                                var currentTime=0;
                                                var seekStart = null;
                                                player.on('seeking', function(event) {
                                                    timer=Math.floor((Date.now()-timerStart)/1000);
                                                    let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                                    let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                                    if(seekStart === null){
                                                        seekStart=lastTime
                                                        trackSeek("Vimeo",title,Math.round(event.seconds),duration,url,timer,scrollDepth,timestamp,document.referrer,"video","Seek",label);   
                                                    }

                                                });
                                                player.on('seeked', function(event) {
                                                    seekStart = null;
                                                });
                                                player.on("timeupdate", function(event){
                                                    
                                                    timer=Math.floor((Date.now()-timerStart)/1000);
                                                    let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                                    let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                                    let percent_new=Math.round(event.seconds/duration*100);
                                                    if(percent_new!==percent){
                                                        percent=percent_new;
                                                        if(percent===25||percent===50||percent===75||percent===90){
                                                            let percentage=percent+'%'
                                                            let total_videos_progress=1
                                                            trackProgress("Vimeo",title,Math.round(event.seconds),duration,url,percentage,timer,scrollDepth,timestamp,document.referrer,total_videos_progress,"video",percentage,label)
                                                        }
                                                    }
                                                    lastTime = currentTime;
                                                    currentTime = event.seconds;
                                                })
                                                
                                            } catch (err) {
                                                console.log(err);
                                            }
                                        }
                                        viemoPlay()

                                    })                                          
                                    clearInterval(checkVimeo);
                                }
                            }, 100);
                            checkVimeo;
                    
                        }
                        //Dailymotion videos
                        if(dmotion&&dmotion.length>0){
                            let tag = document.createElement('script');
                            tag.src = "https://api.dmcdn.net/all.js";
                            let firstScriptTag = document.getElementsByTagName('script')[0];
                            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                            let checkDM = setInterval(function () {
                                if(typeof DM !== 'undefined'){
                                    dmotion.forEach((iframe)=>{ 
                                        const url=iframe.src;
                                        const videoID=url.substring(url.lastIndexOf('/')+1)
                                        var duration;
                                        var title;
                                        var percent=0;
                                        var label;
                                        DM.api(
                                            `/video/${videoID}`,
                                            { fields: ['duration', 'title' ]},
                                            result => {
                                                // result is an Object with all the fields wanted
                                                duration=result.duration;
                                                title=result.title;
                                                label=title+" - "+url;
                                            }
                                        )
                                        var player =DM.player(iframe,{
                                            video: videoID
                                        });
                                        
                                        player.addEventListener('play', function(event){
                                            timer=Math.floor((Date.now()-timerStart)/1000);
                                            let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                            let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                            let total_videos_started=1          
                                                
                                            trackPlay("Daily Motion",title,Math.round(event.target.currentTime),duration,url,timer,scrollDepth,timestamp,document.referrer,total_videos_started,"video","Play",label);                                                
                                        })
                                        player.addEventListener('pause', function(event){
                                            timer=Math.floor((Date.now()-timerStart)/1000);
                                            let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                            let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                            trackPause("Daily Motion",title,Math.round(event.target.currentTime),duration,url,timer,scrollDepth,timestamp,document.referrer,"video","Pause",label);
                                            
                                        })
                                        player.addEventListener('seeking', function(){
                                            timer=Math.floor((Date.now()-timerStart)/1000);
                                            let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                            let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                            trackSeek("Daily Motion",title,Math.round(event.target.currentTime),duration,url,timer,scrollDepth,timestamp,document.referrer,"video","Seek",label);
                                        })
                                        player.addEventListener('end', function(){
                                            timer=Math.floor((Date.now()-timerStart)/1000);
                                            let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                            let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                            let total_videos_completed=1
                                            trackComplete("Daily Motion",title,Math.round(event.target.currentTime),duration,url,timer,scrollDepth,timestamp,document.referrer,total_videos_completed,"video","100%",label);
                                        })
                                        player.addEventListener('timeupdate', function(){
                                            timer=Math.floor((Date.now()-timerStart)/1000);
                                            let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                            let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                            let percent_new=Math.round(event.target.currentTime/duration*100);
                                            if(percent_new!==percent){
                                                percent=percent_new;
                                                if(percent===25||percent===50||percent===75||percent===90){
                                                    let percentage=percent+'%'
                                                    let total_videos_progress=1
                                                    trackProgress("Daily Motion",title,Math.round(event.target.currentTime),duration,url,percentage,timer,scrollDepth,timestamp,document.referrer,total_videos_progress,"video",percentage,label)
                                                }
                                            }
                                        }) 

                                    })                                          
                                    clearInterval(checkDM);
                                }
                            }, 100);
                            checkDM;
                    
                        }
                        var focus = true;	
                        document.addEventListener("visibilitychange", function() {	
                            focus = document.hidden ? false:true;	
                        });
                        window.onPlayerReady=function(event) {

                            var lastTime = -1;
                            var lastState=-1;
                            const interval = 1000;
                            const margin = 1000;
                            var percent = 0;
                            const duration=event.target.getDuration();
                            const title=event.target.getVideoData().title;
                            const url=event.target.getVideoUrl();
                            let label=title+" - "+url  

                            var checkPlayerTime = function () {
                                timer=Math.floor((Date.now()-timerStart)/1000);
                                let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                if (lastTime !== -1) {
                              
                                    if(event.target.getPlayerState() === 1) {
                                        if (lastState===1 && Math.abs((event.target.getCurrentTime() - lastTime)*1000 - interval) > margin && focus) {
                                            trackSeek("YouTube",title,Math.round(event.target.getCurrentTime()),Math.round(duration),url,timer,scrollDepth,timestamp,document.referrer,"video","Seek",label)
                                        }else if(lastState!==1){
                                            let total_videos_started=1
                                                                                            
                                            trackPlay("YouTube",title,Math.round(event.target.getCurrentTime()),Math.round(duration),url,timer,scrollDepth,timestamp,document.referrer,total_videos_started,"video","Play",label);
                                        }
                                    }
                                    if(event.target.getPlayerState() === 2&&lastState===2) {
                                        if (Math.abs((event.target.getCurrentTime() - lastTime)*1000 - interval) > margin) {
                                            trackSeek("YouTube",title,Math.round(event.target.getCurrentTime()),Math.round(duration),url,timer,scrollDepth,timestamp,document.referrer,"video","Seek",label)
                                        }                                        
                                    }
                                    let percent_new=Math.round(event.target.getCurrentTime()/duration*100);
                                    if(percent_new!==percent){
                                        percent=percent_new;
                                        if(percent===25||percent===50||percent===75||percent===90){
                                            let percentage=percent+'%'
                                            let total_videos_progress=1
                                            trackProgress("YouTube",title,Math.round(event.target.getCurrentTime()),Math.round(duration),url,percentage,timer,scrollDepth,timestamp,document.referrer,total_videos_progress,"video",percentage,label)
                                        }
                                    }
                                    
                                }
                                lastTime = event.target.getCurrentTime();
                                lastState = event.target.getPlayerState();
                                setTimeout(checkPlayerTime, interval); /// repeat function call in 1 second
                            }
                            setTimeout(checkPlayerTime, interval); /// initial call delayed 
                        }  

                        function onPlayerStateChange(event) {
                           
                            const duration=event.target.getDuration();
                            const title=event.target.getVideoData().title;
                            const url=event.target.getVideoUrl();
                            timer=Math.floor((Date.now()-timerStart)/1000);
                            let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                            let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                            let label=title+" - "+url 

                            switch(event.data) {
                                case 0:
                                    let total_videos_completed=1
                                    trackComplete("YouTube",title,Math.round(event.target.getCurrentTime()),Math.round(duration),url,timer,scrollDepth,timestamp,document.referrer,total_videos_completed,"video","100%",label);
                                    break;
                                case 2:
                                    setTimeout(function() {
                                        if ( event.target.getPlayerState() == 2 && Math.round(event.target.getCurrentTime())!==duration ) {
                                            trackPause("YouTube",title,Math.round(event.target.getCurrentTime()),Math.round(duration),url,timer,scrollDepth,timestamp,document.referrer,"video","Pause",label);
                                        }
                                    }, 1000)
                                    break;
                            }
                                
                        }
                        //Uploaded videos
                        const videos=Array.prototype.slice.call(document.querySelectorAll('.wp-block-video video'));
                        if(videos&&videos.length>0){
                            videos.forEach((video)=>{
                                var duration;
                                var percent=0;
                                const title=video.nextElementSibling?video.nextElementSibling.innerHTML:'';
                                const url=video.src;
                                const ext=url.substring(url.lastIndexOf("/")+1).split('.').pop();
                                let label=title+" - "+url  
                                var lastTime=0;
                                var currentTime=0;
                                var seekStart = null;
                                var isSeeked= false;
                                var isSeekTest;
                                const SEEKEVENT_TIMEOUT = 200;

                                video.addEventListener("play", (event)=>{
                                    timer=Math.floor((Date.now()-timerStart)/1000);
                                    let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                    let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                    duration=video.duration;
                                    let total_videos_started=1

                                    if(!isSeeked){
                                    trackPlay(ext,title,Math.round(event.target.currentTime),Math.round(duration),url,timer,scrollDepth,timestamp,document.referrer,total_videos_started,"video","Play",label);
                                    }
                                })
                                video.addEventListener("pause", (event)=>{
                                    timer=Math.floor((Date.now()-timerStart)/1000);
                                    let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                    let scrollDepth=Math.floor(scrollTop/trackLength * 100)

                                    if(video.currentTime!==duration&&video.currentTime!==0){
                                        setTimeout(function() {
                                            if ( video.paused ) {
                                                trackPause(ext,title,Math.round(event.target.currentTime),Math.round(duration),url,timer,scrollDepth,timestamp,document.referrer,"video","Pause",label);
                                            }
                                        }, 1000)
                                    }
                                })

                                video.addEventListener("seeking", (event)=>{  
                                    timer=Math.floor((Date.now()-timerStart)/1000);
                                    let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                    let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                                                        
                                    if(seekStart === null){
                                        seekStart=lastTime
                                        trackSeek(ext,title,Math.round(seekStart),Math.round(duration),url,timer,scrollDepth,timestamp,document.referrer,"video","Seek",label);   
                                    }
            
                                })
                                video.addEventListener("seeked", (event)=>{  
                                    seekStart = null;
                                    isSeeked=true;
                                    setTimeout(function() {
                                        isSeeked=false;
                                    }, SEEKEVENT_TIMEOUT);
                                })
                                video.addEventListener("ended", (event)=>{
                                    timer=Math.floor((Date.now()-timerStart)/1000);
                                    let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                    let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                    let total_videos_completed=1
                                    trackComplete(ext,title,Math.round(event.target.currentTime),Math.round(duration),url,timer,scrollDepth,timestamp,document.referrer,total_videos_completed,"video","100%",label);
                                })
                                video.addEventListener("timeupdate", (event)=>{

                                    timer=Math.floor((Date.now()-timerStart)/1000);
                                    let scrollTop=window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
                                    let scrollDepth=Math.floor(scrollTop/trackLength * 100)
                                    let percent_new=Math.round(video.currentTime/duration*100);
                                    if(percent_new!==percent){
                                        percent=percent_new;
                                        if(percent===25||percent===50||percent===75||percent===90){
                                            let percentage=percent+'%'
                                            let total_videos_progress=1
                                            trackProgress(ext,title,Math.round(event.target.currentTime),Math.round(duration),url,percentage,timer,scrollDepth,timestamp,document.referrer,total_videos_progress,"video",percentage,label)
                                        }
                                    }
                                    lastTime = currentTime;
                                    currentTime = event.target.currentTime;
                                })
                            })
                        }
                        //Get query parameter
                        function getParameterByName(name, url = window.location.href) {
                            name = name.replace(/[\[\]]/g, '\\$&');
                            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                                results = regex.exec(url);
                            if (!results) return null;
                            if (!results[2]) return '';
                            return decodeURIComponent(results[2].replace(/\+/g, ' '));
                        }
                        //Tracking functions
                        function trackFAQ(click_text,path,time_on_page,scroll_depth,timestamp,referrer,action,label){
                            analytics.track('FAQ Clicked', {
                                click_text: click_text,
                                page_path: path,
                                time_on_page:time_on_page,
                                scroll_depth:scroll_depth,
                                timestamp:JSON.stringify(timestamp),
                                referrer: document.referrer,
                                category: "FAQ clicks",
                                action:action,
                                label:label
                            });
                        }
                        function trackSearchLink(click_text,query,page_number,time_on_page,scroll_depth,timestamp,referrer,total_search_result_clicks,category,action,label,value){
                            analytics.track("Search Results Page", {
                                click_text: click_text,
                                query:query,
                                page_number:page_number,
                                time_on_page:time_on_page,
                                scroll_depth:scroll_depth,
                                timestamp:JSON.stringify(timestamp),
                                referrer:referrer,
                                total_search_result_clicks:total_search_result_clicks,
                                category:category,
                                action:action,
                                label:label,
                                value:value
                            });
                        }
                        function trackLink(message,click_text,destination_href,time_on_page,scroll_depth,timestamp,referrer,category,action,label){
                            analytics.track(message, {
                                click_text: click_text,
                                destination_href:destination_href,
                                time_on_page:time_on_page,
                                scroll_depth:scroll_depth,
                                timestamp:JSON.stringify(timestamp),
                                referrer:referrer,
                                category:category,
                                action:action,
                                label:label
                            });
                        }
                        function trackDownloadLink(click_text,destination_href,file_type,time_on_page,scroll_depth,timestamp,referrer,total_downloads,category,action,label,value){
                            analytics.track('Download Link Clicked', {
                                click_text: click_text,
                                destination_href:destination_href,
                                file_type: file_type,
                                time_on_page:time_on_page,
                                scroll_depth:scroll_depth,
                                timestamp:JSON.stringify(timestamp),
                                referrer:referrer,
                                total_downloads:total_downloads,
                                category:category,
                                action:action,
                                label:label,
                                value:value
                            });
                        }
                        function trackEmailLink(message,destination_href,time_on_page,scroll_depth,timestamp,referrer,total_email_clicks,category,action,label){
                            analytics.track(message, {
                                destination_href:destination_href,
                                time_on_page:time_on_page,
                                scroll_depth:scroll_depth,
                                timestamp:JSON.stringify(timestamp),
                                referrer:referrer,
                                total_email_clicks:total_email_clicks,
                                category:category,
                                action:action,
                                label:label
                            });
                        }
                        function trackPhoneLink(message,destination_href,time_on_page,scroll_depth,timestamp,referrer,total_phone_clicks,category,action,label){
                            analytics.track(message, {
                                destination_href:destination_href,
                                time_on_page:time_on_page,
                                scroll_depth:scroll_depth,
                                timestamp:JSON.stringify(timestamp),
                                referrer:referrer,
                                total_phone_clicks:total_phone_clicks,
                                category:category,
                                action:action,
                                label:label
                            });
                        }
                        function trackOtherLink(message,destination_href,time_on_page,scroll_depth,timestamp,referrer,category,action,label){
                            analytics.track(message, {
                                destination_href:destination_href,
                                time_on_page:time_on_page,
                                scroll_depth:scroll_depth,
                                timestamp:JSON.stringify(timestamp),
                                referrer:referrer,
                                category:category,
                                action:action,
                                label:label
                            });
                        }
                        function trackPlay(player,title,position,length,url,time_on_page,scroll_depth,timestamp,referrer,total_videos_started,category,action,label){
                            analytics.track('Video Playback Started', {
                                video_player: player,
                                video_title:title,
                                video_position:position,
                                video_total_length:length,
                                video_url:url,
                                video_status: "play",
                                time_on_page:time_on_page,
                                scroll_depth:scroll_depth,
                                timestamp:JSON.stringify(timestamp),
                                referrer:referrer,
                                total_videos_started:total_videos_started,
                                category:category,
                                action:action,
                                label:label
                            });
                        }
                        function trackPause(player,title,position,length,url,time_on_page,scroll_depth,timestamp,referrer,category,action,label){
                            analytics.track('Video Playback Paused', {
                                video_player: player,
                                video_title:title,
                                video_position:position,
                                video_total_length:length,
                                video_url:url,
                                video_status: "pause",
                                time_on_page:time_on_page,
                                scroll_depth:scroll_depth,
                                timestamp:JSON.stringify(timestamp),
                                referrer:referrer,
                                category:category,
                                action:action,
                                label:label
                            });
                        }
                        function trackSeek(player,title,position,length,url,time_on_page,scroll_depth,timestamp,referrer,category,action,label){
                            analytics.track('Video Playback Seek', {
                                video_player: player,
                                video_title:title,
                                video_position:position,
                                video_total_length:length,
                                video_url:url,
                                video_status: "seek",
                                time_on_page:time_on_page,
                                scroll_depth:scroll_depth,
                                timestamp:JSON.stringify(timestamp),
                                referrer:referrer,
                                category:category,
                                action:action,
                                label:label
                            });
                        }
                        function trackComplete(player,title,position,length,url,time_on_page,scroll_depth,timestamp,referrer,total_videos_completed,category,action,label){
                            analytics.track('Video Playback Completed', {
                                video_player: player,
                                video_title:title,
                                video_position:position,
                                video_total_length:length,
                                video_url:url,
                                video_status:"complete",
                                video_progress:'100%',
                                time_on_page:time_on_page,
                                scroll_depth:scroll_depth,
                                timestamp:JSON.stringify(timestamp),
                                referrer:referrer,
                                total_videos_completed:total_videos_completed,
                                total_videos_progress:1,
                                category:category,
                                action:action,
                                label:label
                            });
                        }
                        function trackProgress(player,title,position,length,url,progress,time_on_page,scroll_depth,timestamp,referrer,total_videos_progress,category,action,label){
                            analytics.track('Video Playback Progress', {
                                video_player: player,
                                video_title:title,
                                video_position:position,
                                video_total_length:length,
                                video_url:url,
                                video_status:"progress",
                                video_progress:progress,
                                time_on_page:time_on_page,
                                scroll_depth:scroll_depth,
                                timestamp:JSON.stringify(timestamp),
                                referrer:referrer,
                                total_videos_progress:total_videos_progress,
                                category:category,
                                action:action,
                                label:label
                            });
                        }
                        //Set parameter
                        function getDocHeight() {
                            var D = document;
                            return Math.max(
                                D.body.scrollHeight, D.documentElement.scrollHeight,
                                D.body.offsetHeight, D.documentElement.offsetHeight,
                                D.body.clientHeight, D.documentElement.clientHeight
                            )
                        }
                        function getmeasurements(){
                            winheight= window.innerHeight || (document.documentElement || document.body).clientHeight
                            docheight = getDocHeight()
                            trackLength = docheight - winheight
                        }

                        window.addEventListener("resize", function(){
                            getmeasurements()
                        }, false)
                    }
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


