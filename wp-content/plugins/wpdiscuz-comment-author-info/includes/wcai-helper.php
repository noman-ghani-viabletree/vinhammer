<?php

if (!defined("ABSPATH")) {
    exit();
}

class WCAIHelper implements WCAIConstants {

    private $options;
    private $dbManager;
    private $currentUser;

    public function __construct($options, $dbManager) {
        $this->options = $options;
        $this->dbManager = $dbManager;
    }

    public function addInfoIcon($output, $comment, $user, $currentUser) {
        if ($this->options->sections && ($this->isAllowedRole($currentUser) || (!WpdiscuzCommentAuthorInfo::$IS_LOGGED_IN && $this->options->showForGuests))) {
            $this->currentUser = $currentUser;
            $output .= "<span wpd-tooltip='" . __("Comment author information", "wpdiscuz-comment-author-info") . "' wpd-tooltip-size='medium'><i id='wcai-comment_{$comment->comment_ID}' class='fas fa-info wpf-cta wcai-info wcai-not-clicked'></i></span>";
        }
        return $output;
    }

    public function addLityPopup() {
        $wpdiscuz = wpDiscuz();
        if ($wpdiscuz->isWpdiscuzLoaded) {
            $html = "<a id='wcaiInfoAnchor' style='display:none;' rel='#wcaiInfo' data-wcai-lity>Comment Author Info</a>";
            $html .= "<div id='wcaiInfo' style='overflow:auto;background:#FDFDF6;padding:20px;width:600px;max-width:100%;border-radius:6px' class='lity-hide'></div>";
            $html .= "<div id='wcaiInfoShort' class='wcaiInfoShort'></div>";
            $html .= "<style>";
            $html .= ".wcaiInfoShort .wcai-wrapper .wcai-full-info a.wcai-finfo{background-color:{$wpdiscuz->options->thread_styles["primaryColor"]};}";
            $html .= ".wcai-wrapper .wcai-list-item.wcai-active{border-bottom: 3px solid {$wpdiscuz->options->thread_styles["primaryColor"]};}";
            $html .= "</style>";
            echo $html;
        }
    }

    public function wcaiGetInfo() {
        $wpdiscuz = wpDiscuz();
        $response = "";
        if (isset($_POST["commentId"]) && ($commentId = intval($_POST["commentId"])) && ($comment = get_comment($commentId))) {
            $isProfileActive = in_array(self::SECTION_PROFILE, $this->options->sections);
            $isActivityActive = in_array(self::SECTION_ACTIVITY, $this->options->sections);
            $isVotesActive = in_array(self::SECTION_VOTES, $this->options->sections);
            $isSubscriptionsActive = in_array(self::SECTION_SUBSCRIPTION, $this->options->sections);
            $isFollowsActive = in_array(self::SECTION_FOLLOWS, $this->options->sections);

            $user = get_user_by("ID", $comment->user_id);
            if (!$user && $wpdiscuz->options->login["isUserByEmail"]) {
                $user = get_user_by("email", $comment->comment_author_email);
            }
            $response .= "<div class='wcai-wrapper'>";
            $response .= "<ul class='wcai-list'>";
            if ($isProfileActive) {
                $response .= $this->getProfileTitleHtml();
            }
            if ($isActivityActive) {
                $response .= $this->getActivityTitleHtml();
            }
            if ($user && $user->ID) {
                if ($isVotesActive) {
                    $response .= $this->getVotesTitleHtml();
                }
                if ($isSubscriptionsActive) {
                    $response .= $this->getSubscriptionsTitleHtml();
                }
                if ($isFollowsActive) {
                    $response .= $this->getFollowsTitleHtml();
                }
            }
            $response .= "</ul>";
            $response .= "<div class='wcai-content'>";
            $isFirstTab = true;
            if ($isProfileActive) {
                $response .= $this->getProfileContentHtml($wpdiscuz, $user, $comment, $isFirstTab);
                $isFirstTab = false;
            }
            if ($isActivityActive) {
                $response .= $this->getActivityContentHtml($wpdiscuz, $user, $comment, $isFirstTab);
                $isFirstTab = false;
            }
            if ($user && $user->ID) {
                if ($isVotesActive) {
                    $response .= $this->getVotesContentHtml($wpdiscuz, $user, $comment, $isFirstTab);
                    $isFirstTab = false;
                }
                if ($isSubscriptionsActive) {
                    $response .= $this->getSubscriptionsContentHtml($wpdiscuz, $user, $comment, $isFirstTab);
                    $isFirstTab = false;
                }
                if ($isFollowsActive) {
                    $response .= $this->getFollowsContentHtml($wpdiscuz, $user, $comment, $isFirstTab);
                    $isFirstTab = false;
                }
            }
            $response .= "</div>";
            $response .= "<input type='hidden' class='wcai-comment-id' value='{$comment->comment_ID}'/>";
            $response .= "<input type='hidden' class='wcai-info-type' value='wcaiInfo'/>";
            $response .= "</div>";
        }
        wp_die($response);
    }

