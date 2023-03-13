<?php
if (!defined("ABSPATH")) {
    exit();
}
?>
<div class='wcai-item'>
    <div class='wcai-item-link wcai-comment-meta'>
        <i class='fas fa-user'></i>
        <?php echo esc_html($item->comment_author); ?> &nbsp; 
        <i class='fas fa-calendar-alt'></i> 
        <?php echo $wpdiscuz->helper->getCommentDate($item); ?>
    </div>
    <div class='wcai-item-link wcai-comment-item-link'>
        <a class='wcai-comment-link' href='<?php echo get_comment_link($item); ?>' target='_blank'>
            <?php echo get_comment_excerpt($item->comment_ID); ?>
        </a>
    </div>
    <div class='wcai-item-link wcai-post-item-link'>
        <span><?php print $this->options->phraseActivityInResponseTo ? __($this->options->phraseActivityInResponseTo, "wpdiscuz-comment-author-info") : __("In Response To:", "wpdiscuz-comment-author-info"); ?></span>
        <a class='wcai-post-link' href='<?php echo get_permalink($post); ?>' target='_blank'>
            <?php echo $post->post_title; ?>
        </a>
    </div>
</div>