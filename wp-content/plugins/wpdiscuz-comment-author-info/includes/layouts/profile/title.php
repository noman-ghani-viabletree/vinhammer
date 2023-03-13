<?php
if (!defined("ABSPATH")) {
    exit();
}

ob_start();
?>
<li class='wcai-list-item'>
    <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" style="vertical-align: sub;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
    <span><?php print $this->options->phraseProfileSectionTitle ? __($this->options->phraseProfileSectionTitle, "wpdiscuz-comment-author-info") : __("Profile", "wpdiscuz-comment-author-info"); ?></span>
    <input class='wcai-rel' type='hidden' value='wcai-content-item-1'/>
</li>
<?php
$html .= ob_get_contents();
ob_end_clean();
