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
                var timer;
                var timerStart;
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

                            let user_type="", student_type="",employee_type="",is_donor=false,is_alumni=false, 
                            first_name="", last_name="",birth_day="", birth_month="",birth_year="", age="", phone="", email="",
                            student_country_origin="",student_state_origin="",address_postcode="",address_state="",
                            start_year="",enrolement_type="", interested_in_college=[],career_interests=[],
                            parent_first_name="", parent_last_name="",parent_email="",parent_relationship_to_student="";

                            let labels = Array.prototype.slice.call(form.querySelectorAll('.gfield_label'),0);
                            labels.forEach((label)=>{
                                let labelContent=label.textContent.charAt(label.textContent.length-1)==="*"?label.textContent.substring(0,label.textContent.length-1):label.textContent;
                                let sibling=label.nextElementSibling
                                if(labelContent==="User Type"){
                                    user_type=sibling.querySelector('select').value
                                }else if(labelContent==="Student Type"&&user_type==="Student"){
                                    student_type=sibling.querySelector('select').value
                                }else if(labelContent==="Employee Type"&&user_type==="Employee"){
                                    employee_type=sibling.querySelector('select').value
                                }else if(labelContent==="Are you a donor?"){
                                    is_donor=sibling.querySelector('select').value==="Yes"?true:false
                                }else if(labelContent==="Are you an alumini?"){
                                    is_alumni=sibling.querySelector('select').value==="Yes"?true:false
                                }else if(labelContent==="Student Name"){
                                    first_name=sibling.querySelector('.name_first>input').value
                                    last_name=sibling.querySelector('.name_last>input').value
                                }else if(labelContent==="Birthdate"){
                                    birth_month=sibling.querySelector('.gfield_date_month>input').value
                                    birth_day=sibling.querySelector('.gfield_date_day>input').value
                                    birth_year=sibling.querySelector('.gfield_date_year>input').value
                                    let birthday=birth_year+"-"+birth_month+"-"+birth_day
                                    console.log(Date.parse(birthday))
                                    age=Math.round((Date.now()-Date.parse(birthday))/31536000000)
                                }else if(labelContent==="Phone"){
                                    phone=sibling.querySelector('input').value
                                }else if(labelContent==="Student's Email"){
                                    email=sibling.querySelector('input').value
                                }else if(labelContent==="Address"){
                                    student_country_origin=sibling.querySelector('.address_country>select').value
                                    student_state_origin=sibling.querySelector('.address_state>input').value
                                    address_postcode=sibling.querySelector('.address_zip>input').value
                                    address_state=student_state_origin
                                }else if(labelContent==="Start Year"){
                                    start_year=sibling.querySelector('select').value
                                }else if(labelContent==="Enrollment Type"){
                                    enrolement_type=sibling.querySelector('select').value
                                }else if(labelContent==="Purdue disciplinary college in which you are interested"){
                                    interested_in_college=[...sibling.querySelector('select').options].filter(option => option.selected)
                                                        .map(option => option.value)
                                }else if(labelContent==="Career Interests"){
                                    career_interests=[...sibling.querySelector('select').options].filter(option => option.selected)
                                                        .map(option => option.value)
                                }else if(labelContent==="Parent Name"){
                                    parent_first_name=sibling.querySelector('.name_first>input').value
                                    parent_last_name=sibling.querySelector('.name_last>input').value
                                }else if(labelContent==="Parent's Email"){
                                    parent_email=sibling.querySelector('input').value
                                }else if(labelContent==="Parent Relationship to Student"){
                                    parent_relationship_to_student=sibling.querySelector('input').value
                                }
                            })
                            let traits = {
                                user_type : user_type,
                                student_type: student_type,
                                employee_type: employee_type,
                                is_donor : is_donor,
                                is_alumni : is_alumni,
                                first_name : first_name,
                                last_name : last_name,
                                birth_day : birth_day,
                                birth_month : birth_month,
                                birth_year : birth_year,
                                age : age,
                                phone : phone,
                                email : email,
                                student_country_origin : student_country_origin,
                                student_state_origin : student_state_origin,
                                address_postcode : address_postcode,
                                address_state : address_state,
                                start_year : start_year,
                                enrolement_type : enrolement_type,
                                interested_in_college : interested_in_college,
                                career_interests : career_interests,
                                parent_first_name : parent_first_name,
                                parent_last_name : parent_last_name,
                                parent_email : parent_email,
                                parent_relationship_to_student : parent_relationship_to_student
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
                        let session_search=sessionStorage.getItem('total_searches');
                        !session_search?sessionStorage.setItem('total_searches', '0'):'';   
                        //G-forms
                        const gFormWrappers = Array.prototype.slice.call(document.querySelectorAll('.gform_wrapper'), 0);
                        if(gFormWrappers&&gFormWrappers.length>0){
                            gFormWrappers.forEach((wrapper)=>{
                                let form=wrapper.querySelector('form')
                                form.addEventListener("submit",this.formSubmitted);
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
                                let scrollDepth=link.getBoundingClientRect().top>=windowHeight?link.getBoundingClientRect().top-windowHeight:0;
                                link.addEventListener('click',function(){
                                    event.preventDefault();
                                    timer=Math.floor((Date.now()-timerStart)/1000);

                                    if(ext&&ext!=="edu"&&ext!=="edu/"&&ext!=="com"&&ext!=="com/"&&ext!=="org"&&ext!=="org/"&&ext!=="net"&&ext!=="net/"&&ext!=="php"&&ext!=="php"&&ext!=="html"&&ext!=="aspx"){
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
                                        link.classList.contains('cta-button')||
                                        link.parentElement.parentElement.parentElement.classList.contains('navbar-end')){
                                        analytics.track('CTA Link Clicked', {
                                            text: link.innerText,
                                            destination_href:link.href,
                                            time_on_page:timer,
                                            scroll_depth:scrollDepth
                                        });
                                    }
                                    //Search Results Page
                                    if(h1Text.substring(0,h1Text.indexOf(' '))==="Search"&&h1.nextElementSibling.classList.contains('search-box')){
                                        if(link.parentElement.classList.contains("search-post-title")||link.classList.contains("gs-title")){
                                            let pageN=1;
                                            if(document.querySelector('.gsc-cursor-current-page')){
                                                pageN=document.querySelector('.gsc-cursor-current-page').innerHTML;
                                            }else if(document.querySelector('.pagination>.nav-links>.current')){
                                                pageN=document.querySelector('.pagination>.nav-links>.current').innerHTML;
                                            }
                                            function getParameterByName(name, url = window.location.href) {
                                                name = name.replace(/[\[\]]/g, '\\$&');
                                                var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                                                    results = regex.exec(url);
                                                if (!results) return null;
                                                if (!results[2]) return '';
                                                return decodeURIComponent(results[2].replace(/\+/g, ' '));
                                            }
                                            analytics.track('Search Results Page', {
                                                text: link.innerText,
                                                query: getParameterByName('s')||getParameterByName('q'),
                                                page_number:pageN,
                                                time_on_page:timer,
                                                scroll_depth:scrollDepth
                                            });
                                        }
                                    }
                                    setTimeout(function(){ 
                                        window.open(link.href, link.target&&link.target==="_blank"?"_blank":"_self")
                                    }, 300);
                                })
                            })
                        }
                        //404 page 
                        if(h1Text==="Page Not Found"){
                            analytics.track('404 Page Viewed', {
                                page_href: window.location.href,
                                referrer: document.referrer
                            });
                        }
                        //Search performed
                        const searchForms=Array.prototype.slice.call(document.getElementsByName('searchform'),0);
                        searchForms.forEach((form)=>{
                            form.addEventListener('submit',function(event){
                                event.preventDefault();
                                timer=Math.floor((Date.now()-timerStart)/1000);
                                let phrase=event.target.querySelector('.search-field').value || null;
                                let searches = sessionStorage.getItem('total_searches');
                                searches = parseInt(searches)+1;
                                sessionStorage.setItem('total_searches', searches);
                                analytics.track("Site Search Performed", {
                                    query:phrase,
                                    total_searches:searches,
                                    time_on_page:timer
                                })
                                setTimeout(function(){ 
                                    form.submit()
                                }, 300);  
                            })
                        })

                        //Embeded videos
                        var youtubePlayers=[];
                        var vimeoPlayers=[];
                        
                        window.onload=function(){

                            timer=0;
                            timerStart=Date.now();                      
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
                                    if(YT&&YT.loaded){
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
                                    if(Vimeo){
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
                                                    player.on('play', function(event) {
                                                        trackPlay("Vimeo",title,Math.round(event.seconds),duration,url);
                                                    });
                                                    player.on('pause', function(event) {
                                                        if(Math.round(event.seconds)!==duration){
                                                            trackPause("Vimeo",title,Math.round(event.seconds),duration,url);
                                                        }
                                                       
                                                    });
                                                    player.on('ended', function(event) {
                                                        trackComplete("Vimeo",title,Math.round(event.seconds),duration,url);
                                                    });
                                                  
                                                    var lastTime=0;
                                                    var currentTime=0;
                                                    var seekStart = null;
                                                    player.on('seeking', function(event) {
                                                        if(seekStart === null){
                                                            seekStart=lastTime
                                                            trackSeek("Vimeo",title,Math.round(event.seconds),duration,url);   
                                                        }

                                                    });
                                                    player.on('seeked', function(event) {
                                                        seekStart = null;
                                                    });
                                                    player.on("timeupdate", function(event){
                                    
                                                        let percent_new=Math.round(event.seconds/duration*100);
                                                        if(percent_new!==percent){
                                                            percent=percent_new;
                                                            if(percent===25||percent===50||percent===75||percent===90){
                                                                percentage=percent+'%'
                                                                trackProgress("Vimeo",title,Math.round(event.seconds),duration,url,percentage)
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
                                    if(DM){
                                        dmotion.forEach((iframe)=>{ 
                                            const url=iframe.src;
                                            const videoID=url.substring(url.lastIndexOf('/')+1)
                                            var duration;
                                            var title;
                                            var percent=0;
                                            DM.api(
                                                `/video/${videoID}`,
                                                { fields: ['duration', 'title' ]},
                                                result => {
                                                    // result is an Object with all the fields wanted
                                                    duration=result.duration;
                                                    title=result.title;
                                                }
                                            )
                                            var player =DM.player(iframe,{
                                                video: videoID
                                            });

                                            player.addEventListener('play', function(event){
                                                trackPlay("Daily Motion",title,Math.round(event.target.currentTime),duration,url);
                                                
                                            })
                                            player.addEventListener('pause', function(event){
                                                trackPause("Daily Motion",title,Math.round(event.target.currentTime),duration,url);
                                                
                                            })
                                            player.addEventListener('seeking', function(){
                                                trackSeek("Daily Motion",title,Math.round(event.target.currentTime),duration,url);
                                            })
                                            player.addEventListener('end', function(){
                                                trackComplete("Daily Motion",title,Math.round(event.target.currentTime),duration,url);
                                            })
                                            player.addEventListener('timeupdate', function(){
                                                let percent_new=Math.round(event.target.currentTime/duration*100);
                                                if(percent_new!==percent){
                                                    percent=percent_new;
                                                    if(percent===25||percent===50||percent===75||percent===90){
                                                        percentage=percent+'%'
                                                        trackProgress("Daily Motion",title,Math.round(event.target.currentTime),duration,url,percentage)
                                                    }
                                                }
                                            }) 

                                        })                                          
                                        clearInterval(checkDM);
                                    }
                                }, 100);
                                checkDM;
                       
                            }
                        }
                       
                        window.onPlayerReady=function(event) {
                            console.log(event.target)
                            console.log("youtube player")
                            var lastTime = -1;
                            var lastState=-1;
                            var interval = 1000;
                            var percent = 0;
                            const duration=event.target.getDuration();
                            const title=event.target.getVideoData().title;
                            const url=event.target.getVideoUrl();

                            var checkPlayerTime = function () {
                                if (lastTime !== -1) {
                              
                                    if(event.target.getPlayerState() === 1||(event.target.getPlayerState() === 2&&lastState===2)) {
                                        if (Math.abs(event.target.getCurrentTime() - lastTime - 1) > 1) {
                                            trackSeek("YouTube",title,Math.round(event.target.getCurrentTime()),Math.round(duration),url)
                                        }
                                    }

                                    let percent_new=Math.round(event.target.getCurrentTime()/duration*100);
                                    if(percent_new!==percent){
                                        percent=percent_new;
                                        if(percent===25||percent===50||percent===75||percent===90){
                                            percentage=percent+'%'
                                            trackProgress("YouTube",title,Math.round(event.target.getCurrentTime()),Math.round(duration),url,percentage)
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
                            console.log('state change')
                            const duration=event.target.getDuration();
                            const title=event.target.getVideoData().title;
                            const url=event.target.getVideoUrl();
                            switch(event.data) {
                                case 0:
                                    trackComplete("YouTube",title,Math.round(event.target.getCurrentTime()),Math.round(duration),url);
                                    break;
                                case 1:
                                    trackPlay("YouTube",title,Math.round(event.target.getCurrentTime()),Math.round(duration),url);
                                    break;
                                case 2:
                                    if(Math.round(event.target.getCurrentTime())!==duration){
                                        trackPause("YouTube",title,Math.round(event.target.getCurrentTime()),Math.round(duration),url);
                                    }
                                    break;

                            }
                        }

                        function trackPlay(player,title,position,length,url){
                            analytics.track('Video Playback Started', {
                                video_player: player,
                                video_title:title,
                                video_position:position,
                                video_total_length:length,
                                video_url:url
                            });
                        }
                        function trackPause(player,title,position,length,url){
                            analytics.track('Video Playback Paused', {
                                video_player: player,
                                video_title:title,
                                video_position:position,
                                video_total_length:length,
                                video_url:url
                            });
                        }
                        function trackSeek(player,title,position,length,url){
                            analytics.track('Video Playback Seek', {
                                video_player: player,
                                video_title:title,
                                video_position:position,
                                video_total_length:length,
                                video_url:url
                            });
                        }
                        function trackComplete(player,title,position,length,url){
                            analytics.track('Video Playback Completed', {
                                video_player: player,
                                video_title:title,
                                video_position:position,
                                video_total_length:length,
                                video_url:url,
                                video_progress:'100%'
                            });
                        }
                        function trackProgress(player,title,position,length,url,progress){
                            analytics.track('Video Playback Progress', {
                                video_player: player,
                                video_title:title,
                                video_position:position,
                                video_total_length:length,
                                video_url:url,
                                video_progress:progress
                            });
                        }
                        //Uploaded videos
                        const videos=Array.prototype.slice.call(document.querySelectorAll('.wp-block-video video'));
                        if(videos&&videos.length>0){
                            videos.forEach((video)=>{
                                var duration;
                                var percent=0;
                                const title=video.nextElementSibling.innerHTML?video.nextElementSibling.innerHTML:'';
                                const url=video.src;
                                const ext=url.substring(url.lastIndexOf("/")+1).split('.').pop();
                                video.addEventListener("play", (event)=>{
                                    duration=video.duration;
                                    trackPlay(ext,title,Math.round(event.target.currentTime),Math.round(duration),url);
                                })
                                video.addEventListener("pause", (event)=>{
                                    if(video.currentTime!==duration&&video.currentTime!==0){
                                        trackPause(ext,title,Math.round(event.target.currentTime),Math.round(duration),url);
                                    }
                                })
                                var lastTime=0;
                                var currentTime=0;
                                var seekStart = null;
                                video.addEventListener("seeking", (event)=>{  
                                    
                                    if(seekStart === null){
                                        seekStart=lastTime
                                        trackSeek(ext,title,Math.round(seekStart),Math.round(duration),url);   
                                    }
            
                                })
                                video.addEventListener("seeked", (event)=>{  
                                    seekStart = null;
                                })
                                video.addEventListener("ended", (event)=>{
                                    trackComplete(ext,title,Math.round(event.target.currentTime),Math.round(duration),url);
                                })
                                video.addEventListener("timeupdate", (event)=>{
                                    
                                    let percent_new=Math.round(video.currentTime/duration*100);
                                    if(percent_new!==percent){
                                        percent=percent_new;
                                        if(percent===25||percent===50||percent===75||percent===90){
                                            percentage=percent+'%'
                                            trackProgress(ext,title,Math.round(event.target.currentTime),Math.round(duration),url,percentage)
                                        }
                                    }
                                    lastTime = currentTime;
                                    currentTime = event.target.currentTime;
                                })
                            })
                        }
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


