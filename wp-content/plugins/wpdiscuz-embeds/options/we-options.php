<?php

if (!defined("ABSPATH")) {
    exit();
}

class WEOptions implements WEConstants {

    private $dbManager;
    public $playerWidth;
    public $playerHeight;
    public $widthType;
    public $heightType;
    public $embedsPerComment;
    public $embedWebsites;
    public $embedInDashboard;
    public $embedInMobile;
    public $isGuestAllowed;
    public $allowedUserRoles;
    public $allowedForms;
    public $wpoEmbedProviders;
    public $wpoEmbedProvidersOriginal;
    public $wpoEmbedProvidersDetails = [];

    public function __construct($dbManager) {
        $this->dbManager = $dbManager;
        $options = maybe_unserialize(get_option(self::OPTION_MAIN_OPTIONS));
//        remove_all_filters("oembed_providers");
        add_filter("oembed_providers", [&$this, "addoEmbedProviders"], 9999);
        $this->addOptions();
        $this->initOptions($options);

        add_action("wpdiscuz_save_options", [&$this, "saveOptions"]);
        add_action("wpdiscuz_reset_options", [&$this, "resetOptions"]);
        add_filter("wpdiscuz_settings", [&$this, "settingsArray"], 35);
    }

    public function addOptions() {
        $options = $this->getDefaultOptions();
        add_option(self::OPTION_MAIN_OPTIONS, $options, "", "no");
    }

    public function getDefaultOptions() {
        return [
            "playerWidth" => 480,
            "playerHeight" => 270,
            "widthType" => "px",
            "heightType" => "px",
            "embedsPerComment" => 5,
            "embedWebsites" => 0,
            "embedInDashboard" => 0,
            "embedInMobile" => 0,
            "isGuestAllowed" => 1,
            "allowedUserRoles" => $this->getDefaultRoles(),
            "allowedForms" => $this->getDefaultForms(),
            "wpoEmbedProviders" => $this->getWPoEmbedProviders(),
        ];
    }

    public function getOwerWritableOptions() {
        return ["wpoEmbedProviders",];
    }

    public function initOptions($options) {
        $options = maybe_unserialize($options);
        $this->playerWidth = isset($options["playerWidth"]) && ($v = absint($options["playerWidth"])) ? $v : "";
        $this->playerHeight = isset($options["playerHeight"]) && ($v = absint($options["playerHeight"])) ? $v : "";
        $this->widthType = isset($options["widthType"]) && ($v = trim($options["widthType"])) ? $v : "px";
        $this->heightType = isset($options["heightType"]) && ($v = trim($options["heightType"])) ? $v : "px";
        $this->embedsPerComment = isset($options["embedsPerComment"]) && ($v = absint($options["embedsPerComment"])) ? $v : 0;
        $this->embedWebsites = isset($options["embedWebsites"]) && ($v = absint($options["embedWebsites"])) ? $v : 0;
        $this->embedInDashboard = isset($options["embedInDashboard"]) && ($v = absint($options["embedInDashboard"])) ? $v : 0;
        $this->embedInMobile = isset($options["embedInMobile"]) && ($v = absint($options["embedInMobile"])) ? $v : 0;
        $this->isGuestAllowed = isset($options["isGuestAllowed"]) && ($v = absint($options["isGuestAllowed"])) ? $v : 0;
        $this->allowedUserRoles = isset($options["allowedUserRoles"]) && is_array($options["allowedUserRoles"]) ? $options["allowedUserRoles"] : [];
        $this->allowedForms = isset($options["allowedForms"]) && is_array($options["allowedForms"]) ? $options["allowedForms"] : [];
        $this->wpoEmbedProviders = isset($options["wpoEmbedProviders"]) && is_array($options["wpoEmbedProviders"]) && $options["wpoEmbedProviders"] ? $options["wpoEmbedProviders"] : [];
    }

    public function toArray() {
        $options = [
            "playerWidth" => $this->playerWidth,
            "playerHeight" => $this->playerHeight,
            "widthType" => $this->widthType,
            "heightType" => $this->heightType,
            "embedsPerComment" => $this->embedsPerComment,
            "embedWebsites" => $this->embedWebsites,
            "embedInDashboard" => $this->embedInDashboard,
            "embedInMobile" => $this->embedInMobile,
            "isGuestAllowed" => $this->isGuestAllowed,
            "allowedUserRoles" => $this->allowedUserRoles,
            "allowedForms" => $this->allowedForms,
            "wpoEmbedProviders" => $this->wpoEmbedProviders,
        ];
        return $options;
    }

