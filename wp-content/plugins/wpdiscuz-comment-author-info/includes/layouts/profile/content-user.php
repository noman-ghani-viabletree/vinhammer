<?php
if (!defined("ABSPATH")) {
    exit();
}
ob_start();
$profileRightStyle = is_rtl() ? "margin-right:0;" : "margin-left:0;";
?>
<div class="wcai-profile-wrap">
    <?php if ($avatar = get_avatar($user->user_email, 96)) { ?>
        <?php $profileRightStyle = ""; ?>
        <div class="wcai-profile-left">
            <?php echo $avatar; ?>
            <div class="wcai-profile-nickname">
                <?php
                if ($this->options->profileShowNickName) {
                    echo "@{$user->nickname}";
                }
                ?>
            </div>

            <?php if ($this->options->profileShowMycredData && (function_exists("mycred_get_users_badges") || (function_exists("mycred_get_users_rank")))) { ?>
                <?php if (function_exists("mycred_get_badge") && ($badges = mycred_get_users_badges($user->ID))) { ?>
                    <div class='wcai-profile-mycreddata'>
                        <div class='wcai-mycred-badges-wrap'>
                            <?php
                            if (function_exists("mycred_get_users_badges")) { //User Badges
                                $wpcusers_badges = mycred_get_users_badges($user->ID);
                                if (!empty($wpcusers_badges)) {
                                    echo "<div class='row mycred-users-badges'><div class='col-xs-12'>";
                                    foreach ($wpcusers_badges as $badge_id => $level) {
                                        if (function_exists("mycred_get_badge")) {
                                            $badge = mycred_get_badge($badge_id, $level);
                                            if ($badge->level_image !== false)
                                                echo apply_filters("mycred_the_badge", $badge->level_image, $badge_id, $badge, $user->ID);
                                        } else {
                                            $imageKey = $level > 0 ? "level_image" . $level : "main_image";
                                            $imgSrc = get_post_meta($badge_id, $imageKey, true);
                                            if ($imgSrc) {
                                                $badge_image_url = get_post_meta($badge_id, $imageKey, true);
                                                echo "<span class='wcai-mycred-badge-title'><img src='" . $badge_image_url . "' class='mycred-badge earned' alt='" . get_the_title($badge_id) . "'  title='" . get_the_title($badge_id) . "' /></span>";
                                            }
                                        }
                                    }
                                    echo "</div></div>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if (function_exists("mycred_get_users_rank") && ($rank = mycred_get_users_rank($user->ID))) { ?>
                    <div class='wcai-mycred-rank-wrap'>
                        <?php if (is_object($rank)) { ?>
                            <?php
                            if ($rank->has_logo) {
                                if (is_object($rank)) {
                                    $src = $rank->logo_url;
                                    echo "<img src='" . $src . "' title='" . $rank->title . "' class='mycred-rank wp-post-image' alt='" . $rank->title . "'/>";
                                } else {
                                    echo mycred_get_users_rank($user->ID, "logo", "post-thumbnail", ["class" => "mycred-rank"]);
                                }
                            }
                            ?>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } ?>

        </div>
    <?php } ?>
    <div class="wcai-profile-right" style="<?php echo $profileRightStyle; ?>">
        <div class="wcai-profile-right-wrap">
            <div class="wcai-profile-head">
                <?php
                if ($this->options->profileShowWebUrl && $user->user_url) {
                    ?>
                    <a href="<?php echo esc_url($user->user_url) ?>" rel="nofollow">
                        <i class="fas fa-globe"></i> 
                    </a>
                    <?php
                }
                ?>
                <?php
                $commentAuthorUrl = "";
                $profileUrl = "";
                $url = "";
                $commentAuthorUrl = ("http://" == $user->user_url) ? "" : $user->user_url;
                $commentAuthorUrl = esc_url($commentAuthorUrl, ["http", "https"]);
                $commentAuthorUrl = apply_filters("get_comment_author_url", $commentAuthorUrl, $user->ID, $comment);
                $profileUrl = get_author_posts_url($user->ID);
                $profileUrl = $wpdiscuz->helper->getProfileUrl($profileUrl, $user);
                if ($wpdiscuz->options->login["enableProfileURLs"]) {
                    $url = ($profileUrl) ? $profileUrl : $commentAuthorUrl;
                }
                ?>
                <?php if ($this->options->profileShowDisplayName) { ?>
                    <?php if ($url) { ?>
                        <a href="<?php echo esc_url($url) ?>">
                            <span><?php echo $user->display_name; ?></span>
                        </a>
                    <?php } else { ?>
                        <span><?php echo $user->display_name; ?></span>
                    <?php } ?>
                <?php } ?>
                <div class="wcai-profile-la">
                    <?php
                    $lastComments = get_comments(["user_id" => $user->ID, "number" => 1]);
                    $lastComment = $lastComments[0];
                    $phraseProfileLastActivity = $this->options->phraseProfileLastActivity ? __($this->options->phraseProfileLastActivity, "wpdiscuz-comment-author-info") : __("Last Activity:", "wpdiscuz-comment-author-info");
                    ?>
                    <?php echo $phraseProfileLastActivity; ?> <?php echo $wpdiscuz->helper->getCommentDate($lastComment); ?>
                </div>
            </div>
            <div class="wcai-profile-stat">
                <?php $phraseProfileComments = $this->options->phraseProfileComments ? __($this->options->phraseProfileComments, "wpdiscuz-comment-author-info") : __("Comments", "wpdiscuz-comment-author-info"); ?>
                <div class="wcai-profile-stat-box" title="<?php echo $phraseProfileComments; ?>">
                    <div class="wcai-profile-stat-box-icon"><svg xmlns="https://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg></div>
                    <div class="wcai-profile-stat-box-val" title="<?php echo $commentStatistic["approved"] ? $commentStatistic["approved"] : 0; ?>"><?php echo $commentStatistic["approved"] ? $wpdiscuz->helper->getNumber($commentStatistic["approved"]) : 0; ?></div>
                </div>
                <?php $phraseProfilePosts = $this->options->phraseProfilePosts ? __($this->options->phraseProfilePosts, "wpdiscuz-comment-author-info") : __("Posts", "wpdiscuz-comment-author-info"); ?>
                <div class="wcai-profile-stat-box" title="<?php echo $phraseProfilePosts; ?>">
                    <div class="wcai-profile-stat-box-icon"><svg xmlns="https://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></div>
                    <div class="wcai-profile-stat-box-val" title="<?php echo count_user_posts($user->ID); ?>"><?php echo $wpdiscuz->helper->getNumber(count_user_posts($user->ID)); ?></div>
                </div>
                <?php if ($wpdiscuz->options->thread_layouts['showVotingButtons']) { ?>
                    <?php $phraseProfileReceivedLikes = $this->options->phraseProfileReceivedLikes ? __($this->options->phraseProfileReceivedLikes, "wpdiscuz-comment-author-info") : __("Received Likes", "wpdiscuz-comment-author-info"); ?>
                    <div class="wcai-profile-stat-box" style="color:#118B26;" title="<?php echo $phraseProfileReceivedLikes; ?>">
                        <div class="wcai-profile-stat-box-icon"><svg xmlns="https://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg></div>
                        <div class="wcai-profile-stat-box-val" style="background:#edfff9;" title="<?php echo $commentStatistic["likes"]; ?>"><?php echo $wpdiscuz->helper->getNumber($commentStatistic["likes"]); ?></div>
                    </div>
                    <?php if ($wpdiscuz->options->thread_layouts['enableDislikeButton']) { ?>
                        <?php $phraseProfileReceivedDisikes = $this->options->phraseProfileReceivedDisikes ? __($this->options->phraseProfileReceivedDisikes, "wpdiscuz-comment-author-info") : __("Received Disikes", "wpdiscuz-comment-author-info"); ?>
                        <div class="wcai-profile-stat-box" style="color:#E8484A;" title="<?php echo $phraseProfileReceivedDisikes; ?>">
                            <div class="wcai-profile-stat-box-icon"><svg xmlns="https://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-down"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path></svg></div>
                            <div class="wcai-profile-stat-box-val" style="background:#fff4f4;" title="<?php echo $commentStatistic["dislikes"]; ?>"><?php echo $wpdiscuz->helper->getNumber($commentStatistic["dislikes"]); ?></div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <div class="wcai-clear"></div>
            </div>
        </div>
    </div>
    <div class="wcai-clear"></div>
    <div class="wcai-profile-body">
        <?php if ($this->options->profileShowBio) { ?>
            <div class="wcai-profile-title-r"><?php print $this->options->phraseProfileAuthorBio ? __($this->options->phraseProfileAuthorBio, "wpdiscuz-comment-author-info") : __("Comment Author Biography", "wpdiscuz-comment-author-info"); ?></div>
            <div class="wcai-profile-field-val">
                <?php $phraseNotAvailable = $this->options->phraseNotAvailable ? __($this->options->phraseNotAvailable, "wpdiscuz-comment-author-info") : __("Not available", "wpdiscuz-comment-author-info"); ?>
                <?php print ($bio = get_user_meta($user->ID, "description", true)) ? $bio : "<p style='text-align:center'>" . $phraseNotAvailable . "</p>"; ?>
            </div>
        <?php } ?>
        <?php if ($this->options->profileShowStatistics && current_user_can("delete_others_pages")) { ?>
            <?php $phraseProfileCommentsStat = $this->options->phraseProfileCommentsStat ? __($this->options->phraseProfileCommentsStat, "wpdiscuz-comment-author-info") : __("Comments Statistic", "wpdiscuz-comment-author-info"); ?>
            <div class="wcai-profile-title-l"><?php echo $phraseProfileCommentsStat; ?></div>
            <div class="wcai-stat-box">
                <?php $phraseProfileCommentsStatAll = $this->options->phraseProfileCommentsStatAll ? __($this->options->phraseProfileCommentsStatAll, "wpdiscuz-comment-author-info") : __("All", "wpdiscuz-comment-author-info"); ?>
                <div class="wcai-stat-box-icon"><?php echo $phraseProfileCommentsStatAll; ?></div>
                <div class="wcai-stat-box-val" title="<?php echo $commentStatistic["all"]; ?>"><?php echo $wpdiscuz->helper->getNumber($commentStatistic["all"]); ?></div>
            </div>
            <div class="wcai-stat-box">
                <?php $phraseProfileCommentsStatApproved = $this->options->phraseProfileCommentsStatApproved ? __($this->options->phraseProfileCommentsStatApproved, "wpdiscuz-comment-author-info") : __("Approved", "wpdiscuz-comment-author-info"); ?>
                <div class="wcai-stat-box-icon"><?php echo $phraseProfileCommentsStatApproved; ?></div>
                <div class="wcai-stat-box-val" title="<?php echo $commentStatistic["approved"]; ?>"><?php echo $wpdiscuz->helper->getNumber($commentStatistic["approved"]); ?></div>
            </div>
            <div class="wcai-stat-box">
                <?php $phraseProfileCommentsStatPending = $this->options->phraseProfileCommentsStatPending ? __($this->options->phraseProfileCommentsStatPending, "wpdiscuz-comment-author-info") : __("Pending", "wpdiscuz-comment-author-info") ?>
                <div class="wcai-stat-box-icon"><?php echo $phraseProfileCommentsStatPending; ?></div>
                <div class="wcai-stat-box-val" title="<?php echo $commentStatistic["pending"]; ?>"><?php echo $wpdiscuz->helper->getNumber($commentStatistic["pending"]); ?></div>
            </div>
            <div class="wcai-stat-box">
                <?php $phraseProfileCommentsStatSpam = $this->options->phraseProfileCommentsStatSpam ? __($this->options->phraseProfileCommentsStatSpam, "wpdiscuz-comment-author-info") : __("Spam", "wpdiscuz-comment-author-info") ?>
                <div class="wcai-stat-box-icon"><?php echo $phraseProfileCommentsStatSpam; ?></div>
                <div class="wcai-stat-box-val" title="<?php echo $commentStatistic["spam"]; ?>"><?php echo $wpdiscuz->helper->getNumber($commentStatistic["spam"]); ?></div>
            </div>
            <div class="wcai-stat-box">
                <?php $phraseProfileCommentsStatTrashed = $this->options->phraseProfileCommentsStatTrashed ? __($this->options->phraseProfileCommentsStatTrashed, "wpdiscuz-comment-author-info") : __("Trashed", "wpdiscuz-comment-author-info") ?>
                <div class="wcai-stat-box-icon"><?php echo $phraseProfileCommentsStatTrashed; ?></div>
                <div class="wcai-stat-box-val" title="<?php echo $commentStatistic["trash"]; ?>"><?php echo $wpdiscuz->helper->getNumber($commentStatistic["trash"]); ?></div>
            </div>
        <?php } ?>
        <div class="wcai-clear"></div>
    </div>
</div>
<?php
$html .= ob_get_contents();
ob_end_clean();
