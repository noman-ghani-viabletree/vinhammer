<?php

if (!defined("ABSPATH")) {
    exit();
}

interface WEConstants {
    const OPTION_VERSION                = "wpdiscuz_embeds_version";
    const OPTION_MAIN_OPTIONS           = "wpdiscuz_embeds_options";
    const PAGE_OPTIONS                  = "embeds"; 
    
    /* === CACHE ===*/
    const WPDISCUZ_EMBED                = "wpdiscuz_embed";
    const WP_OEMBED                     = "wp_oembed";
    const WPDISCUZ_OEMBED               = "wpdiscuz_oembed";
}