    public function saveOptions() {
        if (self::PAGE_OPTIONS === $_POST["wpd_tab"]) {
            $this->playerWidth = isset($_POST[self::PAGE_OPTIONS]["playerWidth"]) && ($v = absint($_POST[self::PAGE_OPTIONS]["playerWidth"])) ? $v : "";
            $this->playerHeight = isset($_POST[self::PAGE_OPTIONS]["playerHeight"]) && ($v = absint($_POST[self::PAGE_OPTIONS]["playerHeight"])) ? $v : "";
            $this->widthType = isset($_POST[self::PAGE_OPTIONS]["widthType"]) && ($v = trim($_POST[self::PAGE_OPTIONS]["widthType"])) ? $v : "px";
            $this->heightType = isset($_POST[self::PAGE_OPTIONS]["heightType"]) && ($v = trim($_POST[self::PAGE_OPTIONS]["heightType"])) ? $v : "px";
            $this->embedsPerComment = isset($_POST[self::PAGE_OPTIONS]["embedsPerComment"]) && ($v = absint($_POST[self::PAGE_OPTIONS]["embedsPerComment"])) ? $v : 0;
            $this->embedWebsites = isset($_POST[self::PAGE_OPTIONS]["embedWebsites"]) && ($v = absint($_POST[self::PAGE_OPTIONS]["embedWebsites"])) ? $v : 0;
            $this->embedInDashboard = isset($_POST[self::PAGE_OPTIONS]["embedInDashboard"]) && ($v = absint($_POST[self::PAGE_OPTIONS]["embedInDashboard"])) ? $v : 0;
            $this->embedInMobile = isset($_POST[self::PAGE_OPTIONS]["embedInMobile"]) && ($v = absint($_POST[self::PAGE_OPTIONS]["embedInMobile"])) ? $v : 0;
            $this->isGuestAllowed = isset($_POST[self::PAGE_OPTIONS]["isGuestAllowed"]) && ($v = absint($_POST[self::PAGE_OPTIONS]["isGuestAllowed"])) ? $v : 0;
            $this->allowedUserRoles = isset($_POST[self::PAGE_OPTIONS]["allowedUserRoles"]) && is_array($_POST[self::PAGE_OPTIONS]["allowedUserRoles"]) ? $_POST[self::PAGE_OPTIONS]["allowedUserRoles"] : [];
            $this->allowedForms = isset($_POST[self::PAGE_OPTIONS]["allowedForms"]) && is_array($_POST[self::PAGE_OPTIONS]["allowedForms"]) ? $_POST[self::PAGE_OPTIONS]["allowedForms"] : [];
            $wpoEmbedProviders = isset($_POST[self::PAGE_OPTIONS]["wpoEmbedProviders"]) && is_array($_POST[self::PAGE_OPTIONS]["wpoEmbedProviders"]) && $_POST[self::PAGE_OPTIONS]["wpoEmbedProviders"] ? $_POST[self::PAGE_OPTIONS]["wpoEmbedProviders"] : [];
            $this->wpoEmbedProviders = $this->decodePatterns($wpoEmbedProviders);
            update_option(self::OPTION_MAIN_OPTIONS, $this->toArray());
            do_action("wpdiscuz_embeds_save_options");
        }
    }

    public function resetOptions($tab) {
        if ($tab === self::PAGE_OPTIONS || $tab === "all") {
            delete_option(self::OPTION_MAIN_OPTIONS);
            $this->addOptions();
            $this->initOptions(get_option(self::OPTION_MAIN_OPTIONS));
            do_action("wpdiscuz_embeds_save_options");
        }
    }

