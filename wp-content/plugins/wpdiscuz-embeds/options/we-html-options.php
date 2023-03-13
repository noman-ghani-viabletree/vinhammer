<?php
if (!defined("ABSPATH")) {
    exit();
}
?>
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="playerWidth">
    <div class="wpd-opt-name">
        <label for="playerWidth"><?php echo $setting["options"]["playerWidth"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["playerWidth"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <span>
            <input type="text" value="<?php echo $setting["values"]->playerWidth; ?>" name="<?php echo WpdiscuzEmbeds::PAGE_OPTIONS; ?>[playerWidth]" id="playerWidth" style="width:70px;">
            <select name="<?php echo WpdiscuzEmbeds::PAGE_OPTIONS; ?>[widthType]" style="min-width: auto;width: auto;vertical-align: top;">
                <option value="px" <?php selected($setting["values"]->widthType == "px"); ?>><?php _e("px", "wpdiscuz-embeds"); ?></option>
                <option value="%" <?php selected($setting["values"]->widthType == "%"); ?>><?php _e("%", "wpdiscuz-embeds"); ?></option>
            </select>
        </span>
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="playerHeight">
    <div class="wpd-opt-name">
        <label for="playerHeight"><?php echo $setting["options"]["playerHeight"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["playerHeight"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <span>
            <input type="text" value="<?php echo $setting["values"]->playerHeight; ?>" name="<?php echo WpdiscuzEmbeds::PAGE_OPTIONS; ?>[playerHeight]" id="playerHeight" style="width:70px;">
            <select name="<?php echo WpdiscuzEmbeds::PAGE_OPTIONS; ?>[heightType]" style="min-width: auto;width: auto;vertical-align: top;">
                <option value="px" <?php selected($setting["values"]->heightType == "px"); ?>><?php _e("px", "wpdiscuz-embeds"); ?></option>
                <option value="%" <?php selected($setting["values"]->heightType == "%"); ?>><?php _e("%", "wpdiscuz-embeds"); ?></option>
            </select>
        </span>
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="embedsPerComment">
    <div class="wpd-opt-name">
        <label for="embedsPerComment"><?php echo $setting["options"]["embedsPerComment"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["embedsPerComment"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <span for="embedsPerComment"><input type="number" min="0" value="<?php echo $setting["values"]->embedsPerComment; ?>" name="<?php echo WpdiscuzEmbeds::PAGE_OPTIONS; ?>[embedsPerComment]" id="embedsPerComment" style="width:70px;"></span>
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="embedWebsites">
    <div class="wpd-opt-name">
        <label for="embedWebsites"><?php echo $setting["options"]["embedWebsites"]["label"]; ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["embedWebsites"]["description"]; ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->embedWebsites == 1); ?> value="1" name="<?php echo WpdiscuzEmbeds::PAGE_OPTIONS; ?>[embedWebsites]" id="embedWebsites" />
            <label for="embedWebsites"></label>
        </div>
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="embedInDashboard">
    <div class="wpd-opt-name">
        <label for="embedInDashboard"><?php echo $setting["options"]["embedInDashboard"]["label"]; ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["embedInDashboard"]["description"]; ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->embedInDashboard == 1); ?> value="1" name="<?php echo WpdiscuzEmbeds::PAGE_OPTIONS; ?>[embedInDashboard]" id="embedInDashboard" />
            <label for="embedInDashboard"></label>
        </div>
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="embedInMobile">
    <div class="wpd-opt-name">
        <label for="embedInMobile"><?php echo $setting["options"]["embedInMobile"]["label"]; ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["embedInMobile"]["description"]; ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->embedInMobile == 1); ?> value="1" name="<?php echo WpdiscuzEmbeds::PAGE_OPTIONS; ?>[embedInMobile]" id="embedInMobile" />
            <label for="embedInMobile"></label>
        </div>
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="isGuestAllowed">
    <div class="wpd-opt-name">
        <label for="isGuestAllowed"><?php echo $setting["options"]["isGuestAllowed"]["label"]; ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["isGuestAllowed"]["description"]; ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->isGuestAllowed == 1); ?> value="1" name="<?php echo WpdiscuzEmbeds::PAGE_OPTIONS; ?>[isGuestAllowed]" id="isGuestAllowed" />
            <label for="isGuestAllowed"></label>
        </div>
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="allowedUserRoles">
    <div class="wpd-opt-name">
        <label for="allowedUserRoles"><?php echo $setting["options"]["allowedUserRoles"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["allowedUserRoles"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <?php
        $blogRoles = get_editable_roles();
        foreach ($blogRoles as $role => $info) {
            ?>
            <div class="wpd-mublock-inline" style="width: 45%;">
                <input type="checkbox" <?php checked(in_array($role, $setting["values"]->allowedUserRoles)); ?> value="<?php echo $role; ?>" name="<?php echo WpdiscuzEmbeds::PAGE_OPTIONS; ?>[allowedUserRoles][]" id="we-<?php echo $role; ?>" style="margin:0px; vertical-align: middle;" />
                <label for="we-<?php echo $role; ?>" style=""><?php echo $info["name"]; ?></label>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="wpd-opt-doc">
        <a href="<?php echo esc_url($setting["options"]["allowedUserRoles"]["docurl"]) ?>" title="<?php _e("Read the documentation", "wpdiscuz") ?>" target="_blank"><i class="far fa-question-circle"></i></a>
    </div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="allowedForms">
    <div class="wpd-opt-name">
        <label for="allowedForms"><?php echo $setting["options"]["allowedForms"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["allowedForms"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <?php
        $forms = get_posts(["numberposts" => -1, "post_type" => "wpdiscuz_form", "post_status" => "publish"]);
        foreach ($forms as $form) {
            ?>
            <div class="wpd-mublock-inline" style="width:95%;">
                <input type="checkbox" <?php checked(in_array($form->ID, $setting["values"]->allowedForms)); ?> value="<?php echo $form->ID; ?>" name="<?php echo WpdiscuzEmbeds::PAGE_OPTIONS; ?>[allowedForms][]" id="we-form<?php echo $form->ID; ?>" style="margin:0px; vertical-align: middle;" />
                <label for="we-form<?php echo $form->ID; ?>" style="white-space:nowrap; font-size:13px;"><?php echo $form->post_title ? esc_html($form->post_title) : __('no title', "wpdiscuz_private_comment") . ' ( ID : ' . $form->ID . ' )'; ?></label>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="wpd-opt-doc">
        <a href="<?php echo esc_url($setting["options"]["allowedForms"]["docurl"]) ?>" title="<?php _e("Read the documentation", "wpdiscuz") ?>" target="_blank"><i class="far fa-question-circle"></i></a>
    </div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wpoEmbedProviders" style="display: block;">
    <div class="wpd-opt-name">
        <label for="wpoEmbedProviders"><?php echo $setting["options"]["wpoEmbedProviders"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wpoEmbedProviders"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input we-embed-items" style="width:100%; display: inline-block; padding-top: 10px;">
        <?php
        $c_providers = $setting["values"]->getWPoEmbedProviders();
        $assetsUrl = plugins_url(WE_DIR_NAME);
        $wpoEmbedProviders = $setting["values"]->wpoEmbedProviders;
        $wpoEmbedProvidersDetails = $setting["values"]->wpoEmbedProvidersDetails;
        foreach ($wpoEmbedProvidersDetails as $pK => $provider) {
            $img = "";
            $src = "/assets/img/wp-icons/";
            if (file_exists(WE_DIR_PATH . $src . "$pK.png")) {
                $img = "<img src='" . $assetsUrl . $src . "$pK.png' title='$pK' style='vertical-align:middle; margin-right:5px; max-height: 20px;'>";
            } else {
                $img = "<img src='" . $assetsUrl . $src . "embed.png' title='$pK' style='vertical-align:middle; margin-right:5px; max-height: 20px;'>";
            }
            ?>
            <div class="wpd-opt-input we-block">
                <div style="float: left;" class="embed-label-wrap">                    
                    <label for="embed-<?php echo $pK; ?>" class="embed-label"><?php echo $img; ?><span><?php echo ucfirst($pK); ?></span></label>
                </div>
                <div style="float: right;" class="we-provider-checkbox-values">
                    <input type="checkbox" <?php checked($setting["values"]->isProviderExists($provider, $wpoEmbedProviders)); ?> id="embed-<?php echo $pK; ?>" class="we-provider-checkbox">
                    <?php
                    if (is_array($provider)) {
                        ?>
                        <div style="display: none;">
                            <?php
                            foreach ($provider as $patternK => $pattern) {
                                $embedPatterns = [];
                                $embedPatterns[$patternK] = $pattern;
                                ?>
                                <textarea name="<?php echo WpdiscuzEmbeds::PAGE_OPTIONS; ?>[wpoEmbedProviders][]" class="we-provider-hidden-value"><?php echo json_encode($embedPatterns); ?></textarea>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <div style="margin-top:15px;float:right;">
        <button id="weSelectMimes" type="button" class="button button-secondary"><?php esc_html_e("Select All", "wpdiscuz-embeds"); ?></button>
        <button id="weUnselectMimes" type="button" class="button button-secondary"><?php esc_html_e("Unselect All", "wpdiscuz-embeds"); ?></button>
        <button id="weInvertMimes" type="button" class="button button-secondary"><?php esc_html_e("Invert Selection", "wpdiscuz-embeds"); ?></button>
    </div>
    <div class="wpd-clear"></div>
</div>
<div class="wpd-clear"></div>
<!-- Option end -->