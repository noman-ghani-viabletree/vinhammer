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
    $items = $this->dbManager->getSubscriptions($user->user_email, $perPage, $offset);
    if ($items && is_array($items)) {
        $itemsCount = $this->dbManager->getSubscriptionsCount($user->user_email);
        $pCount = intval($itemsCount / $perPage);
        $pageCount = ($itemsCount % $perPage == 0) ? $pCount : $pCount + 1;
        $currentUser = wp_get_current_user();
        $isAdmin = current_user_can("manage_options");
        foreach ($items as $item) {
            $sId = $item->id;
            $sEmail = $item->email;
            $scId = $item->subscribtion_id;
            $sPostId = $item->post_id;
            $sType = $item->subscribtion_type;
            $sKey = $item->activation_key;

            if ($sType === WpdiscuzCore::SUBSCRIPTION_COMMENT) {
                $object = get_comment($scId);
                $link = get_comment_link($scId);
                $author = $object->comment_author;
                $postedDate = $wpdiscuz->helper->getCommentDate($object);
                $content = wp_trim_words($object->comment_content, 20, "&hellip;");
                $sTypeInfo = $this->options->phraseSubscriptionsReply ? __($this->options->phraseSubscriptionsReply, "wpdiscuz-comment-author-info") : __("subscribed to replies to this comment", "wpdiscuz-comment-author-info");
            } else if ($sType === WpdiscuzCore::SUBSCRIPTION_ALL_COMMENT) {
                $object = get_post($sPostId);
                $link = get_permalink($sPostId);
                $postAuthor = get_user_by("ID", $object->post_author);
                $author = $postAuthor->display_name ? $postAuthor->display_name : $postAuthor->user_login;
                $postedDate = $wpdiscuz->helper->getPostDate($object);
                $content = $object->post_title;
                $sTypeInfo = $this->options->phraseSubscriptionsAllComment ? __($this->options->phraseSubscriptionsAllComment, "wpdiscuz-comment-author-info") : __("subscribed to replies to own comments", "wpdiscuz-comment-author-info");
            } else {
                $object = get_post($sPostId);
                $link = get_permalink($sPostId);
                $postAuthor = get_user_by("ID", $object->post_author);
                $author = $postAuthor->display_name ? $postAuthor->display_name : $postAuthor->user_login;
                $postedDate = $wpdiscuz->helper->getPostDate($object);
                $content = $object->post_title;
                $sTypeInfo = $this->options->phraseSubscriptionsPost ? __($this->options->phraseSubscriptionsPost, "wpdiscuz-comment-author-info") : __("subscribed to all follow-up comments of this post", "wpdiscuz-comment-author-info");
            }

            if ($object && !is_wp_error($object)) {
                include WCAI_DIR_PATH . "/includes/layouts/subscriptions/item.php";
            }
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
