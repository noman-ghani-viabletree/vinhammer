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
    $lrItemsCount = 3;
    $perPage = $this->options->perPage;
    $offset = $page * $perPage;
    $args = ["number" => $perPage, "status" => "approve", "offset" => $offset];
    $user = get_user_by("ID", $comment->user_id);
    if (!$user && $wpdiscuz->options->login["isUserByEmail"]) {
        $user = get_user_by("email", $comment->comment_author_email);
    }

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
            $allComments = get_comments($args);
            $pCount = intval($allComments / $perPage);
            $pageCount = ($allComments % $perPage == 0) ? $pCount : $pCount + 1;
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
}
$html .= ob_get_contents();
ob_end_clean();
