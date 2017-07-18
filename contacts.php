<?php
 /**
 * Plugin Name:  Team WD
 * Plugin URI: https://web-dorado.com/products/wordpress-team-wd.html
 * Description:  Team WD is a WordPress team list plugin with large and affecting capabilities which helps you to display information about the group of people more intelligible, effective and convenient.  Team WD’s main feature is the possibility to create and manage your own list of different contacts with corresponding images, data and with a feedback option.
 * Version: 1.0.13
 * Author:          WebDorado
 * Author URI:      https://web-dorado.com
 * License:  GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

define ('SC_FILE' , __FILE__);
define ('SC_DIR',dirname(__FILE__));
define ('SC_URL',plugins_url(plugin_basename(dirname(__FILE__))));
require_once('SContClass.php');
add_action( 'plugins_loaded', array( 'SContClass', 'getInstance' ) );
include_once dirname( __FILE__ ) . '/includes/SCDemo.php';
register_activation_hook( __FILE__, array( 'SCDemo', 'installDemoData' ) );
if(is_admin()){
	require_once('SContAdminClass.php');
	$cont_admin = SContAdminClass::getInstance();
}

register_activation_hook( __FILE__,  array( 'SContAdminClass', 'contActivate' ));
//team-staff
add_action('init', 'twd_wd_lib_init');


function twd_wd_lib_init() {
  if (!isset($_REQUEST['ajax'])) {
    if (!class_exists("DoradoWeb")) {
      require_once(SC_DIR . '/wd/start.php');
    }
    global $twd_options;
    $twd_options = array(
      "prefix" => "twd",
      "wd_plugin_id" => 153,
      "plugin_title" => "Team WD",
      "plugin_wordpress_slug" => "staff-team",
      "plugin_dir" => SC_DIR,
      "plugin_main_file" => __FILE__,
      "description" => __("A perfect solution to display the members of your staff, team or employees on your WordPress website. Show details about team members, including bio, featured image, nationality, occupation and more.", 'twd'),
      // from web-dorado.com
      "plugin_features" => array(
        0 => array(
          "title" => __("Easy Configuration", "twd"),
          "description" => __("Install and activate Team WD plugin and you can easily add your team/staff members from the admin area. Add each team member separately, including name, photo, bio, position/occupation, contact email, and other information.", "twd"),
        ),
        1 => array(
          "title" => __("Team Ordering", "twd"),
          "description" => __("You can order team members to show in the list by ID or by name. You can also set the order that the members appear on the page using the simple drag & drop ordering.", "twd"),
        ),
        2 => array(
          "title" => __("Display Options", "twd"),
          "description" => __("The Team WD plugin allows you to display team/staff member photos and information in 8 different layouts - Short, Full, Chess, Portfolio, Blog, Circle, Square and Table. Simply choose the view type that better fits your website when adding the team list to the page.", "twd"),
        ),
        3 => array(
          "title" => __("Styles and Colors/Themes", "twd"),
          "description" => __("The WordPress team plugin comes with 5 different built-in styles and colors – Default, Dark, Blue, Green and Violet.", "twd"),
        ),
        4 => array(
          "title" => __("Lightbox", "twd"),
          "description" => __("The WordPress team plugin comes with Lightbox integration. You can choose to activate the Lightbox feature to showcase your staff images in a more attractive pop up view.", "twd"),
        )
      ),
      // user guide from web-dorado.com
      "user_guide" => array(
        0 => array(
          "main_title" => __("Adding a Category", "twd"),
          "url" => "https://web-dorado.com/wordpress-team-wd/adding-category.html",
          "titles" => array()
        ),
        1 => array(
          "main_title" => __("Adding a Contact", "twd"),
          "url" => "https://web-dorado.com/wordpress-team-wd/adding-contact.html",
          "titles" => array()
        ),
        2 => array(
          "main_title" => __("Team Ordering", "twd"),
          "url" => "https://web-dorado.com/wordpress-team-wd/team-ordering.html",
          "titles" => array()
        ),
        3 => array(
          "main_title" => __("Messages", "twd"),
          "url" => "https://web-dorado.com/wordpress-team-wd/messages.html",
          "titles" => array()
        ),
        4 => array(
          "main_title" => __("Options", "twd"),
          "url" => "https://web-dorado.com/wordpress-team-wd/options.html",
          "titles" => array()
        ),
        5 => array(
          "main_title" => __("Styles and Colors", "twd"),
          "url" => "https://web-dorado.com/wordpress-team-wd/styles-and-colors.html",
          "titles" => array()
        ),
        6 => array(
          "main_title" => __("Inserting Team WD in a Page or Post", "twd"),
          "url" => "https://web-dorado.com/wordpress-team-wd/inserting-team-wd.html",
          "titles" => array()
        ),
      ),
      'overview_welcome_image' => SC_URL . '/images/welcome_image.png',
      "video_youtube_id" => null,
      // e.g. https://www.youtube.com/watch?v=acaexefeP7o youtube id is the acaexefeP7o
      "plugin_wd_url" => "https://web-dorado.com/products/wordpress-team-wd.html",
      "plugin_wd_demo_link" => "http://wpdemo.web-dorado.com/team-wd/?_ga=1.146655351.212018776.1470817467",
      //"plugin_wd_forum_link" => "https://web-dorado.com/forum/team-wd.html",
      //"plugin_wd_addons_link" => "https://web-dorado.com/products/wordpress-google-maps-plugin/add-ons/marker-clustering.html",
      "after_subscribe" => "edit.php?post_type=contact&page=overview_twd",
      // this can be plagin overview page or set up page
      "plugin_wizard_link" => null,
      "plugin_menu_title" => "Team WD",
      //null
      "plugin_menu_icon" => SC_URL . '/images/Staff_Directory_WD_menu.png',
      //null
      "deactivate" => true,
      "subscribe" => true,
      "custom_post" => 'edit.php?post_type=contact',
      //"custom_post" => false,
      // if true => edit.php?post_type=contact
    );
    dorado_web_init($twd_options);
  }
}
?>