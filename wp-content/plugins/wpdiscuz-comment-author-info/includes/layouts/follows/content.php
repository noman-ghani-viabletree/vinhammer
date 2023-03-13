<?php
if (!defined("ABSPATH")) {
    exit();
}
ob_start();
$action = "wcaiGetFollowsPage";
if ($items && is_array($items)) {
    $page = 0;
    $lrItemsCount = 3;
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
    <input type="hidden" class="wcai-page-number" value="0"/>
    <?php
} else {
    ?>
    <div class='wcai-item'>
        <?php print $this->options->phraseNoData ? __($this->options->phraseNoData, "wpdiscuz-comment-author-info") : __("No Data", "wpdiscuz-comment-author-info"); ?>
    </div>
    <?php
}
$html .= ob_get_contents();
ob_end_clean();
