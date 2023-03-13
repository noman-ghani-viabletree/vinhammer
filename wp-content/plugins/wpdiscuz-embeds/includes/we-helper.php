<?php

if (!defined("ABSPATH")) {
    exit();
}

class WEHelper implements WEConstants {

    private $options;
    private $post;
    private $embedsCount = 0;
    private $isEnabled = false;
    private $isKnown = false;
    private $isCustom = false;
    private $isEmbed = false;
    private $isFormSupportsEmbedding = false;
    private $urlPattern = "([^\'\"]|^)(https?://[^\s\'\"<>]+)([^\'\"]|$)";

    public function __construct($options) {
        $this->options = $options;
        $this->isEmbed = $this->options->embedInDashboard ? true : (!is_admin() || (defined("DOING_AJAX") && DOING_AJAX));
        $isMobile = wp_is_mobile();
        if (!$isMobile || ($this->options->embedInMobile && $isMobile)) {
            add_filter("wpdiscuz_get_form", [&$this, "weGetForm"], 57);
            /* MOVED INTO autoEmbed FUNCTION TO AVOID AFFECTION ON EMBEDS IN POST CONTENT */
//            add_filter("embed_defaults", [&$this, "embedAttributes"], 10, 2);
//            add_filter("embed_oembed_html", [$this, "getCachedEmbed"], 10, 4);
//            add_filter("pre_oembed_result", [$this, "getUrlOrEmbed"], 2, 3);
            /* MOVED INTO autoEmbed FUNCTION TO AVOID AFFECTION ON EMBEDS IN POST CONTENT */
            add_filter("comment_text", [$this, "autoEmbed"], 2, 3);
        }
    }

    public function weGetForm($form) {
        $this->isFormSupportsEmbedding = $form && in_array($form->getFormID(), $this->options->allowedForms);
        return $form;
    }

    public function embedAttributes($attr, $url) {
        $attr["width"] = $this->options->playerWidth;
        $attr["height"] = $this->options->playerHeight;
        return $attr;
    }

    public function getCachedEmbed($cache, $url, $attr, $postId) {
        if ($cache) {
            global $wp_embed;
            $embedsPerComment = (int) $this->options->embedsPerComment;
            if ($embedsPerComment && $embedsPerComment === $this->embedsCount) {
                return $wp_embed ? $wp_embed->maybe_make_link($url) : make_clickable($url);
            }

            $width = $this->options->playerWidth ? $this->options->playerWidth . $this->options->widthType : "100%";
            $height = $this->options->playerHeight ? $this->options->playerHeight . $this->options->heightType : "auto";
            $html = "<div class='wpdiscuz-embed-wrapper'>";
            $html .= "<style type='text/css'>#comments #wpdcom .wpdiscuz-embed-wrapper span, #respond #wpdcom .wpdiscuz-embed-wrapper span {display:inline-block;}#wpdcom .wpdiscuz-embed-wrapper iframe{width:$width;height:$height;}</style>";
            /* TRY TO REPLACE STYLE ATTRIBUTE WITH OPTION  {playerWidth} */
            //$cache = preg_replace("#(<iframe[^>]*style=[\'\"][^\'\"]*[\s;\'\"]width\s*:)[^:;\'\"]+([^>]*>)#i", "$1{$this->options->playerWidth}px$2", $cache);
            /* TRY TO REPLACE STYLE ATTRIBUTE WITH OPTION  {playerHeight} */
            //$cache = preg_replace("#(<iframe[^>]*style=[\'\"][^\'\"]*[\s;\'\"]height\s*:)[^:;\'\"]+([^>]*>)#i", "$1{$this->options->playerHeight}px$2", $cache);
            $html .= $cache;
            $html .= "</div>";
            $cache = $html;
            $this->embedsCount++;
        }
        return $cache;
    }

