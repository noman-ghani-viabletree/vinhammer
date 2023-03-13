<?php
if (!defined("ABSPATH")) {
    exit();
}
ob_start();
$action = "wcaiGetVotesPage";
if ($items && is_array($items)) {
    $page = 0;
    $lrItemsCount = 3;
    $pCount = intval($itemsCount / $perPage);
    $pageCount = ($itemsCount % $perPage == 0) ? $pCount : $pCount + 1;
    foreach ($items as $item) {
        $itemId = $item->comment_id;
        $voteType = $item->vote_type;
        $item = get_comment($itemId);
        if ($item) {
            if ($voteType === "1") {
                $voteColor = "#118b26";
                $voteIcon = "fa-thumbs-up";
            } else {
                $voteColor = "#e8484a";
                $voteIcon = "fa-thumbs-down";
            }
            include WCAI_DIR_PATH . "/includes/layouts/votes/item.php";
        }
    }
    include WCAI_DIR_PATH . "/includes/layouts/pagination.php";
} else {
    ?>
    <div class='wcai-item'>
        <?php print $this->options->phraseNoData ? __($this->options->phraseNoData, "wpdiscuz-comment-author-info") : __("No Data", "wpdiscuz-comment-author-info"); ?>
    </div>
    <?php
}
$html .= ob_get_contents();
ob_end_clean();
