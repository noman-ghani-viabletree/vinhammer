<?php
if (!defined("ABSPATH")) {
    exit();
}
?>
<div class="wcai-item">
    <div class="wcai-item-left">
        <?php if($voteIcon === "fa-thumbs-down") { ?>
            <svg xmlns="https://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" color="<?php echo $voteColor; ?>" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-down"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path></svg>
        <?php } else { ?>
            <svg xmlns="https://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" color="<?php echo $voteColor; ?>" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
        <?php } ?>
    </div>
    <div class="wcai-item-right">
        <div class="wcai-item-link wcai-comment-meta">
            <i class="fas fa-user"></i> 
            <?php echo $item->comment_author; ?> &nbsp; 
            <i class="fas fa-calendar-alt"></i> 
            <?php echo $wpdiscuz->helper->getCommentDate($item); ?>
        </div>
        <div class="wcai-item-link wcai-comment-item-link">
            <a class="wcai-comment-link" href="<?php echo get_comment_link($item); ?>" target="_blank">
                <?php echo wp_trim_words($item->comment_content, 20, "&hellip;"); ?>
            </a>
        </div>
        <div class="wcai-item-link wcai-post-item-link">
            <span><?php print $this->options->phraseVotesInResponseTo ? __($this->options->phraseVotesInResponseTo, "wpdiscuz-comment-author-info") : __("In Response To:", "wpdiscuz-comment-author-info"); ?></span>
            <a class="wcai-post-link" href="<?php echo get_permalink($item->comment_post_ID); ?>" target="_blank">
                <?php echo get_the_title($item->comment_post_ID); ?>
            </a>
        </div>
    </div>
    <div class="wcai-clear"></div>
</div>