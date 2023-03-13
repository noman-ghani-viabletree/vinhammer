<?php

if (!defined("ABSPATH")) {
    exit();
}

interface WCAIConstants {
    const OPTION_VERSION                = "wcai_version";
    const OPTION_MAIN_OPTIONS           = "wcai_options";
    const PAGE_OPTIONS                  = "wcai_options_page"; 
    
    const SECTION_PROFILE               = "profile";
    const SECTION_ACTIVITY              = "activity";
    const SECTION_VOTES                 = "votes";
    const SECTION_SUBSCRIPTION          = "subscriptions";
    const SECTION_FOLLOWS               = "follows";
}