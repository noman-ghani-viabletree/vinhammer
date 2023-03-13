<?php
if (!defined("ABSPATH")) {
    exit();
}
ob_start();
$profileRightStyle = is_rtl() ? "margin-right:0;" : "margin-left:0;";
?>
<div class="wcai-profile-wrap">
    <div class="wcai-profile-right" style="<?php echo $profileRightStyle; ?>">
        <div class="wcai-profile-right-wrap">
            <div class="wcai-profile-head">
                <?php
                if ($this->options->profileShowWebUrl && $comment->comment_author_url) {
                    ?>
                    <a href="<?php echo esc_url($comment->comment_author_url) ?>" rel="nofollow">
                        <i class="fas fa-globe"></i> 
                    </a>
                    <?php
                }
                ?>
                <?php if ($this->options->profileShowDisplayName) { ?>
                    <span><?php echo $comment->comment_author; ?></span>
                <?php } ?>
                <div class="wcai-profile-la">
                    <?php
                    $lastComments = get_comments(["author_email" => $comment->comment_author_email, "number" => 1]);
                    $lastComment = $lastComments[0];
                    $phraseProfileLastActivity = $this->options->phraseProfileLastActivity ? __($this->options->phraseProfileLastActivity, "wpdiscuz-comment-author-info") : __("Last Activity:", "wpdiscuz-comment-author-info");
                    ?>
                    <?php echo $phraseProfileLastActivity; ?> <?php echo $wpdiscuz->helper->getCommentDate($lastComment); ?>
                </div>
            </div>
            <div class="wcai-profile-stat">
                <?php $phraseProfileComments = $this->options->phraseProfileComments ? __($this->options->phraseProfileComments, "wpdiscuz-comment-author-info") : __("Comments", "wpdiscuz-comment-author-info"); ?>
                <div class="wcai-profile-stat-box" title="<?php echo $phraseProfileComments; ?>">
                    <div class="wcai-profile-stat-box-icon"><i class="far fa-comment"></i></div>
                    <div class="wcai-profile-stat-box-val" title="<?php print $commentStatistic["approved"] ? $commentStatistic["approved"] : 0; ?>"><?php echo $commentStatistic["approved"] ? $wpdiscuz->helper->getNumber($commentStatistic["approved"]) : 0; ?></div>
                </div>
                <?php $phraseProfilePosts = $this->options->phraseProfilePosts ? __($this->options->phraseProfilePosts, "wpdiscuz-comment-author-info") : __("Posts", "wpdiscuz-comment-author-info"); ?>
                <div class="wcai-profile-stat-box" title="<?php echo $phraseProfilePosts; ?>">
                    <div class="wcai-profile-stat-box-icon"><i class="fas fa-pencil-alt"></i></div>
                    <div class="wcai-profile-stat-box-val" title="0">0</div>
                </div>                
                <?php if ($wpdiscuz->options->thread_layouts['showVotingButtons']) { ?>
                    <?php $phraseProfileReceivedLikes = $this->options->phraseProfileReceivedLikes ? __($this->options->phraseProfileReceivedLikes, "wpdiscuz-comment-author-info") : __("Received Likes", "wpdiscuz-comment-author-info"); ?>
                    <div class="wcai-profile-stat-box" style="color:#118B26;" title="<?php echo $phraseProfileReceivedLikes; ?>">
                        <div class="wcai-profile-stat-box-icon"><i class="far fa-thumbs-up"></i></div>
                        <div class="wcai-profile-stat-box-val" style="background:#edfff9;" title="<?php echo $commentStatistic["likes"]; ?>"><?php echo $wpdiscuz->helper->getNumber($commentStatistic["likes"]); ?></div>
                    </div>
                    <?php if ($wpdiscuz->options->thread_layouts['enableDislikeButton']) { ?>
                        <?php $phraseProfileReceivedDisikes = $this->options->phraseProfileReceivedDisikes ? __($this->options->phraseProfileReceivedDisikes, "wpdiscuz-comment-author-info") : __("Received Disikes", "wpdiscuz-comment-author-info"); ?>
                        <div class="wcai-profile-stat-box" style="color:#E8484A;" title="<?php echo $phraseProfileReceivedDisikes; ?>">
                            <div class="wcai-profile-stat-box-icon"><i class="far fa-thumbs-down"></i></div>
                            <div class="wcai-profile-stat-box-val" style="background:#fff4f4;" title="<?php echo $commentStatistic["dislikes"]; ?>"><?php echo $wpdiscuz->helper->getNumber($commentStatistic["dislikes"]); ?></div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <div class="wcai-clear"></div>
            </div>
        </div>
    </div>
    <div class="wcai-clear"></div>
    <?php if ($this->options->sections) { ?>
        <?php $phraseFullInfo = $this->options->phraseFullInfo ? __($this->options->phraseFullInfo, "wpdiscuz-comment-author-info") : __("View full info", "wpdiscuz-comment-author-info"); ?>
        <div class="wcai-full-info"><a href="#" class="wcai-finfo wcai-not-clicked"><?php echo $phraseFullInfo; ?></a></div>
        <?php } ?>
</div>
<?php
$html .= ob_get_contents();
ob_end_clean();
