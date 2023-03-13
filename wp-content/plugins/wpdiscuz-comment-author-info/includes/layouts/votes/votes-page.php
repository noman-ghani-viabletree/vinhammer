<?php
if (!defined("ABSPATH")) {
    exit();
}
ob_start();
$page = isset($_POST["page"]) ? intval($_POST["page"]) : 0;
$commentId = isset($_POST["commentId"]) ? $_POST["commentId"] : 0;
$action = isset($_POST["action"]) ? $_POST["action"] : "";
$comment = get_comment($commentId);
if ($comment && $action) {
    $user = get_user_by("ID", $comment->user_id);
    if (!$user && $wpdiscuz->options->login["isUserByEmail"]) {
        $user = get_user_by("email", $comment->comment_author_email);
    }
    $lrItemsCount = 3;
    $perPage = $this->options->perPage;
    $offset = $page * $perPage;
    $items = $this->dbManager->getGivenLikesDislikes($user->ID, $perPage, $offset);
    if ($items && is_array($items)) {
        $itemsCount = $this->dbManager->getGivenLikesDislikesCount($user->ID);
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
}
$html .= ob_get_contents();
ob_end_clean();
