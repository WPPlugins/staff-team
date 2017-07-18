<?php
class SContClass {
    protected static $instance = null;
    public $cpt;
    public $mess_cpt;
    public $theme_cpt;
    public $shortcode_tag = 'contact';
    public $version = "1.0.13";
    private function __construct() {
        global $contLDomain;
        $contLDomain = "StaffDirectoryWD";
        $this->includes();
        add_action('wp_enqueue_scripts', array($this, 'contactStyles'));
        add_action('wp_enqueue_scripts', array($this, 'contactScripts'));
        add_action('init', array($this, 'add_localization'));
        add_action('registered_post_type' , array($this , 'send_mes'));

    }
    public function send_mes (){
                if(isset($_POST['is_message'])){
                    $this->send_message();
                }
    }
    public function includes(){
        include_once('includes/SContCPT.php');
        $this->cpt = SContCPT::getInstance();  
        include_once('includes/SCMessClass.php');
        $this->mess_cpt = SCMessClass::getInstance();  
        include_once('includes/SCThemeClass.php');
        $this->theme_cpt = SCThemeClass::getInstance();  
        include_once('includes/SCShortcode.php');  
    }
    public function contactStyles() {
        $theme = 23;
        wp_register_style('spidercontacts_theme', plugins_url('css/themesCSS/sc_theme.css', __FILE__), $this->version, $this->version);
        wp_enqueue_style('spidercontacts_theme');
		wp_enqueue_style('wdwt_font-awesome', plugins_url('/includes/font-awesome/font-awesome.css', __FILE__), $this->version, 'all');

        $twd_custom_css = get_option('twd_custom_css');
        if(isset($twd_custom_css) &&  $twd_custom_css){
            $css = $twd_custom_css;
        }else{
            $css = "";
        }
        wp_add_inline_style('spidercontacts_theme', $css);
    }

    public function contactScripts() {
        global $contLDomain;
        wp_enqueue_script('SC_imagelightbox', plugins_url('js/imagelightbox.min.js', __FILE__), array('jquery'), $this->version);
        wp_enqueue_script('team_contact_common', plugins_url("js/common.js", __FILE__), array('jquery'), $this->version);
        wp_enqueue_script('SC_Script', plugins_url('js/SC_Script.js', __FILE__),  array('jquery'), $this->version);
		wp_enqueue_script('responsive', plugins_url('js/responsive.js', __FILE__),  array('jquery'), $this->version);
        wp_localize_script('SC_Script', 'contLDomain', $this->translationFields($contLDomain));
        wp_localize_script('SC_Script', 'contactAjaxUrl', admin_url('admin-ajax.php'));
       
    }

    public function send_message() {
        if(!isset($_SESSION)){
            @session_start();
        }
        $cont_id = $_POST['contact_id'];

        if($this->mess_cpt->saveMess()){
            $location = 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
            $_SESSION["cont_message_result"][$cont_id] = "success";
            wp_redirect($location);
            exit;
        }
        else{
            $location = 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
            $_SESSION["cont_message_result"][$cont_id] = "error";
            wp_redirect($location);
            exit;
        }
    }
    
    public function translationFields($contLDomain) {
        $scriptLDomain = array();
        $scriptLDomain['mess_text'] = array(
			__("The Name field is required",$contLDomain),
			__("The Message field is required",$contLDomain),
			__("The Title field is Required",$contLDomain), 
			__("Incorrect security code",$contLDomain), 
			__("The Phone field is required",$contLDomain), 
			__("Please provide a valid email address",$contLDomain) 
        );
        $scriptLDomain['paginate'] = array(
			'prev' =>__("",$contLDomain),
			'next' =>__("",$contLDomain)
        );
        $scriptLDomain['more_inf'] = __("More",$contLDomain);
		$scriptLDomain['readmore_inf'] = __("Read More",$contLDomain);
		$scriptLDomain['send_email'] = __("Send Email",$contLDomain);
        $scriptLDomain['category'] = __("Category",$contLDomain);
        $scriptLDomain['email'] = __("Email",$contLDomain);
        
		/*== Table View ==*/
        $scriptLDomain['tabPicture'] = __("Picture",$contLDomain);
        $scriptLDomain['tabName'] = __("Name",$contLDomain);
        $scriptLDomain['tabCateg'] = __("Category",$contLDomain);
        $scriptLDomain['tabEmail'] = __("Email",$contLDomain);
        $scriptLDomain['tabParam'] = __("Parameters",$contLDomain);
		
		return $scriptLDomain;
    }
    
    public function add_localization(){
        global $contLDomain;
        $locale = get_locale(); 
		
		//veragrel inchvor popoxakani, nor dra [0] vercnel
		$staff_exp_plug = explode('/', plugin_basename(__FILE__));

        $path =  WP_CONTENT_DIR . '/uploads/Languages_WD/' . $staff_exp_plug[0].'/'.$staff_exp_plug[0].'-'.$locale.'.mo' ;
        $loaded = load_textdomain($contLDomain, $path);
        if (isset($_GET['page']) && $_GET['page'] == basename(__FILE__) && !$loaded) {
            echo '<div class="error"> Team WD ' . __('Could not load the localization file: ' . $path,$contLDomain ) . '</div>';
            return;
        }
    }
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}