    public function wcaiGetShortInfo() {
        $response = "";
        if (isset($_POST["commentId"]) && ($commentId = intval($_POST["commentId"])) && ($comment = get_comment($commentId))) {
            $wpdiscuz = wpDiscuz();
            $user = get_user_by("ID", $comment->user_id);
            if (!$user && $wpdiscuz->options->login["isUserByEmail"]) {
                $user = get_user_by("email", $comment->comment_author_email);
            }
            $response .= "<div class='wcai-wrapper'>";
            $response .= "<div class='wcai-content'>";
            $response .= $this->getProfileShortContentHtml($wpdiscuz, $user, $comment);
            $response .= "</div>";
            $response .= "<input type='hidden' class='wcai-comment-id' value='{$comment->comment_ID}'/>";
            $response .= "<input type='hidden' class='wcai-info-type' value='wcaiInfoShort'/>";
            $response .= "</div>";
        }
        wp_die($response);
    }

    private function getProfileTitleHtml() {
        $html = "";
        include_once WCAI_DIR_PATH . "/includes/layouts/profile/title.php";
        return $html;
    }

    private function getActivityTitleHtml() {
        $html = "";
        include_once WCAI_DIR_PATH . "/includes/layouts/activity/title.php";
        return $html;
    }

    private function getVotesTitleHtml() {
        $html = "";
        include_once WCAI_DIR_PATH . "/includes/layouts/votes/title.php";
        return $html;
    }

    private function getSubscriptionsTitleHtml() {
        $html = "";
        include_once WCAI_DIR_PATH . "/includes/layouts/subscriptions/title.php";
        return $html;
    }

    private function getFollowsTitleHtml() {
        $html = "";
        include_once WCAI_DIR_PATH . "/includes/layouts/follows/title.php";
        return $html;
    }

    private function getProfileContentHtml($wpdiscuz, $user, $comment, $isFirstTab) {
        $html = "<div id='wcai-content-item-1' class='wcai-content-item wcai-active'>";
        if ($user && $user->ID) {
            $allComments = $this->dbManager->getAuthorAllComments($user->ID);
            $commentStatistic = $this->getAuthorStatistic($allComments);
            if ($isFirstTab) {
                include_once WCAI_DIR_PATH . "/includes/layouts/profile/content-user.php";
            }
        } else {
            $allComments = $this->dbManager->getAuthorAllComments(0, $comment->comment_author_email);
            $commentStatistic = $this->getAuthorStatistic($allComments);
            if ($isFirstTab) {
                include_once WCAI_DIR_PATH . "/includes/layouts/profile/content-guest.php";
            }
        }
        $html .= "</div>";
        return $html;
    }

    private function getProfileShortContentHtml($wpdiscuz, $user, $comment) {
        $html = "<div id='wcai-content-item-1' class='wcai-content-item wcai-content-item-short wcai-active'>";
        if ($user && $user->ID) {
            $allComments = $this->dbManager->getAuthorAllComments($user->ID);
            $commentStatistic = $this->getAuthorStatistic($allComments);
            include_once WCAI_DIR_PATH . "/includes/layouts/profile/content-user-short.php";
        } else {
            $allComments = $this->dbManager->getAuthorAllComments(0, $comment->comment_author_email);
            $commentStatistic = $this->getAuthorStatistic($allComments);
            include_once WCAI_DIR_PATH . "/includes/layouts/profile/content-guest-short.php";
        }
        $html .= "</div>";
        return $html;
    }

    private function getActivityContentHtml($wpdiscuz, $user, $comment, $isFirstTab) {
        $html = "<div id='wcai-content-item-2' class='wcai-content-item'>";
        if ($isFirstTab) {
            include_once WCAI_DIR_PATH . "/includes/layouts/activity/content.php";
        }
        $html .= "</div>";
        return $html;
    }

    private function getVotesContentHtml($wpdiscuz, $user, $comment, $isFirstTab) {
        $perPage = $this->options->perPage;
        $itemsCount = $this->dbManager->getGivenLikesDislikesCount($user->ID);
        $items = $this->dbManager->getGivenLikesDislikes($user->ID, $perPage, 0);
        $html = "<div id='wcai-content-item-3' class='wcai-content-item'>";
        if ($isFirstTab) {
            include_once WCAI_DIR_PATH . "/includes/layouts/votes/content.php";
        }
        $html .= "</div>";
        return $html;
    }

