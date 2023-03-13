<?php
if (!defined("ABSPATH")) {
    exit();
}
ob_start();
?>
<li class='wcai-list-item' data-action='wcaiGetVotesPage'>
    <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" style="vertical-align: sub;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
    <span><?php print $this->options->phraseVotesSectionTitle ? __($this->options->phraseVotesSectionTitle, "wpdiscuz-comment-author-info") : __("Votes", "wpdiscuz-comment-author-info"); ?></span>
    <input class='wcai-rel' type='hidden' value='wcai-content-item-3'/>
</li>
<?php
$html .= ob_get_contents();
ob_end_clean();
