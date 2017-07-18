<?php
class SContAdminClass {

    protected static $instance = null;
    public $shortcode_tag = 'contact';
    public $version = "1.0.13";
    private function __construct() {
        $this->uninstall_redirect();
        add_action('admin_menu', array($this, 'SContSubmenu'),9);
        add_action('admin_init', array($this,'setup_redirect'));
        add_action('admin_init', array($this, 'registerContOptions'));
        add_action('admin_enqueue_scripts', array($this, 'includeAdminStyle'));
        add_action('admin_enqueue_scripts', array($this, 'includeAdminScripts'));
        add_action('admin_head', array($this, 'insert_contacts'));
        add_action('admin_head', array($this, 'insert_cont_cats'));
        add_action('admin_head', array($this, 'plugin_url'));
        add_action('init', array($this, 'admin_head'));

        add_action('admin_notices', array($this, 'create_pro_logo_to_head'));
        require_once 'lang/SLangClass.php';
        $plugin_folder_name = explode('/' , plugin_basename( __FILE__ ));
        new StaffDirLangClass('contact', $plugin_folder_name[0]);
        add_action('admin_menu', array($this, 'SCuninstallPlugin'),11);
        add_action('wp_ajax_staff_order_contact', array($this, 'staff_order_contact'));
        add_action('wp_ajax_delete_demo_data', array($this, 'delete_demo_data'));

    }

    public function SContSubmenu() {
        add_submenu_page('edit.php?post_type=contact', 'Team Ordering', 'Team Ordering', 'manage_options', 'ordering_staff', array($this , 'ordering_staff'));
        add_submenu_page('edit.php?post_type=contact', 'Messages', 'Messages', 'manage_options', 'edit.php?post_type=cont_mess');
        add_submenu_page('edit.php?post_type=contact', 'Styles and Colors', 'Styles and Colors', 'manage_options', 'styles_colors' , array($this , 'styles_colors'));
        add_submenu_page('edit.php?post_type=contact', 'Global Options', 'Options', 'manage_options', 'cont_option', array($this, 'displayGlobalOptions'));
    }
    public function SCuninstallPlugin(){
        add_submenu_page('edit.php?post_type=contact', 'Uninstall', 'Uninstall', 'manage_options', 'uninstall_plugin', array($this,'uninstallPlugin'));
    }
    public function includeAdminStyle() {
        $screen = get_current_screen();
        if($screen->post_type=="contact" || $screen->post_type =="cont_mess" || $screen->post_type =="cont_theme" || $screen->post_type == "page" ||  $screen->post_type=="post") {
            wp_register_style('contAdminStyle', plugins_url('css/admin.css', __FILE__), 1, $this->version);
            wp_register_style('jQueryDialog', plugins_url('css/jquery-ui-dialog.css', __FILE__), 1, 'all');
            wp_enqueue_style('contAdminStyle');
            wp_enqueue_style('jQueryDialog');
            wp_register_style('cont-evol-colorpicker-min', plugins_url('css/evol.colorpicker.css', __FILE__), 1, 'all');
            wp_enqueue_style('cont-evol-colorpicker-min');
            wp_enqueue_style('cont-admin-datetimepicker-css', plugins_url('css/jquery.datetimepicker.css', __FILE__), array(), 1, 'all');
            $get_current_screen = get_current_screen();
            if ($get_current_screen->base == "contact_page_uninstall_plugin") {
                wp_enqueue_style('twd_deactivate-css', SC_URL . '/wd/assets/css/deactivate_popup.css', array(), $this->version);
            }
            /*Upload*/
            wp_enqueue_style('thickbox');
        }
    }

    public function ordering_staff(){
        include_once('views/admin/SCViewOrderingStuff.php' );
        global $wpdb;
        $contactList = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE post_type='contact' AND post_status='publish'");
        drawOrderingTable($contactList);

    }
    public function staff_order_contact(){
        $staff_order_contact = get_option('staff_order_contact');
        update_option('staff_order_contact' , $_POST['data']);


    }
    public function create_pro_logo_to_head(){
        global $pagenow, $post;
        if ($this->staff_page()) { ?>
            <div class="TWDUpgradeProVersion">
                <a href="https://web-dorado.com/products/wordpress-team-wd.html" target="_blank">
                    <img src="<?php echo plugins_url('/images/pro.png', __FILE__); ?>" border="0" alt="https://web-dorado.com/">
                </a>
            </div>
            <?php
        }
    }
    private function staff_page(){
        $screen = get_current_screen();
        if ($screen->id == 'contact_page_styles_colors' || $screen->id == 'edit-contact' || $screen->id == 'contact' || $screen->id == 'edit-cont_category' || $screen->id == 'contact_page_ordering_staff' || $screen->id=='edit-cont_mess' || $screen->id == 'contact_page_styles_colors' || $screen->id == 'contact_page_cont_option' || $screen->id == 'contact_page_cont_featured_plugins' || $screen->id == 'contact_page_cont_featured_themes' || $screen->id == 'contact_page_contact_lang_option' || $screen->id == 'contact_page_uninstall_plugin') {
            return true;
        } else {
            return false;
        }
    }