    public function autoEmbed($content, $comment, $args = []) {
        $this->embedsCount = 0;

        // if embedding not allowed for current user role or user is guest and guest embedding not allowed return unchanged content
        if (!$this->isAllowedUser() || !$this->isEmbed) {
            return $content;
        }

        global $post, $wp_embed;

        if (!$this->post) {
            if ($post) {
                $this->post = $post;
            } else if (!empty($args['post_id'])) {
                $this->post = get_post($args['post_id']);
            } else if ($comment->comment_post_ID) {
                $this->post = get_post($comment->comment_post_ID);
            }
        }

        // if post not initiated return unchanged content
        if (!$this->post || !$wp_embed) {
            return $content;
        }

        // if embedding not allowed for current comment form type return unchanged content
        if (!$this->isFormSupportsEmbedding) {
            return $content;
        }

        if (is_admin() || !empty($args["is_wpdiscuz_loaded"])) {
            remove_filter("comment_text", "wp_kses_post");
        }

        add_filter("embed_defaults", [&$this, "embedAttributes"], 10, 2);
        add_filter("embed_oembed_html", [$this, "getCachedEmbed"], 10, 4);
        add_filter("pre_oembed_result", [$this, "getUrlOrEmbed"], 2, 3);
        add_filter("oembed_request_post_id", function ($postId, $url) {
            if (!$this->options->embedWebsites) {
                return 0;
            }
            return $postId;
        }, 999, 2);

        $wp_embed->post_ID = $this->post->ID;
        $content = $wp_embed->autoembed($content);
        // Replace line breaks from all HTML elements with placeholders.
        $content = wp_replace_in_html_tags($content, array("\n" => '<!-- wp-line-break -->'));

        if (preg_match('#' . $this->urlPattern . '#i', $content)) {
            $content = preg_replace_callback('#' . $this->urlPattern . '#im', array($wp_embed, 'autoembed_callback'), $content);

            /**
             * Find URLs even they are on same line with text
             * // Find URLs on their own line. |^(\s*)(https?://[^\s<>"]+)(\s*)$|im <=== CHANGED TO ===> |(\s*)(https?://[^\s<>"]+)(\s*)$|im
             */
            /*
              $content = preg_replace_callback('|(\s*)(https?://[^\s<>"]+)(\s*)$|im', array($wp_embed, 'autoembed_callback'), $content);
             */
            // Find URLs in their own paragraph.
            /*
              $content = preg_replace_callback('|(<p(?: [^>]*)?>\s*)(https?://[^\s<>"]+)(\s*<\/p>)|i', array($wp_embed, 'autoembed_callback'), $content);
             */
        }

        // Put the line breaks back.
        $content = str_replace('<!-- wp-line-break -->', "\n", $content);

        return $content;
    }

    public function getUrlOrEmbed($pre, $url, $args) {

        $embedsPerComment = (int) $this->options->embedsPerComment;
        if ($embedsPerComment && $embedsPerComment === $this->embedsCount) {
            return false;
        }

        $this->isKnown = false;
        $this->isEnabled = false;
        $this->isCustom = false;
        if ($this->options->wpoEmbedProviders && is_array($this->options->wpoEmbedProviders)) {
            $wpoEmbedProviders = $this->options->wpoEmbedProviders;
            $width = $this->options->playerWidth ? $this->options->playerWidth . $this->options->widthType : "100%";
            $height = $this->options->playerHeight ? $this->options->playerHeight . $this->options->heightType : "auto";

            foreach ($wpoEmbedProviders as $regex => $service) {
                $this->isKnown = preg_match($regex, $url, $match);
                if ($this->isKnown) {
                    if (!empty($service["enabled"])) {
                        $this->isEnabled = true;
                        if (!empty($service["custom"])) {
                            $this->isCustom = true;
                            $src = empty($match[1]) ? false : $match[1];
                            $embedArgs = [$src, $width, $height];
                            $res = $this->getCustomEmbed($service["html"], $embedArgs);
                            return $res;
                        }
                    }
                    break;
                }
            }
        }


        // if url found in enabled services try to embed
        if ($this->isEnabled) {
            $res = $pre;
        }
        // if url found but not in enabled services and embed post urls is enabled try to embed
        else if (!$this->isKnown && !$this->isEnabled && $this->options->embedWebsites) {
            $res = $pre;
        } else {
            $res = false;
        }

        return $res;
    }

    private function getCustomEmbed($embed, $args = []) {
        $html = false;
        if (!empty($args[0])) {
            /*
             * $args[0] => embed source
             * $args[1] => embed width
             * $args[2] => embed height
             */
            if (!empty($embed)) {

                if (is_array($embed)) {
                    $tag = empty($embed["tag"]) ? "" : $embed["tag"];
                    $attrs = empty($embed["attrs"]) ? "" : $embed["attrs"];
                    $scripts = empty($embed["scripts"]) ? "" : $embed["scripts"];
                    
                    $replacedAttrs = sprintf($attrs, urlencode($args[0]), $args[1], $args[2]);
                    $html = "<{$tag} {$replacedAttrs}></{$tag}><script {$scripts}></script>";
                } else {
                    $html = sprintf($embed, urlencode($args[0]), $args[1], $args[2]);
                }
            }
        }
        return $html;
    }

    public static function unescapeData($data) {
        if (!empty($data)) {
            $data = is_array($data) ? array_map(array('WEHelper', 'unescapeData'), $data) : stripslashes($data);
        }
        return $data;
    }

    public function isAllowedUser() {
        $allowed = false;
        if (WpdiscuzEmbeds::$is_user_logged_in) {
            if (!empty(WpdiscuzEmbeds::$CURRENT_USER->roles)) {
                foreach (WpdiscuzEmbeds::$CURRENT_USER->roles as $k => $val) {
                    if (in_array($val, $this->options->allowedUserRoles)) {
                        $allowed = true;
                        break;
                    }
                }
            }
        } else if ($this->options->isGuestAllowed) {
            $allowed = true;
        }
        return apply_filters("wpdiscuz_embeds_allowed_user", $allowed, WpdiscuzEmbeds::$CURRENT_USER);
    }

}
