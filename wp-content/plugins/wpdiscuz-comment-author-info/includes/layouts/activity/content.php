<?php
if (!defined("ABSPATH")) {
    exit();
}
ob_start();
$action = "wcaiGetActivityPage";
$perPage = $this->options->perPage;
$lrItemsCount = 3;
$page = 0;
$args = ["user_id" => 0, "author_email" => "", "number" => $perPage, "status" => "approve"];
if (isset($user->ID)) {
    $args["user_id"] = $user->ID;
} else if (isset($comment->comment_author_email)) {
    $args["author_email"] = $comment->comment_author_email;
}
if ($args["user_id"] || $args["author_email"]) {
    $items = get_comments($args);
    if ($items && is_array($items)) {
        $args["number"] = null;
        $args["count"] = true;
        $itemsCount = get_comments($args);
        $pCount = intval($itemsCount / $perPage);
        $pageCount = ($itemsCount % $perPage == 0) ? $pCount : $pCount + 1;
        foreach ($items as $item) {
            $post = get_post($item->comment_post_ID);
            include WCAI_DIR_PATH . "/includes/layouts/activity/item.php";
        }
        include WCAI_DIR_PATH . "/includes/layouts/pagination.php";
    }
} else {
    ?>
    <div class='wcai-item'>
        <?php print $this->options->phraseNoData ? __($this->options->phraseNoData, "wpdiscuz-comment-author-info") : __("No Data", "wpdiscuz-comment-author-info"); ?>
    </div>
    <?php
}
$html .= ob_get_contents();
ob_end_clean();
