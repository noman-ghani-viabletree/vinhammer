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
    $items = $this->dbManager->getFollows($user->user_email, $perPage, $offset);
    if ($items && is_array($items)) {
        $itemsCount = $this->dbManager->getFollowsCount($user->user_email);
        $pCount = intval($itemsCount / $perPage);
        $pageCount = ($itemsCount % $perPage == 0) ? $pCount : $pCount + 1;
        $currentUser = wp_get_current_user();
        $isAdmin = current_user_can("manage_options");
        foreach ($items as $item) {
            $fId = $item->id;
            $fName = $item->user_name;
            $fPostId = $item->post_id;
            $fKey = $item->activation_key;
            $fEmail = $item->follower_email;
            $postedDate = $wpdiscuz->helper->getDate($item->follow_date);
            include WCAI_DIR_PATH . "/includes/layouts/follows/item.php";
        }
        include WCAI_DIR_PATH . "/includes/layouts/pagination.php";
        ?>
        <input type="hidden" class="wcai-page-number" value="<?php echo $page; ?>"/>
        <?php
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
