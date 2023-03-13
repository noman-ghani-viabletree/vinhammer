<?php

/*
 * Plugin Name: wpDiscuz - Embeds
 * Description: Allows to embed lots of video, social network, audio and photo content providers in comment content.
 * Version: 1.1.0
 * Author: gVectors Team
 * Author URI: https://gvectors.com/
 * Plugin URI: https://gvectors.com/product/wpdiscuz-embeds/
 * Text Domain: wpdiscuz-embeds
 * Domain Path: /languages/
 */

if (!defined('ABSPATH')) {
    exit();
}

define("WE_DIR_PATH", dirname(__FILE__));
define("WE_DIR_NAME", basename(WE_DIR_PATH));
define("WE_BASENAME_FILE", basename(__FILE__));

include_once WE_DIR_PATH . "/includes/we-constants.php";
include_once WE_DIR_PATH . "/includes/we-dbmanager.php";
include_once WE_DIR_PATH . "/includes/we-helper.php";
include_once WE_DIR_PATH . "/options/we-options.php";
include_once WE_DIR_PATH . "/includes/gvt-api-manager.php";

class WpdiscuzEmbeds implements WEConstants {

    private $version;
    private $dbManager;
    private $helper;
    private $options;
    public static $CURRENT_USER = null;
    public static $is_user_logged_in = false;
	public $apimanager;

    public function __construct() {
        add_action("admin_notices", [$this, "requirements"], 1);
        add_action("plugins_loaded", [$this, "dependencies"], 1);
        add_action("wp_loaded", [$this, "initCurrentUser"], 1);
    }

    public function requirements() {
        if (current_user_can("manage_options")) {
            if (!function_exists("wpDiscuz")) {
                echo "<div class='error'><p>" . __("wpDiscuz - Embeds requires wpDiscuz to be installed!", "wpdiscuz-embeds") . "</p></div>";
            }
        }
    }

    public function dependencies() {
        if (function_exists("wpDiscuz")) {
	        $this->apimanager = new GVT_API_Manager(__FILE__, "wpdiscuz_options_page", "wpdiscuz_option_page");
            $this->dbManager = new WEDBManager();
            $this->options = new WEOptions($this->dbManager);
            $this->helper = new WEHelper($this->options);

            $this->version = get_option(self::OPTION_VERSION);
            if (!$this->version) {
                $this->version = "1.0.0";
                update_option(self::OPTION_VERSION, $this->version);
            }
            load_plugin_textdomain("wpdiscuz-embeds", false, WE_DIR_NAME . "/languages/");

            add_action("wpdiscuz_check_version", [&$this, "version"]);
            add_action("admin_enqueue_scripts", [&$this, "backendFiles"]);
            //add_action("wpdiscuz_front_scripts", [&$this, "frontendFiles"]);
        }
    }

    public function version() {
        $pluginData = get_plugin_data(__FILE__);
        if (version_compare($pluginData["Version"], $this->version, ">")) {
            $this->updateActions();
            update_option(self::OPTION_VERSION, $pluginData["Version"]);
        }
    }

    private function updateActions() {
        $savedOptions = $this->options->toArray();
        $defaultOptions = $this->options->getDefaultOptions();
        $owerWritableOptions = $this->options->getOwerWritableOptions();
        $newOptions = [];
        foreach ($defaultOptions as $dok => $dov) {
            if (isset($savedOptions[$dok])) {
                if (in_array($dok, $owerWritableOptions)) {
                    if (is_array($dov) && is_array($savedOptions[$dok])) {
                        $newOptions[$dok] = array_merge($savedOptions[$dok], $dov);
                    } else {
                        $newOptions[$dok] = $dov;
                    }
                } else {
                    $newOptions[$dok] = $savedOptions[$dok];
                }
            }
        }
        $this->options->initOptions($newOptions);
        update_option(self::OPTION_MAIN_OPTIONS, $newOptions);
        do_action("wpdiscuz_embeds_save_options");
    }

    public function backendFiles() {
        $args = [];
        wp_register_style("we-backend-css", plugins_url(WE_DIR_NAME . "/assets/css/we-backend.css"), null, $this->version);
        wp_enqueue_style("we-backend-css");
        wp_register_script("we-backend-js", plugins_url(WE_DIR_NAME . "/assets/js/backend.js"), ["jquery"]);
        wp_localize_script("we-backend-js", "weJsObj", $args);
        wp_enqueue_script("we-backend-js");
    }

    /*
      public function frontendFiles($options) {
      }
     */

    public function initCurrentUser() {
        self::$is_user_logged_in = is_user_logged_in();
        if (is_null(self::$CURRENT_USER)) {
            self::$CURRENT_USER = wp_get_current_user();
        }
    }

}

$wpdiscuzEmbeds = new WpdiscuzEmbeds();