    public function styles_colors(){
        echo"
            <h4 style='color: #cc0000 ;'>Styles and Colors options are disabled in free version. If you need this functionality, you need to buy the <a href='https://web-dorado.com/products/wordpress-team-wd.html'>commercial version.</a></h4>
            <img class='free_style_colors' src='".SC_URL."/images/styles_colors.png'>
            ";
    }

    public function includeAdminScripts() {
        $screen = get_current_screen();

        if($screen->post_type=="contact" || $screen->post_type =="cont_mess" || $screen->post_type =="cont_theme" || $screen->post_type == "page" ||  $screen->post_type=="post") {
            wp_register_script('SCAdminScript', plugins_url('js/admin/admin.js', __FILE__), array(
              'jquery',
              'jquery-ui-widget',
              'jquery-ui-tabs',
              'jquery-ui-dialog',
              'media-upload',
              'thickbox'
            ), $this->version, true);
            wp_localize_script('SCAdminScript', 'staff_ajaxurl', admin_url('admin-ajax.php'));
            wp_register_script('colorPicker-min', plugins_url('js/admin/evol.colorpicker.js', __FILE__), array(
              'jquery',
              'jquery-ui-widget'
            ), 1, true);
            wp_enqueue_script('colorPicker-min');
            wp_register_script('datetimepicker', plugins_url('js/admin/jquery.datetimepicker.js', __FILE__), array(
              'jquery',
              'jquery-ui-widget'
            ), 1, true);
            wp_enqueue_script('datetimepicker');
            wp_enqueue_script('SCAdminScript');
            wp_enqueue_media();
            /*Upload*/
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
            //wp_register_script('my-upload', plugins_url('js/admin/admin.js', __FILE__), array('jquery',));
            wp_enqueue_script('my-upload');
            wp_register_script('contact_order', plugins_url('js/admin/contact_order.js', __FILE__), array(
              'jquery',
              'jquery-ui-widget',
              'jquery-ui-tabs',
              'jquery-ui-dialog'
            ), 1, true);
            wp_enqueue_script('contact_order');
            $get_current_screen = get_current_screen();
            if ($get_current_screen->base == "contact_page_uninstall_plugin") {
                wp_enqueue_script('twd-deactivate-popup', SC_URL . '/wd/assets/js/deactivate_popup.js', array(), $this->version, true);
                $admin_data = wp_get_current_user();
                wp_localize_script('twd-deactivate-popup', 'twdWDDeactivateVars', array(
                  "prefix" => "twd",
                  "deactivate_class" => 'twd_deactivate_link',
                  "email" => $admin_data->data->user_email,
                  "plugin_wd_url" => "https://web-dorado.com/products/wordpress-team-wd-plugin.html",
                ));
            }
        }
    }

    public function plugin_url() { ?>
        <script>
            var sc_plugin_url = '<?php echo SC_URL; ?>';
        </script>
    <?php
    }

