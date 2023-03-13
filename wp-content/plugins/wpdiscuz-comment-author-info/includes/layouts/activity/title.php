<?php
if (!defined("ABSPATH")) {
    exit();
}
ob_start();
?>
<li class='wcai-list-item' data-action='wcaiGetActivityPage'>
    <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" style="vertical-align: sub;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
    <span><?php print $this->options->phraseActivitySectionTitle ? __($this->options->phraseActivitySectionTitle, "wpdiscuz-comment-author-info") : __("Activity", "wpdiscuz-comment-author-info"); ?></span>
    <input class='wcai-rel' type='hidden' value='wcai-content-item-2'/>
</li>
<?php
$html .= ob_get_contents();
ob_end_clean();
