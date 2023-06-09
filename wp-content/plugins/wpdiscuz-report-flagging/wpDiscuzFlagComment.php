<?php

/*
 * Plugin Name: wpDiscuz - Report and Flagging
 * Description: Adds comment reporting and flagging features. Auto-moderates comments based on number of reports and flags.
 * Version: 7.0.11
 * Author: gVectors Team
 * Author URI: https://gvectors.com/
 * Plugin URI: https://gvectors.com/product/wpdiscuz-report-flagging/
 * Text Domain: wpdiscuz_fc
 * Domain Path: /languages/
 */

if (!defined("ABSPATH")) {
    exit();
}

define("WPDISCUZ_FLAG_DIR_PATH", dirname(__FILE__));
define("WPDISCUZ_FLAG_DIR_NAME", basename(WPDISCUZ_FLAG_DIR_PATH));
define("FC_KEY", "Reactivated");

require_once WPDISCUZ_FLAG_DIR_PATH . "/includes/gvt-api-manager.php";
include_once WPDISCUZ_FLAG_DIR_PATH . "/wpDiscuzFCommentOption.php";
include_once WPDISCUZ_FLAG_DIR_PATH . "/includes/wpDiscuzFlagDBManager.php";
include_once WPDISCUZ_FLAG_DIR_PATH . "/includes/wpDiscuzFlagHelper.php";
include_once WPDISCUZ_FLAG_DIR_PATH . "/includes/wpDiscuzFlagHelperAjax.php";

class wpDiscuzFlagComment {

    /**
     * @var wpDiscuzFCommentOption
     */
    public $options;
    
    /**
     * @var wpDiscuzFlagDBManager
     */
    public $dbManager;

    /**
     * @var wpDiscuzFlagHelper
     */
    public $helper;
    
    /**
     * @var wpDiscuzFlagHelperAjax
     */
    public $helperAjax;
    public $to;
    public $postTitle;
    public $postName;
    public $comment;
    public $commentId;
    public $userLogin;
    public $userIp;
    public $userEmail;
    public $version;

    /**
     * @var wpDiscuzFlagComment
     */
    private static $instance;
    public static $VERSION_SLUG = "wpdiscuz_fc_version";

    /**
     * @var GVT_API_Manager
     */
    public $apimanager;

    private function __construct() {
        $this->version = get_option(self::$VERSION_SLUG, "1.0.0");
        $this->dbManager = new wpDiscuzFlagDBManager();
        register_activation_hook(__FILE__, [$this, "createTablesMultisite"]);
        add_action("wp_insert_site", [&$this->dbManager, "onNewBlog"], 10);
        add_filter("wpmu_drop_tables", [&$this->dbManager, "onDeleteBlog"]);
        add_action("plugins_loaded", [&$this, "pluginsLoaded"], 14.5);
    }

    public function createTablesMultisite($networkWide) {
        if (is_multisite() && $networkWide) {
            $oldBlogID = $this->dbManager->getBlogID();
            $blogIDs = $this->dbManager->getBlogIDs();
            foreach ($blogIDs as $k => $blogID) {
                switch_to_blog($blogID);
                $this->dbManager->createTables();
            }
            switch_to_blog($oldBlogID);
        } else {
            $this->dbManager->createTables();
        }
    }

    public function pluginsLoaded() {
        if (function_exists("wpDiscuz")) {
            $this->apimanager = new GVT_API_Manager(__FILE__, "wpdiscuz_options_page", "wpdiscuz_option_page");
            $this->options = new wpDiscuzFCommentOption();
            $this->helper = new wpDiscuzFlagHelper($this->dbManager, $this->options);
            $this->helperAjax = new wpDiscuzFlagHelperAjax($this->dbManager, $this->options);
            load_plugin_textdomain("wpdiscuz_fc", false, WPDISCUZ_FLAG_DIR_NAME . "/languages/");
            add_action("admin_init", [&$this, "wpFcNewVersion"], 1);
            add_action("admin_enqueue_scripts", [$this, "registerFlagAdminStyle"]);
            add_action("wpdiscuz_front_scripts", [$this, "registerFlagFrontStyle"]);
            add_action("transition_comment_status", [&$this, "changeCommentStatus"], 265, 3);
            add_action("delete_comment", [$this->dbManager, "deleteReportedData"], 10, 1);
            add_filter("wpdiscuz_js_options", [&$this, "addFrontendArgs"], 10, 2);
        } else {
            add_action("admin_notices", [&$this, "fcRequirements"], 1);
        }
    }

