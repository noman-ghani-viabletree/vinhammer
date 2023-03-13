<?php

/*
 * Plugin Name: wpDiscuz - Comment Author Info
 * Description: Displays comment author profile information, activity, voted comments and subscriptions
 * Version: 7.0.11
 * Author: gVectors Team
 * Author URI: https://gvectors.com/
 * Plugin URI: https://gvectors.com/product/wpdiscuz-comment-author-info/
 * Text Domain: wpdiscuz-comment-author-info
 * Domain Path: /languages/
 */
if (!defined("ABSPATH")) {
    exit();
}

define("WCAI_DIR_PATH", dirname(__FILE__));
define("WCAI_DIR_NAME", basename(WCAI_DIR_PATH));
define("WCAI_BASENAME_FILE", basename(__FILE__));

include_once WCAI_DIR_PATH . "/includes/gvt-api-manager.php";
include_once WCAI_DIR_PATH . "/includes/wcai-constants.php";
include_once WCAI_DIR_PATH . "/includes/wcai-dbmanager.php";
include_once WCAI_DIR_PATH . "/includes/wcai-helper.php";
include_once WCAI_DIR_PATH . "/options/wcai-options.php";

class WpdiscuzCommentAuthorInfo implements WCAIConstants {

    private $version;
    private $dbManager;
    public $helper;
    private $options;
    public static $IS_LOGGED_IN;
	public $apimanager;

    public function __construct() {
        add_action("admin_notices", [$this, "requirements"], 1);
        add_action("plugins_loaded", [$this, "dependencies"], 1);
    }

    public function requirements() {
        if (current_user_can("manage_options")) {
            if (!function_exists("wpDiscuz")) {
                echo "<div class='error'><p>" . __("wpDiscuz - Comment Author Info requires wpDiscuz to be installed!", "wpdiscuz-comment-author-info") . "</p></div>";
            }

            if (!function_exists("ob_start") || !function_exists("ob_get_contents") || !function_exists("ob_end_clean")) {
                echo "<div class='error'><p>" . __("wpDiscuz - Comment Author Info requires object buffering to be enabled!", "wpdiscuz-comment-author-info") . "</p></div>";
            }
        }
    }

    public function dependencies() {
        if (function_exists("wpDiscuz")) {
	        $this->apimanager = new GVT_API_Manager(__FILE__, "wpdiscuz_options_page", "wpdiscuz_option_page");
            self::$IS_LOGGED_IN = is_user_logged_in();
            $this->dbManager = new WCAIDBManager();
            $this->options = new WCAIOptions($this->dbManager);
            $this->helper = new WCAIHelper($this->options, $this->dbManager);

            $this->version = get_option(self::OPTION_VERSION);
            if (!$this->version) {
                $this->version = "1.0.0";
                update_option(self::OPTION_VERSION, $this->version);
            }
            load_plugin_textdomain("wpdiscuz-comment-author-info", false, WCAI_DIR_NAME . "/languages/");

            add_action("wpdiscuz_front_scripts", [&$this, "frontendFiles"]);
            add_action("wpdiscuz_check_version", [&$this, "version"]);

            add_action("wpdiscuz_save_options", [&$this->options, "saveOptions"]);
            add_action("wpdiscuz_reset_options", [&$this->options, "resetOptions"]);
            add_filter("wpdiscuz_settings", [&$this->options, "settingsArray"], 20);
            add_filter("wpdiscuz_before_comment_link", [&$this->helper, "addInfoIcon"], 10, 4);
            add_filter("wpdiscuz_username_classes", [&$this->helper, "usernameClasses"]);
            add_filter("wpdiscuz_avatar_classes", [&$this->helper, "commentAvatarClasses"]);
            add_action("wp_footer", [&$this->helper, "addLityPopup"]);
            add_action("wp_ajax_wcaiGetInfo", [&$this->helper, "wcaiGetInfo"]);
            add_action("wp_ajax_nopriv_wcaiGetInfo", [&$this->helper, "wcaiGetInfo"]);
            add_action("wp_ajax_wcaiGetShortInfo", [&$this->helper, "wcaiGetShortInfo"]);
            add_action("wp_ajax_nopriv_wcaiGetShortInfo", [&$this->helper, "wcaiGetShortInfo"]);
            add_action("wp_ajax_wcaiGetActivityPage", [&$this->helper, "getActivityPage"]);
            add_action("wp_ajax_nopriv_wcaiGetActivityPage", [&$this->helper, "getActivityPage"]);
            add_action("wp_ajax_wcaiGetVotesPage", [&$this->helper, "getVotesPage"]);
            add_action("wp_ajax_nopriv_wcaiGetVotesPage", [&$this->helper, "getVotesPage"]);
            add_action("wp_ajax_wcaiGetSubscriptionsPage", [&$this->helper, "getSubscriptionsPage"]);
            add_action("wp_ajax_nopriv_wcaiGetSubscriptionsPage", [&$this->helper, "getSubscriptionsPage"]);
            add_action("wp_ajax_wcaiGetFollowsPage", [&$this->helper, "getFollowsPage"]);
            add_action("wp_ajax_nopriv_wcaiGetFollowsPage", [&$this->helper, "getFollowsPage"]);
            add_action("wp_ajax_wcaiUnsubscribe", [&$this->helper, "wcaiUnsubscribe"]);
            add_action("wp_ajax_wcaiUnfollow", [&$this->helper, "wcaiUnfollow"]);
        }
    }

    public function frontendFiles($options) {
        $suf = $options->general["loadMinVersion"] ? ".min" : "";
	    if (is_rtl()) {
		    wp_enqueue_style("wcai-frontend-rtl", plugins_url(WCAI_DIR_NAME . "/assets/css/wcai-frontend-rtl$suf.css"), null, $this->version);
	    } else {
		    wp_enqueue_style("wcai-frontend", plugins_url(WCAI_DIR_NAME . "/assets/css/wcai-frontend$suf.css"), null, $this->version);
	    }
        wp_register_script("wcai-frontend-js", plugins_url(WCAI_DIR_NAME . "/assets/js/wcai-frontend$suf.js"), ["jquery"], $this->version, true);
        wp_enqueue_script("wcai-frontend-js");
    }

    public function version() {
        $pluginData = get_plugin_data(__FILE__);
        if (version_compare($pluginData["Version"], $this->version, ">")) {
            $options = get_option(self::OPTION_MAIN_OPTIONS);
            $this->addNewOptions($options);
            update_option(self::OPTION_VERSION, $pluginData["Version"]);
        }
    }

    private function addNewOptions($options) {
        $this->options->initOptions($options);
        $newOptions = $this->options->toArray();
        update_option(self::OPTION_MAIN_OPTIONS, $newOptions);
    }

}

$wpdiscuzCommentAuthorInfo = new WpdiscuzCommentAuthorInfo();