    public function admin_head() {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }
        if ('true' == get_user_option('rich_editing')) {
            add_filter('mce_external_plugins', array($this, 'mce_external_plugins'));
            add_filter('mce_buttons', array($this, 'mce_buttons'));
        }
    }

    public function mce_external_plugins($plugin_array) {
        $screen = get_current_screen();
        if ($screen->post_type != 'contact') {
            $plugin_array[$this->shortcode_tag] = plugins_url('js/mce-cont-button.js', __FILE__);
        }
        return $plugin_array;
    }

    public function mce_buttons($buttons) {
        array_push($buttons, $this->shortcode_tag);
        return $buttons;
    }

    public function insert_contacts() {
        $contacts = get_posts(array('post_type' => 'contact', 'numberposts' => -1,));  ?>
        <script>
            var contacts = [];
            contacts[0] =
			{
				text: "<?php echo 'Select a Contact'; ?>",
				value: 0
			};
        </script>
        <?php
        $i = 0;
        foreach ($contacts as $cont) {
            ?>
            <script>
                contacts[<?php echo ++$i; ?>] =
				{
					text: "<?php echo $cont->post_title; ?>",
					value: '<?php echo $cont->ID; ?>'
				};
            </script>
        <?php
        }
    }

    public function insert_cont_cats() {
        $args = array(
            'orderby' => 'id',
            'order' => 'ASC',
            'hide_empty' => TRUE,
            'exclude' => array(),
            'exclude_tree' => array(),
            'include' => array(),
            'number' => '',
            'fields' => 'all',
            'slug' => '',
            'parent' => '',
            'hierarchical' => true,
            'child_of' => 0,
            'get' => '',
            'name__like' => '',
            'pad_counts' => false,
            'offset' => '',
            'search' => '',
            'cache_domain' => 'core'
        );

        $cats = get_terms('cont_category', $args);  ?>
        <script>
            var contCats = [];
        </script>
        <?php
        $i = 0;
        foreach ($cats as $cat) { ?>
            <script>
                contCats[<?php echo $i++; ?>] =
				{
					text: '<?php echo $cat->name; ?>',
					value: '<?php echo $cat->term_id; ?>'
				};
            </script>
        <?php
        }
    }

    public function registerContOptions() {
        register_setting('cont_option', 'choose_category');
        register_setting('cont_option', 'name_search');
        register_setting('cont_option', 'lightbox');
        register_setting('cont_option', 'team_slug');
        register_setting('mess_option', 'enable_message');
        register_setting('mess_option', 'show_name');
        register_setting('mess_option', 'show_email');
        register_setting('mess_option', 'show_phone');
        register_setting('mess_option', 'show_cont_pref');

        register_setting('custom_css', 'twd_custom_css');
    }

    public function displayGlobalOptions() {
        if(isset($_GET['tab']) && $_GET['tab']=='mess_option'){
            include('views/admin/SCViewMessageOptions.php');
        }elseif (isset($_GET['tab']) && $_GET['tab']=='custom_css'){
            include('views/admin/SCViewCustomCss.php');
        }else{
            include('views/admin/SCViewGlobalOptions.php');
        }
    }
    public function uninstall_redirect (){
        if(isset($_GET['deactivate_plugin'])){
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            deactivate_plugins(plugin_basename(SC_FILE));
            header('Location:'.home_url().'/wp-admin/plugins.php');
        }
    }
    public function uninstallPlugin(){
        include_once('views/admin/SCViewUninstall_sc.php');
        global  $twd_options;
        if(!class_exists("DoradoWebConfig")){
            include_once (SC_DIR . "/wd/config.php");
        }
        if(!class_exists("DoradoWebDeactivate")) {
            include_once (SC_DIR . "/wd/includes/deactivate.php");
        }
        $config = new DoradoWebConfig();

        $config->set_options( $twd_options );

        $deactivate_reasons = new DoradoWebDeactivate($config);
        //$deactivate_reasons->add_deactivation_feedback_dialog_box();
        $deactivate_reasons->submit_and_deactivate();
        if(isset($_POST["sc_uninstall"])){
            include_once('includes/SCUninistall.php');
            sc_uninstall_plugin();
            sc_uninstall_success();
        }else{
            sc_uninstall();
        }

    }


    
    public static function contActivate() {
        update_option('name_search',1);
        update_option('lightbox',1);
        update_option('enable_message',1);
        update_option('show_name',1);
        update_option('show_email',1);
        update_option('show_phone',1);
        update_option('show_cont_pref',1);
        $team_slug = get_option("team_slug");
        if(!$team_slug){
            update_option('team_slug','person');
        }
        $activation_post = array();
        add_option('sc_uninstall_plugin' , $activation_post);
        add_option('twd_do_activation_set_up_redirect', 1);
    }
    public function delete_demo_data(){
        if(isset($_POST["delete"]) && $_POST["delete"]=='true'){
            $delete_demo_data = get_option('delete_demo_data');
            foreach($delete_demo_data as $demo_data){
                $_thumbnail_id = get_post_meta($demo_data , '_thumbnail_id');
                wp_delete_post($_thumbnail_id[0] , true);
                delete_post_meta($demo_data , 'email');
                delete_post_meta($demo_data , 'want_email');
                wp_delete_post($demo_data , true);
            }
            $delete_demo_category = get_option('delete_demo_category');
            wp_delete_term($delete_demo_category["term_id"], 'cont_category');

            delete_option('delete_demo_data');
            delete_option('delete_demo_category');
        }
    }

    public function setup_redirect() {
        if (get_option('twd_do_activation_set_up_redirect')) {
            update_option('twd_do_activation_set_up_redirect',0);
            //wp_safe_redirect( admin_url( 'index.php?page=gmwd_setup' ) );
            wp_safe_redirect( admin_url( 'admin.php?page=twd_subscribe' ) );
            exit;
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

}
