<?php
if (!defined("ABSPATH")) {
    exit();
}
ob_start();
?>
<li class='wcai-list-item' data-action='wcaiGetFollowsPage'>
    <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" style="vertical-align: sub;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-rss"><path d="M4 11a9 9 0 0 1 9 9"></path><path d="M4 4a16 16 0 0 1 16 16"></path><circle cx="5" cy="19" r="1"></circle></svg>
    <span><?php print $this->options->phraseFollowsSectionTitle ? __($this->options->phraseFollowsSectionTitle, "wpdiscuz-comment-author-info") : __("Follows", "wpdiscuz-comment-author-info"); ?></span>
    <input class='wcai-rel' type='hidden' value='wcai-content-item-5'/>
</li>
<?php
$html .= ob_get_contents();
ob_end_clean();