    public function settingsArray($settings) {
        $settings["addons"][self::PAGE_OPTIONS] = [
            "title" => __("Embeds", "wpdiscuz-embeds"),
            "title_original" => "Embeds",
            "icon" => "",
            "icon-height" => "",
            "file_path" => WE_DIR_PATH . "/options/we-html-options.php",
            "values" => $this,
            "options" => [
                "playerWidth" => [
                    "label" => __("Embed video player width", "wpdiscuz-embeds"),
                    "label_original" => "Embed video player width",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "playerHeight" => [
                    "label" => __("Embed video player height", "wpdiscuz-embeds"),
                    "label_original" => "Embed video player height",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "widthType" => [
                    "label" => __("Embed video player width type", "wpdiscuz-embeds"),
                    "label_original" => "Embed video player width type",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "heightType" => [
                    "label" => __("Embed video player height type", "wpdiscuz-embeds"),
                    "label_original" => "Embed video player height type",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "embedsPerComment" => [
                    "label" => __("Maximum number of embedded content per comment", "wpdiscuz-embeds"),
                    "label_original" => "Maximum number of embedded content per comment",
                    "description" => __("Leave empty or set this option value 0 to remove the limit", "wpdiscuz-embeds"),
                    "description_original" => "Leave empty or set this option value 0 to remove the limit", "wpdiscuz-embeds",
                    "docurl" => "#"
                ],
                "embedWebsites" => [
                    "label" => __("Embed Website URLs", "wpdiscuz-embeds"),
                    "label_original" => "Embed Website URLs",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "embedInDashboard" => [
                    "label" => __("Embed in dashboard comments", "wpdiscuz-embeds"),
                    "label_original" => "Embed in dashboard comments",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "embedInMobile" => [
                    "label" => __("Embed in mobile", "wpdiscuz-embeds"),
                    "label_original" => "Embed in mobile",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "isGuestAllowed" => [
                    "label" => __("Allow embedding for guests", "wpdiscuz-embeds"),
                    "label_original" => "Allow embedding for guests",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "allowedUserRoles" => [
                    "label" => __("Allow embedding for user roles", "wpdiscuz-embeds"),
                    "label_original" => "Allow embedding for user roles",
                    "description" => __("Embedding will work for checked user roles only", "wpdiscuz-embeds"),
                    "description_original" => "Embedding will work for checked user roles only",
                    "docurl" => "#"
                ],
                "allowedForms" => [
                    "label" => __("Allow embedding for comment forms", "wpdiscuz-embeds"),
                    "label_original" => "Allow embedding for comment forms",
                    "description" => __("You can manage comment forms and fields in Dashboard > Comments > Forms admin page.", "wpdiscuz-embeds"),
                    "description_original" => "You can manage comment forms and fields in Dashboard > Comments > Forms admin page.",
                    "docurl" => "#"
                ],
                "wpoEmbedProviders" => [
                    "label" => __("oEmbed Options", "wpdiscuz-embeds"),
                    "label_original" => "oEmbed Options",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
            ],
        ];
        return $settings;
    }

    // adding providers via wp hook
    public function addoEmbedProviders($providers) {
        /**
         * @since 1.0.0
         */
        $providers["#https?://(www\.)?giphy\.com/gifs/.*#i"] = ["https://giphy.com/services/oembed/", true, "enabled" => 1, "custom" => 0,];
        $providers["#https?://(www\.)?media\.giphy\.com/media/.*#i"] = ["https://giphy.com/services/oembed/", true, "enabled" => 1, "custom" => 0,];
        $providers["#https?://(?:www\.)?acfun\.cn/v/(.+)#i"] = ["https://www.acfun.cn/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://www.acfun.cn/player/%1$s" style="width:%2$s;height:%3$s;" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"', "scripts" => ""],];
        $providers["#https?://(?:www\.)?aparat\.com/v/([^\?\s/]+)#i"] = ["https://www.aparat.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://www.aparat.com/video/video/embed/videohash/%1$s/vt/frame" style="width:%2$s;height:%3$s;" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"', "scripts" => ""],];
        $providers["#https?://(?:www\.)?bilibili\.com/video/([^\?\s/]+)#i"] = ["https://www.bilibili.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://player.bilibili.com/player.html?bvid=%1$s" style="width:%2$s;height:%3$s;" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"', "scripts" => ""],];
        $providers["#https?://(?:www\.)?bitchute\.com/video/([^\?\s/]+)#i"] = ["https://www.bitchute.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://www.bitchute.com/embed/%1$s" style="width:%2$s;height:%3$s;" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"', "scripts" => ""],];
        $providers["#https?://(?:www\.)?nicovideo\.jp/watch/([^\?\s/]+)#i"] = ["https://www.nicovideo.jp/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://embed.nicovideo.jp/watch/%1$s" style="width:%2$s;height:%3$s;" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"', "scripts" => ""],];
        $providers["#https?://(?:www\.)?rutube\.ru/video/([^\?\s/]+)#i"] = ["https://rutube.ru/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://rutube.ru/play/embed/%1$s" style="width:%2$s;height:%3$s;" scrolling="no" border="0" frameborder="no" framespacing="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen', "scripts" => ""],];
        $providers["#https?://v.youku\.com/v_show/id_([^\?\s/]+)\.html#i"] = ["https://youku.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://player.youku.com/embed/%1$s" style="width:%2$s;height:%3$s;" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen', "scripts" => ""],];
        $providers["#https?://(?:www\.)?liveleak\.com/view\?t=([^\?\s/]+)#i"] = ["https://liveleak.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://liveleak.com/e/%1$s" style="width:%2$s;height:%3$s;" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen', "scripts" => ""],];
        /**
         * @since 1.0.1
         */
        $providers["#https?://y2u\.be/(.*)#i"] = ["https://www.youtube.com", true, "enabled" => 1, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://www.youtube.com/embed/%1$s" style="width:%2$s;height:%3$s;"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen', "scripts" => ""]];
        $providers["#https?://(www\.)?gfycat\.com/.*#i"] = ["https://api.gfycat.com/v1/oembed", true, "enabled" => 0, "custom" => 0,];
        $providers["#https?://[^.]+\.(wistia\.com|wi\.st)/(medias|embed)/.*#i"] = ["https://fast.wistia.com/oembed", true, "enabled" => 0, "custom" => 0,];
        $providers["#https?://sketchfab\.com/.*#i"] = ["https://sketchfab.com/oembed", true, "enabled" => 0, "custom" => 0,];
        $providers["#https?://(www\.)?icloud\.com/keynote/.*#i"] = ["https://iwmb.icloud.com/iwmb/oembed", true, "enabled" => 0, "custom" => 0,];
        /**
         * @since 1.0.4
         */
        $providers["#https?://v\.qq\.com/x/page/(.*)\.html#i"] = ["https://v.qq.com", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://v.qq.com/txp/iframe/player.html?vid=%1$s" style="width:%2$s;height:%3$s;"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen', "scripts" => ""]];
        /**
         * @since 1.0.7
         */
        $providers['#(https?://(?:www\.)?facebook\.com/.*/videos/.*)#i'] = ["https://videos.facebook.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://www.facebook.com/plugins/video.php?href=%1$s&show_text=false" style="width:%2$s;height:%3$s;border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"', "scripts" => ""],];
        $providers['#(https?://(?:www\.)?facebook\.com/.*/photos/.*)#i'] = ["https://posts.facebook.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://www.facebook.com/plugins/post.php?href=%1$s" style="width:%2$s;height:%3$s;border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"', "scripts" => ""],];
        /**
         * @since 1.0.8
         */
        $providers['#https?://(?:www\.)?tenor\.com/view/.+\-(\d+)$#i'] = ["https://tenor.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://tenor.com/embed/%1$s" style="width:%2$s;height:%3$s;border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"', "scripts" => ""],];

        /**
         * @since 1.0.10
         */
        $providers['#(https?://(?:www\.)?facebook\.com/watch/?\?v=\d+)#i'] = ["https://videos.facebook.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://www.facebook.com/plugins/video.php?href=%1$s&show_text=false" style="width:%2$s;height:%3$s;border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"', "scripts" => ""],];
        $providers['#(https?://(?:www\.)?fb\.watch/.*/)#i'] = ["https://videos.facebook.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => '<iframe src="https://www.facebook.com/plugins/video.php?href=%1$s&show_text=false" style="width:%2$s;height:%3$s;border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"', "scripts" => ""],];
        $providers['#(https?://(?:www\.)?facebook\.com/.*/posts/\d+/?)#i'] = ["https://posts.facebook.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://www.facebook.com/plugins/post.php?href=%1$s" style="width:%2$s;height:%3$s;border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"', "scripts" => ""],];
        $providers['#(https?://(?:www\.)?facebook\.com/photo/?\?fbid=\d+)#i'] = ["https://posts.facebook.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://www.facebook.com/plugins/post.php?href=%1$s" style="width:%2$s;height:%3$s;border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"', "scripts" => ""],];

        $providers['#https?://(?:www\.)?music\.yandex\.com/album/\d+/track/(\d+)#i'] = ["https://music.yandex.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://music.yandex.com/iframe/#track/%1$s" frameborder="0" style="border:none;width:%2$s;height:%3$s;"', "scripts" => ""],];
        $providers['#https?://(?:www\.)?music\.yandex\.ru/album/\d+/track/(\d+)#i'] = ["https://music.yandex.ru/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://music.yandex.ru/iframe/#track/%1$s" frameborder="0" style="border:none;width:%2$s;height:%3$s;"', "scripts" => ""],];

        /**
         * @since 1.0.11
         * added /tv @since 1.0.12
         */
        $providers['#https?://(?:www\.)?instagr\.am/(?:p|reel|tv)/([^\/]+)/#i'] = ["https://instagram.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://www.instagram.com/p/%1$s/embed/?cr=1&amp;v=14" allowtransparency="true" allowfullscreen="true" scrolling="no" style="width: %2$s; height: %3$s; background: white none repeat scroll 0% 0%; border-radius: 3px; border: 1px solid rgb(219, 219, 219); box-shadow: none; display: block; margin: 0px 0px 12px; padding: 0px;" frameborder="0" class="instagram-media instagram-media-rendered"', "scripts" => 'async src="//www.instagram.com/embed.js"'],];
        $providers['#https?://(?:www\.)?instagram\.com/(?:p|reel|tv)/([^\/]+)/#i'] = ["https://instagram.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://www.instagram.com/p/%1$s/embed/?cr=1&amp;v=14" allowtransparency="true" allowfullscreen="true" scrolling="no" style="width: %2$s; height: %3$s; background: white none repeat scroll 0% 0%; border-radius: 3px; border: 1px solid rgb(219, 219, 219); box-shadow: none; display: block; margin: 0px 0px 12px; padding: 0px;" frameborder="0" class="instagram-media instagram-media-rendered"', "scripts" => 'async src="//www.instagram.com/embed.js"'],];

        /**
         * @since 1.0.12
         */
        $providers['#https?://(?:www\.)?pinterest\.com/pin/(\d+)#i'] = ["https://www.pinterest.com/", true, "enabled" => 0, "custom" => 1, "html" => ["tag" => "iframe", "attrs" => 'src="https://assets.pinterest.com/ext/embed.html?id=%1$s" style="width: %2$s; height: %3$s;" frameborder="0" scrolling="no"', "scripts" => ""],];
        $providers = apply_filters("wpdiscuz_embed_providers", $providers);
        return $providers;
    }

    public function getWPoEmbedProviders() {
        $oembed = _wp_oembed_get_object();
        $providers = $oembed->providers && is_array($oembed->providers) ? $oembed->providers : [];
        $tmpProviders = [];
        if ($providers && is_array($providers)) {
            foreach ($providers as $pK => $provider) {

                if (!isset($provider["enabled"])) {
                    $provider["enabled"] = 1;
                }

                if (!isset($provider["custom"])) {
                    $provider["custom"] = 0;
                }

                $url = $provider[0];
                $parsedUrl = parse_url($url);
                $host = preg_replace("#(www\.)#", "", $parsedUrl["host"]);
                if (!isset($this->wpoEmbedProvidersDetails[$host]) || (isset($this->wpoEmbedProvidersDetails[$host]) && !in_array($pK, $this->wpoEmbedProvidersDetails[$host]))) {
                    $this->wpoEmbedProvidersDetails[$host][$pK] = $provider;
                }
                $tmpProviders[$pK] = $provider;
            }
        }
        return $tmpProviders;
    }

    public function isProviderExists($arrayToSearch, $arrayInSearch) {
        foreach ($arrayToSearch as $pK => $pV) {
            if (isset($arrayInSearch[$pK]) && isset($arrayInSearch[$pK]['enabled']) && intval($arrayInSearch[$pK]['enabled'])) {
                return true;
            }
        }
        return false;
    }

    private function decodePatterns($patterns) {
        $tmpPatterns = [];
        if ($patterns && is_array($patterns)) {
            foreach ($patterns as $pK => $pattern) {
                $data = json_decode(WEHelper::unescapeData($pattern), true);
                if ($data && is_array($data)) {
                    foreach ($data as $dK => $dV) {
                        $tmpPatterns[$dK] = $dV;
                    }
                }
            }
        }
        return $tmpPatterns;
    }

    private function getDefaultForms() {
        return get_posts([
            "numberposts" => -1,
            "post_type" => "wpdiscuz_form",
            "post_status" => "publish",
            "fields" => "ids"
        ]);
    }

    private function getDefaultRoles() {
        return ["administrator", "editor", "author", "contributor", "subscriber"];
    }

}
