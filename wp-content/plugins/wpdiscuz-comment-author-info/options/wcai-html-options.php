<?php
if (!defined("ABSPATH")) {
    exit();
}
?>
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="sections">
    <div class="wpd-opt-name">
        <label for="sections"><?php echo $setting["options"]["sections"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["sections"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <?php
        $sections = $setting["values"]->getDefaultSections();
        $isArraySections = is_array($setting["values"]->sections);
        foreach ($sections as $section) {
            ?>
            <div class="wpd-mublock-inline" style="width: 200px; min-width: 33%;">
                <input type="checkbox" <?php checked($isArraySections && in_array($section, $setting["values"]->sections)); ?> value="<?php echo $section; ?>" name="<?php echo $setting["values"]->tabKey; ?>[sections][]" id="wcai-<?php echo $section; ?>" style="margin:0px; vertical-align: middle;" />
                <label for="wcai-<?php echo $section; ?>" style="white-space:nowrap; font-size:13px;"><?php echo ucfirst($section); ?></label>
            </div>
            <?php
        }
        ?>
        <div class="wpd-clear"></div>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="showForRoles">
    <div class="wpd-opt-name">
        <label for="sections"><?php echo $setting["options"]["showForRoles"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["showForRoles"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <?php
        $blogRoles = get_editable_roles();
        $isArrayshowForRoles = is_array($setting["values"]->showForRoles);
        foreach ($blogRoles as $role => $info) {
            ?>
            <div class="wpd-mublock-inline" style="width: 200px; min-width: 33%;">
                <input type="checkbox" <?php checked($isArrayshowForRoles && in_array($role, $setting["values"]->showForRoles)); ?> value="<?php echo $role; ?>" name="<?php echo $setting["values"]->tabKey; ?>[showForRoles][]" id="wcai-<?php echo $role; ?>" style="margin:0px; vertical-align: middle;" />
                <label for="wcai-<?php echo $role; ?>" style="white-space:nowrap; font-size:13px;"><?php echo $info["name"]; ?></label>
            </div>
            <?php
        }
        ?>
        <div class="wpd-clear"></div>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="showForGuests">
    <div class="wpd-opt-name">
        <label for="showForGuests"><?php echo $setting["options"]["showForGuests"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["showForGuests"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->showForGuests == 1) ?> value="1" name="<?php echo $setting["values"]->tabKey; ?>[showForGuests]" id="showForGuests">
            <label for="showForGuests"></label>
        </div>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="shortInfoOnAvatarHover">
    <div class="wpd-opt-name">
        <label for="shortInfoOnAvatarHover"><?php echo $setting["options"]["shortInfoOnAvatarHover"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["shortInfoOnAvatarHover"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->shortInfoOnAvatarHover == 1) ?> value="1" name="<?php echo $setting["values"]->tabKey; ?>[shortInfoOnAvatarHover]" id="shortInfoOnAvatarHover">
            <label for="shortInfoOnAvatarHover"></label>
        </div>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="fullInfoOnUsernameClick">
    <div class="wpd-opt-name">
        <label for="fullInfoOnUsernameClick"><?php echo $setting["options"]["fullInfoOnUsernameClick"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["fullInfoOnUsernameClick"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->fullInfoOnUsernameClick == 1) ?> value="1" name="<?php echo $setting["values"]->tabKey; ?>[fullInfoOnUsernameClick]" id="fullInfoOnUsernameClick">
            <label for="fullInfoOnUsernameClick"></label>
        </div>
    </div>
</div>
<!-- Option end -->
<div class="wpd-subtitle">
    <?php _e("Profile Information", "wpdiscuz-comment-author-info") ?>
</div>
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="profileShowDisplayName">
    <div class="wpd-opt-name">
        <label for="profileShowDisplayName"><?php echo $setting["options"]["profileShowDisplayName"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["profileShowDisplayName"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->profileShowDisplayName == 1) ?> value="1" name="<?php echo $setting["values"]->tabKey; ?>[profileShowDisplayName]" id="profileShowDisplayName">
            <label for="profileShowDisplayName"></label>
        </div>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="profileShowNickName">
    <div class="wpd-opt-name">
        <label for="profileShowNickName"><?php echo $setting["options"]["profileShowNickName"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["profileShowNickName"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->profileShowNickName == 1) ?> value="1" name="<?php echo $setting["values"]->tabKey; ?>[profileShowNickName]" id="profileShowNickName">
            <label for="profileShowNickName"></label>
        </div>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="profileShowBio">
    <div class="wpd-opt-name">
        <label for="profileShowBio"><?php echo $setting["options"]["profileShowBio"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["profileShowBio"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->profileShowBio == 1) ?> value="1" name="<?php echo $setting["values"]->tabKey; ?>[profileShowBio]" id="profileShowBio">
            <label for="profileShowBio"></label>
        </div>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="profileShowWebUrl">
    <div class="wpd-opt-name">
        <label for="profileShowWebUrl"><?php echo $setting["options"]["profileShowWebUrl"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["profileShowWebUrl"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->profileShowWebUrl == 1) ?> value="1" name="<?php echo $setting["values"]->tabKey; ?>[profileShowWebUrl]" id="profileShowWebUrl">
            <label for="profileShowWebUrl"></label>
        </div>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="profileShowStatistics">
    <div class="wpd-opt-name">
        <label for="profileShowStatistics"><?php echo $setting["options"]["profileShowStatistics"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["profileShowStatistics"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->profileShowStatistics == 1) ?> value="1" name="<?php echo $setting["values"]->tabKey; ?>[profileShowStatistics]" id="profileShowStatistics">
            <label for="profileShowStatistics"></label>
        </div>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="profileShowMycredData">
    <div class="wpd-opt-name">
        <label for="profileShowMycredData"><?php echo $setting["options"]["profileShowMycredData"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["profileShowMycredData"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->profileShowMycredData == 1) ?> value="1" name="<?php echo $setting["values"]->tabKey; ?>[profileShowMycredData]" id="profileShowMycredData">
            <label for="profileShowMycredData"></label>
        </div>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="perPage">
    <div class="wpd-opt-name">
        <label for="perPage"><?php echo $setting["options"]["perPage"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["perPage"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="number" id="perPage" min="0" name="<?php echo $setting["values"]->tabKey; ?>[perPage]" value="<?php echo $setting["values"]->perPage; ?>" style="width: 80px;"/>
    </div>
</div>
<!-- Option end -->
<div class="wpd-subtitle">
    <?php _e("Phrases", "wpdiscuz-comment-author-info") ?>
</div>
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseProfileSectionTitle">
    <div class="wpd-opt-name">
        <label for="phraseProfileSectionTitle"><?php echo $setting["options"]["phraseProfileSectionTitle"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseProfileSectionTitle"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseProfileSectionTitle]" value="<?php echo $setting["values"]->phraseProfileSectionTitle; ?>" id="phraseProfileSectionTitle" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseActivitySectionTitle">
    <div class="wpd-opt-name">
        <label for="phraseActivitySectionTitle"><?php echo $setting["options"]["phraseActivitySectionTitle"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseActivitySectionTitle"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseActivitySectionTitle]" value="<?php echo $setting["values"]->phraseActivitySectionTitle; ?>" id="phraseActivitySectionTitle" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseVotesSectionTitle">
    <div class="wpd-opt-name">
        <label for="phraseVotesSectionTitle"><?php echo $setting["options"]["phraseVotesSectionTitle"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseVotesSectionTitle"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseVotesSectionTitle]" value="<?php echo $setting["values"]->phraseVotesSectionTitle; ?>" id="phraseVotesSectionTitle" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseSubscriptionsSectionTitle">
    <div class="wpd-opt-name">
        <label for="phraseSubscriptionsSectionTitle"><?php echo $setting["options"]["phraseSubscriptionsSectionTitle"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseSubscriptionsSectionTitle"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseSubscriptionsSectionTitle]" value="<?php echo $setting["values"]->phraseSubscriptionsSectionTitle; ?>" id="phraseSubscriptionsSectionTitle" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseFollowsSectionTitle">
    <div class="wpd-opt-name">
        <label for="phraseFollowsSectionTitle"><?php echo $setting["options"]["phraseFollowsSectionTitle"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseFollowsSectionTitle"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseFollowsSectionTitle]" value="<?php echo $setting["values"]->phraseFollowsSectionTitle; ?>" id="phraseFollowsSectionTitle" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseNotAvailable">
    <div class="wpd-opt-name">
        <label for="phraseNotAvailable"><?php echo $setting["options"]["phraseNotAvailable"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseNotAvailable"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseNotAvailable]" value="<?php echo $setting["values"]->phraseNotAvailable; ?>" id="phraseNotAvailable" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseNoData">
    <div class="wpd-opt-name">
        <label for="phraseNoData"><?php echo $setting["options"]["phraseNoData"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseNoData"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseNoData]" value="<?php echo $setting["values"]->phraseNoData; ?>" id="phraseNoData" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseFullInfo">
    <div class="wpd-opt-name">
        <label for="phraseFullInfo"><?php echo $setting["options"]["phraseFullInfo"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseFullInfo"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseFullInfo]" value="<?php echo $setting["values"]->phraseFullInfo; ?>" id="phraseFullInfo" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<h4 class="wpd-subtitle">
    <?php _e("Profile Tab", "wpdiscuz-comment-author-info") ?>
</h4>
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseProfileLastActivity">
    <div class="wpd-opt-name">
        <label for="phraseProfileLastActivity"><?php echo $setting["options"]["phraseProfileLastActivity"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseProfileLastActivity"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseProfileLastActivity]" value="<?php echo $setting["values"]->phraseProfileLastActivity; ?>" id="phraseProfileLastActivity" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseProfileComments">
    <div class="wpd-opt-name">
        <label for="phraseProfileComments"><?php echo $setting["options"]["phraseProfileComments"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseProfileComments"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseProfileComments]" value="<?php echo $setting["values"]->phraseProfileComments; ?>" id="phraseProfileComments" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseProfilePosts">
    <div class="wpd-opt-name">
        <label for="phraseProfilePosts"><?php echo $setting["options"]["phraseProfilePosts"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseProfilePosts"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseProfilePosts]" value="<?php echo $setting["values"]->phraseProfilePosts; ?>" id="phraseProfilePosts" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseProfileReceivedLikes">
    <div class="wpd-opt-name">
        <label for="phraseProfileReceivedLikes"><?php echo $setting["options"]["phraseProfileReceivedLikes"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseProfileReceivedLikes"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseProfileReceivedLikes]" value="<?php echo $setting["values"]->phraseProfileReceivedLikes; ?>" id="phraseProfileReceivedLikes" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseProfileReceivedDisikes">
    <div class="wpd-opt-name">
        <label for="phraseProfileReceivedDisikes"><?php echo $setting["options"]["phraseProfileReceivedDisikes"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseProfileReceivedDisikes"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseProfileReceivedDisikes]" value="<?php echo $setting["values"]->phraseProfileReceivedDisikes; ?>" id="phraseProfileReceivedDisikes" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseProfileAuthorBio">
    <div class="wpd-opt-name">
        <label for="phraseProfileAuthorBio"><?php echo $setting["options"]["phraseProfileAuthorBio"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseProfileAuthorBio"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseProfileAuthorBio]" value="<?php echo $setting["values"]->phraseProfileAuthorBio; ?>" id="phraseProfileAuthorBio" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseProfileCommentsStat">
    <div class="wpd-opt-name">
        <label for="phraseProfileCommentsStat"><?php echo $setting["options"]["phraseProfileCommentsStat"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseProfileCommentsStat"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseProfileCommentsStat]" value="<?php echo $setting["values"]->phraseProfileCommentsStat; ?>" id="phraseProfileCommentsStat" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseProfileCommentsStatAll">
    <div class="wpd-opt-name">
        <label for="phraseProfileCommentsStatAll"><?php echo $setting["options"]["phraseProfileCommentsStatAll"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseProfileCommentsStatAll"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseProfileCommentsStatAll]" value="<?php echo $setting["values"]->phraseProfileCommentsStatAll; ?>" id="phraseProfileCommentsStatAll" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseProfileCommentsStatApproved">
    <div class="wpd-opt-name">
        <label for="phraseProfileCommentsStatApproved"><?php echo $setting["options"]["phraseProfileCommentsStatApproved"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseProfileCommentsStatApproved"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseProfileCommentsStatApproved]" value="<?php echo $setting["values"]->phraseProfileCommentsStatApproved; ?>" id="phraseProfileCommentsStatApproved" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseProfileCommentsStatPending">
    <div class="wpd-opt-name">
        <label for="phraseProfileCommentsStatPending"><?php echo $setting["options"]["phraseProfileCommentsStatPending"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseProfileCommentsStatPending"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseProfileCommentsStatPending]" value="<?php echo $setting["values"]->phraseProfileCommentsStatPending; ?>" id="phraseProfileCommentsStatPending" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseProfileCommentsStatSpam">
    <div class="wpd-opt-name">
        <label for="phraseProfileCommentsStatSpam"><?php echo $setting["options"]["phraseProfileCommentsStatSpam"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseProfileCommentsStatSpam"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseProfileCommentsStatSpam]" value="<?php echo $setting["values"]->phraseProfileCommentsStatSpam; ?>" id="phraseProfileCommentsStatSpam" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseProfileCommentsStatTrashed">
    <div class="wpd-opt-name">
        <label for="phraseProfileCommentsStatTrashed"><?php echo $setting["options"]["phraseProfileCommentsStatTrashed"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseProfileCommentsStatTrashed"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseProfileCommentsStatTrashed]" value="<?php echo $setting["values"]->phraseProfileCommentsStatTrashed; ?>" id="phraseProfileCommentsStatTrashed" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<h4 class="wpd-subtitle">
    <?php _e("Activity Tab", "wpdiscuz-comment-author-info") ?>
</h4>
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseActivityInResponseTo">
    <div class="wpd-opt-name">
        <label for="phraseActivityInResponseTo"><?php echo $setting["options"]["phraseActivityInResponseTo"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseActivityInResponseTo"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseActivityInResponseTo]" value="<?php echo $setting["values"]->phraseActivityInResponseTo; ?>" id="phraseActivityInResponseTo" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<h4 class="wpd-subtitle">
    <?php _e("Votes Tab", "wpdiscuz-comment-author-info") ?>
</h4>
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseVotesInResponseTo">
    <div class="wpd-opt-name">
        <label for="phraseVotesInResponseTo"><?php echo $setting["options"]["phraseVotesInResponseTo"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseVotesInResponseTo"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseVotesInResponseTo]" value="<?php echo $setting["values"]->phraseVotesInResponseTo; ?>" id="phraseVotesInResponseTo" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<h4 class="wpd-subtitle">
    <?php _e("Subscriptions Tab", "wpdiscuz-comment-author-info") ?>
</h4>
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseSubscriptionsReply">
    <div class="wpd-opt-name">
        <label for="phraseSubscriptionsReply"><?php echo $setting["options"]["phraseSubscriptionsReply"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseSubscriptionsReply"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseSubscriptionsReply]" value="<?php echo $setting["values"]->phraseSubscriptionsReply; ?>" id="phraseSubscriptionsReply" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseSubscriptionsAllComment">
    <div class="wpd-opt-name">
        <label for="phraseSubscriptionsAllComment"><?php echo $setting["options"]["phraseSubscriptionsAllComment"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseSubscriptionsAllComment"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseSubscriptionsAllComment]" value="<?php echo $setting["values"]->phraseSubscriptionsAllComment; ?>" id="phraseSubscriptionsAllComment" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phraseSubscriptionsPost">
    <div class="wpd-opt-name">
        <label for="phraseSubscriptionsPost"><?php echo $setting["options"]["phraseSubscriptionsPost"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phraseSubscriptionsPost"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phraseSubscriptionsPost]" value="<?php echo $setting["values"]->phraseSubscriptionsPost; ?>" id="phraseSubscriptionsPost" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<h4 class="wpd-subtitle">
    <?php _e("Pagination", "wpdiscuz-comment-author-info") ?>
</h4>
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phrasePaginationFirst">
    <div class="wpd-opt-name">
        <label for="phrasePaginationFirst"><?php echo $setting["options"]["phrasePaginationFirst"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phrasePaginationFirst"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phrasePaginationFirst]" value="<?php echo $setting["values"]->phrasePaginationFirst; ?>" id="phrasePaginationFirst" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phrasePaginationPrevious">
    <div class="wpd-opt-name">
        <label for="phrasePaginationPrevious"><?php echo $setting["options"]["phrasePaginationPrevious"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phrasePaginationPrevious"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phrasePaginationPrevious]" value="<?php echo $setting["values"]->phrasePaginationPrevious; ?>" id="phrasePaginationPrevious" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phrasePaginationNext">
    <div class="wpd-opt-name">
        <label for="phrasePaginationNext"><?php echo $setting["options"]["phrasePaginationNext"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phrasePaginationNext"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phrasePaginationNext]" value="<?php echo $setting["values"]->phrasePaginationNext; ?>" id="phrasePaginationNext" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="phrasePaginationLast">
    <div class="wpd-opt-name">
        <label for="phrasePaginationLast"><?php echo $setting["options"]["phrasePaginationLast"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["phrasePaginationLast"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="text" name="<?php echo $setting["values"]->tabKey; ?>[phrasePaginationLast]" value="<?php echo $setting["values"]->phrasePaginationLast; ?>" id="phrasePaginationLast" style="margin:1px;padding:3px 5px; width:90%;"/>
    </div>
</div>
<!-- Option end -->