    private function getSubscriptionsContentHtml($wpdiscuz, $user, $comment, $isFirstTab) {
        $perPage = $this->options->perPage;
        $itemsCount = $this->dbManager->getSubscriptionsCount($user->user_email);
        $items = $this->dbManager->getSubscriptions($user->user_email, $perPage, 0);
        $html = "<div id='wcai-content-item-4' class='wcai-content-item'>";
        if ($isFirstTab) {
            include_once WCAI_DIR_PATH . "/includes/layouts/subscriptions/content.php";
        }
        $html .= "</div>";
        return $html;
    }

    private function getFollowsContentHtml($wpdiscuz, $user, $comment, $isFirstTab) {
        $perPage = $this->options->perPage;
        $itemsCount = $this->dbManager->getFollowsCount($user->user_email);
        $items = $this->dbManager->getFollows($user->user_email, $perPage, 0);
        $html = "<div id='wcai-content-item-5' class='wcai-content-item'>";
        if ($isFirstTab) {
            include_once WCAI_DIR_PATH . "/includes/layouts/follows/content.php";
        }
        $html .= "</div>";
        return $html;
    }

    public function getActivityPage() {
        $wpdiscuz = wpDiscuz();
        $html = "";
        include_once WCAI_DIR_PATH . "/includes/layouts/activity/activity-page.php";
        wp_die($html);
    }

    public function getVotesPage() {
        $wpdiscuz = wpDiscuz();
        $html = "";
        include_once WCAI_DIR_PATH . "/includes/layouts/votes/votes-page.php";
        wp_die($html);
    }

    public function getSubscriptionsPage() {
        $wpdiscuz = wpDiscuz();
        $html = "";
        include_once WCAI_DIR_PATH . "/includes/layouts/subscriptions/subscriptions-page.php";
        wp_die($html);
    }

    public function getFollowsPage() {
        $wpdiscuz = wpDiscuz();
        $html = "";
        include_once WCAI_DIR_PATH . "/includes/layouts/follows/follows-page.php";
        wp_die($html);
    }

    public function wcaiUnsubscribe() {
        $sId = isset($_POST["sId"]) ? intval($_POST["sId"]) : 0;
        $this->dbManager->wcaiUnsubscribe($sId);
        $this->getSubscriptionsPage();
    }

    public function wcaiUnfollow() {
        $sId = isset($_POST["fId"]) ? intval($_POST["fId"]) : 0;
        $this->dbManager->wcaiUnfollow($sId);
        $this->getFollowsPage();
    }

    private function isAllowedRole($user) {
        $isAllowed = false;
        if ($user && isset($user->roles) && is_array($user->roles)) {
            foreach ($user->roles as $role) {
                if (in_array($role, $this->options->showForRoles)) {
                    $isAllowed = true;
                    break;
                }
            }
        }
        return $isAllowed;
    }

    private function getAuthorStatistic($allComments) {
        $commentStatistic = array("all" => 0, "approved" => 0, "pending" => 0, "spam" => 0, "trash" => 0, "likes" => 0, "dislikes" => 0);
        if ($allComments && is_array($allComments)) {
            $approved = 0;
            $pending = 0;
            $spam = 0;
            $trash = 0;
            $commentIds = array();
            foreach ($allComments as $comment) {
                if ($comment->comment_approved === "1") {
                    $commentIds[] = $comment->comment_ID;
                    $approved++;
                } else if ($comment->comment_approved === "0") {
                    $pending++;
                } else if ($comment->comment_approved === "spam") {
                    $spam++;
                } else if ($comment->comment_approved === "trash") {
                    $trash++;
                }
            }
            $commentStatistic["all"] = count($allComments);
            $commentStatistic["approved"] = $approved;
            $commentStatistic["pending"] = $pending;
            $commentStatistic["spam"] = $spam;
            $commentStatistic["trash"] = $trash;
            $votes = $this->dbManager->getReceivedLikesDislikes($commentIds);
            $commentStatistic["likes"] = $votes["likes"];
            $commentStatistic["dislikes"] = $votes["dislikes"];
        }
        return $commentStatistic;
    }

    public function commentAvatarClasses($classes) {
        $currentUser = wp_get_current_user();
        if (($currentUser && $this->isAllowedRole($currentUser) || (!WpdiscuzCommentAuthorInfo::$IS_LOGGED_IN && $this->options->showForGuests)) && $this->options->shortInfoOnAvatarHover) {
            $classes .= "wcai-short-info wcai-not-clicked";
        }
        return $classes;
    }

    public function usernameClasses($classes) {
        $currentUser = wp_get_current_user();
        if (($currentUser && $this->isAllowedRole($currentUser) || (!WpdiscuzCommentAuthorInfo::$IS_LOGGED_IN && $this->options->showForGuests)) && $this->options->fullInfoOnUsernameClick && $this->options->sections) {
            $classes .= "wcai-uname-info wcai-not-clicked";
        }
        return $classes;
    }

}