    public function fcRequirements() {
        if (current_user_can("manage_options")) {
            echo "<div class='error'><p>" . __("wpDiscuz - Report and Flagging requires wpDiscuz to be installed!", "wpdiscuz_fc") . "</p></div>";
        }
    }

    public function addFrontendArgs($args, $options) {
        $args["primaryColor"] = $options->thread_styles["primaryColor"];
        return $args;
    }

    public function wpFcNewVersion() {
        $pluginData = get_plugin_data(__FILE__);
        $newVersion = $pluginData["Version"];
        if (version_compare($this->version, $newVersion, "<")) {
            if ($newVersion == "1.2.2") {
                $this->dbManager->dropUserHashColumn();
                $this->dbManager->addIndexes();
                $this->dbManager->updateUserIps();
            }
            $this->version = $newVersion;
            $this->options->margeOption();
        }
        update_option(self::$VERSION_SLUG, $this->version);
    }

    function changeCommentStatus($newStatus, $oldStatus, $comment) {
        $comment_id = $comment->comment_ID;
        if ($newStatus != $oldStatus) {
            if ($newStatus === "approved" && $comment->comment_type === "wpdiscuz_reported") {
                $this->dbManager->deleteReportedData($comment_id);
                $this->dbManager->updateReportedData($comment_id);
                $meta_values = get_comment_meta($comment_id, FC_KEY, true);
                if ($meta_values) {
                    $meta_values = intval($meta_values + 1);
                    update_comment_meta($comment_id, FC_KEY, $meta_values);
                } else {
                    add_comment_meta($comment_id, FC_KEY, 2);
                }
            }
        }
    }

    public function registerFlagAdminStyle() {
        if (isset($_GET["page"]) && isset($_GET["wpd_tab"]) && $_GET["page"] === WpdiscuzCore::PAGE_SETTINGS && $_GET["wpd_tab"] === $this->options->tabKey) {
            $args = [
                'tabKey' => $this->options->tabKey,
            ];
            wp_register_style("wpdiscuz-flag-css", plugins_url(WPDISCUZ_FLAG_DIR_NAME . "/assets/css/admin-flag.css"), null, $this->version);
            wp_enqueue_style("wpdiscuz-flag-css");
            wp_register_script("wpdiscuz-flag-admin-js", plugins_url(WPDISCUZ_FLAG_DIR_NAME . "/assets/js/flag_admin.js"), ["jquery"], $this->version, false);
            wp_enqueue_script("wpdiscuz-flag-admin-js");
            wp_localize_script('wpdiscuz-flag-admin-js', 'wpdiscuzFcObj', $args);
        }
    }

    public function registerFlagFrontStyle($options) {
        $suf = $options->general["loadMinVersion"] ? ".min" : "";
        if (is_rtl()) {
            wp_register_style("wpdiscuz-flag-rtl-css", plugins_url(WPDISCUZ_FLAG_DIR_NAME . "/assets/css/flag-rtl$suf.css"), null, $this->version);
            wp_enqueue_style("wpdiscuz-flag-rtl-css");
        } else {
            wp_register_style("wpdiscuz-flag-css", plugins_url(WPDISCUZ_FLAG_DIR_NAME . "/assets/css/flag$suf.css"), null, $this->version);
            wp_enqueue_style("wpdiscuz-flag-css");
        }
        wp_register_script("wpdiscuz-flag-js", plugins_url(WPDISCUZ_FLAG_DIR_NAME . "/assets/js/flag$suf.js"), ["jquery"], $this->version, true);
        wp_enqueue_script("wpdiscuz-flag-js");
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new wpDiscuzFlagComment();
        }
        return self::$instance;
    }

}

$wpDiscuzFlagComment = wpDiscuzFlagComment::getInstance();
