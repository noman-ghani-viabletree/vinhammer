<?php

if (!defined("ABSPATH")) {
    exit();
}

class WCAIOptions implements WCAIConstants {

    private $dbManager;
    public $tabKey = "wcai";
    public $sections;
    public $showForRoles;
    public $showForGuests;
    public $shortInfoOnAvatarHover;
    public $fullInfoOnUsernameClick;
    /* SECTION MAIN INFO */
    public $profileShowDisplayName;
    public $profileShowNickName;
    public $profileShowBio;
    public $profileShowWebUrl;
    public $profileShowStatistics;
    public $profileShowMycredData;
    /* PER PAGE OPTION */
    public $perPage;

    /* PHRASES */
    /* === SECTIONS AND COMMON === */
    public $phraseProfileSectionTitle;
    public $phraseActivitySectionTitle;
    public $phraseVotesSectionTitle;
    public $phraseSubscriptionsSectionTitle;
    public $phraseFollowsSectionTitle;
    public $phraseNotAvailable;
    public $phraseNoData;
    public $phraseFullInfo;
    /* === PROFILE === */
    public $phraseProfileLastActivity;
    public $phraseProfileComments;
    public $phraseProfilePosts;
    public $phraseProfileReceivedLikes;
    public $phraseProfileReceivedDisikes;
    public $phraseProfileAuthorBio;
    public $phraseProfileCommentsStat;
    public $phraseProfileCommentsStatAll;
    public $phraseProfileCommentsStatApproved;
    public $phraseProfileCommentsStatPending;
    public $phraseProfileCommentsStatSpam;
    public $phraseProfileCommentsStatTrashed;
    /* === ACTIVITY === */
    public $phraseActivityInResponseTo;
    /* === VOTES === */
    public $phraseVotesInResponseTo;
    /* === SUBSCRIPTIONS === */
    public $phraseSubscriptionsReply;
    public $phraseSubscriptionsAllComment;
    public $phraseSubscriptionsPost;
    /* === PAGINATION === */
    public $phrasePaginationFirst;
    public $phrasePaginationPrevious;
    public $phrasePaginationNext;
    public $phrasePaginationLast;

    public function __construct($dbManager) {
        $this->dbManager = $dbManager;
        $options = maybe_unserialize(get_option(self::OPTION_MAIN_OPTIONS));
        $this->addOptions();
        $this->initOptions($options);
    }

    public function addOptions() {
        $options = [
            "sections" => $this->getDefaultSections(),
            "showForRoles" => $this->getDefaultRoles(),
            "showForGuests" => 1,
            "shortInfoOnAvatarHover" => 1,
            "fullInfoOnUsernameClick" => 1,
            "profileShowDisplayName" => 1,
            "profileShowNickName" => 1,
            "profileShowBio" => 1,
            "profileShowWebUrl" => 1,
            "profileShowStatistics" => 1,
            "profileShowMycredData" => 1,
            "perPage" => 5,
            "phraseProfileSectionTitle" => __("Profile", "wpdiscuz-comment-author-info"),
            "phraseActivitySectionTitle" => __("Activity", "wpdiscuz-comment-author-info"),
            "phraseVotesSectionTitle" => __("Votes", "wpdiscuz-comment-author-info"),
            "phraseSubscriptionsSectionTitle" => __("Subscriptions", "wpdiscuz-comment-author-info"),
            "phraseFollowsSectionTitle" => __("Follows", "wpdiscuz-comment-author-info"),
            "phraseNotAvailable" => __("Not available", "wpdiscuz-comment-author-info"),
            "phraseNoData" => __("No Data", "wpdiscuz-comment-author-info"),
            "phraseFullInfo" => __("View full info", "wpdiscuz-comment-author-info"),
            "phraseProfileLastActivity" => __("Last Activity:", "wpdiscuz-comment-author-info"),
            "phraseProfileComments" => __("Comments", "wpdiscuz-comment-author-info"),
            "phraseProfilePosts" => __("Posts", "wpdiscuz-comment-author-info"),
            "phraseProfileReceivedLikes" => __("Received Likes", "wpdiscuz-comment-author-info"),
            "phraseProfileReceivedDisikes" => __("Received Disikes", "wpdiscuz-comment-author-info"),
            "phraseProfileAuthorBio" => __("Comment Author Biography", "wpdiscuz-comment-author-info"),
            "phraseProfileCommentsStat" => __("Comments Statistic", "wpdiscuz-comment-author-info"),
            "phraseProfileCommentsStatAll" => __("All", "wpdiscuz-comment-author-info"),
            "phraseProfileCommentsStatApproved" => __("Approved", "wpdiscuz-comment-author-info"),
            "phraseProfileCommentsStatPending" => __("Pending", "wpdiscuz-comment-author-info"),
            "phraseProfileCommentsStatSpam" => __("Spam", "wpdiscuz-comment-author-info"),
            "phraseProfileCommentsStatTrashed" => __("Trashed", "wpdiscuz-comment-author-info"),
            "phraseProfileMyCredData" => __("MyCred Data", "wpdiscuz-comment-author-info"),
            "phraseProfileBadges" => __("Badges", "wpdiscuz-comment-author-info"),
            "phraseProfileRank" => __("Rank", "wpdiscuz-comment-author-info"),
            "phraseActivityInResponseTo" => __("In Response To:", "wpdiscuz-comment-author-info"),
            "phraseVotesInResponseTo" => __("In Response To:", "wpdiscuz-comment-author-info"),
            "phraseSubscriptionsReply" => __("subscribed to replies to this comment", "wpdiscuz-comment-author-info"),
            "phraseSubscriptionsAllComment" => __("subscribed to replies to own comments", "wpdiscuz-comment-author-info"),
            "phraseSubscriptionsPost" => __("subscribed to all follow-up comments of this post", "wpdiscuz-comment-author-info"),
            "phrasePaginationFirst" => __("&laquo;", "wpdiscuz-comment-author-info"),
            "phrasePaginationPrevious" => __("&lsaquo;", "wpdiscuz-comment-author-info"),
            "phrasePaginationNext" => __("&rsaquo;", "wpdiscuz-comment-author-info"),
            "phrasePaginationLast" => __("&raquo;", "wpdiscuz-comment-author-info"),
        ];
        add_option(self::OPTION_MAIN_OPTIONS, $options, "", "no");
    }

    public function initOptions($options) {
        $options = maybe_unserialize($options);
        $this->sections = isset($options["sections"]) && is_array($options["sections"]) ? $options["sections"] : $this->getDefaultSections();
        $this->showForRoles = isset($options["showForRoles"]) && is_array($options["showForRoles"]) ? $options["showForRoles"] : [];
        $this->showForGuests = isset($options["showForGuests"]) && ($option = intval($options["showForGuests"])) ? $option : 0;
        $this->shortInfoOnAvatarHover = isset($options["shortInfoOnAvatarHover"]) && ($option = intval($options["shortInfoOnAvatarHover"])) ? $option : 0;
        $this->fullInfoOnUsernameClick = isset($options["fullInfoOnUsernameClick"]) && ($option = intval($options["fullInfoOnUsernameClick"])) ? $option : 0;
        $this->profileShowDisplayName = isset($options["profileShowDisplayName"]) && ($option = intval($options["profileShowDisplayName"])) ? $option : 0;
        $this->profileShowNickName = isset($options["profileShowNickName"]) && ($option = intval($options["profileShowNickName"])) ? $option : 0;
        $this->profileShowBio = isset($options["profileShowBio"]) && ($option = intval($options["profileShowBio"])) ? $option : 0;
        $this->profileShowWebUrl = isset($options["profileShowWebUrl"]) && ($option = intval($options["profileShowWebUrl"])) ? $option : 0;
        $this->profileShowStatistics = isset($options["profileShowStatistics"]) && ($option = intval($options["profileShowStatistics"])) ? $option : 0;
        $this->profileShowMycredData = isset($options["profileShowMycredData"]) && ($option = intval($options["profileShowMycredData"])) ? $option : 0;
        $this->perPage = isset($options["perPage"]) && ($option = intval($options["perPage"])) ? $option : 5;
        $this->phraseProfileSectionTitle = isset($options["phraseProfileSectionTitle"]) && ($phrase = esc_html($options["phraseProfileSectionTitle"])) ? $phrase : "";
        $this->phraseActivitySectionTitle = isset($options["phraseActivitySectionTitle"]) && ($phrase = esc_html($options["phraseActivitySectionTitle"])) ? $phrase : "";
        $this->phraseVotesSectionTitle = isset($options["phraseVotesSectionTitle"]) && ($phrase = esc_html($options["phraseVotesSectionTitle"])) ? $phrase : "";
        $this->phraseSubscriptionsSectionTitle = isset($options["phraseSubscriptionsSectionTitle"]) && ($phrase = esc_html($options["phraseSubscriptionsSectionTitle"])) ? $phrase : "";
        $this->phraseFollowsSectionTitle = isset($options["phraseFollowsSectionTitle"]) && ($phrase = esc_html($options["phraseFollowsSectionTitle"])) ? $phrase : "";
        $this->phraseNotAvailable = isset($options["phraseNotAvailable"]) && ($phrase = esc_html($options["phraseNotAvailable"])) ? $phrase : "";
        $this->phraseNoData = isset($options["phraseNoData"]) && ($phrase = esc_html($options["phraseNoData"])) ? $phrase : "";
        $this->phraseFullInfo = isset($options["phraseFullInfo"]) && ($phrase = esc_html($options["phraseFullInfo"])) ? $phrase : "";
        $this->phraseProfileLastActivity = isset($options["phraseProfileLastActivity"]) && ($phrase = esc_html($options["phraseProfileLastActivity"])) ? $phrase : "";
        $this->phraseProfileComments = isset($options["phraseProfileComments"]) && ($phrase = esc_html($options["phraseProfileComments"])) ? $phrase : "";
        $this->phraseProfilePosts = isset($options["phraseProfilePosts"]) && ($phrase = esc_html($options["phraseProfilePosts"])) ? $phrase : "";
        $this->phraseProfileReceivedLikes = isset($options["phraseProfileReceivedLikes"]) && ($phrase = esc_html($options["phraseProfileReceivedLikes"])) ? $phrase : "";
        $this->phraseProfileReceivedDisikes = isset($options["phraseProfileReceivedDisikes"]) && ($phrase = esc_html($options["phraseProfileReceivedDisikes"])) ? $phrase : "";
        $this->phraseProfileAuthorBio = isset($options["phraseProfileAuthorBio"]) && ($phrase = esc_html($options["phraseProfileAuthorBio"])) ? $phrase : "";
        $this->phraseProfileCommentsStat = isset($options["phraseProfileCommentsStat"]) && ($phrase = esc_html($options["phraseProfileCommentsStat"])) ? $phrase : "";
        $this->phraseProfileCommentsStatAll = isset($options["phraseProfileCommentsStatAll"]) && ($phrase = esc_html($options["phraseProfileCommentsStatAll"])) ? $phrase : "";
        $this->phraseProfileCommentsStatApproved = isset($options["phraseProfileCommentsStatApproved"]) && ($phrase = esc_html($options["phraseProfileCommentsStatApproved"])) ? $phrase : "";
        $this->phraseProfileCommentsStatPending = isset($options["phraseProfileCommentsStatPending"]) && ($phrase = esc_html($options["phraseProfileCommentsStatPending"])) ? $phrase : "";
        $this->phraseProfileCommentsStatSpam = isset($options["phraseProfileCommentsStatSpam"]) && ($phrase = esc_html($options["phraseProfileCommentsStatSpam"])) ? $phrase : "";
        $this->phraseProfileCommentsStatTrashed = isset($options["phraseProfileCommentsStatTrashed"]) && ($phrase = esc_html($options["phraseProfileCommentsStatTrashed"])) ? $phrase : "";
        $this->phraseActivityInResponseTo = isset($options["phraseActivityInResponseTo"]) && ($phrase = esc_html($options["phraseActivityInResponseTo"])) ? $phrase : "";
        $this->phraseVotesInResponseTo = isset($options["phraseVotesInResponseTo"]) && ($phrase = esc_html($options["phraseVotesInResponseTo"])) ? $phrase : "";
        $this->phraseSubscriptionsReply = isset($options["phraseSubscriptionsReply"]) && ($phrase = esc_html($options["phraseSubscriptionsReply"])) ? $phrase : "";
        $this->phraseSubscriptionsAllComment = isset($options["phraseSubscriptionsAllComment"]) && ($phrase = esc_html($options["phraseSubscriptionsAllComment"])) ? $phrase : "";
        $this->phraseSubscriptionsPost = isset($options["phraseSubscriptionsPost"]) && ($phrase = esc_html($options["phraseSubscriptionsPost"])) ? $phrase : "";
        $this->phrasePaginationFirst = isset($options["phrasePaginationFirst"]) && ($phrase = esc_html($options["phrasePaginationFirst"])) ? $phrase : "";
        $this->phrasePaginationPrevious = isset($options["phrasePaginationPrevious"]) && ($phrase = esc_html($options["phrasePaginationPrevious"])) ? $phrase : "";
        $this->phrasePaginationNext = isset($options["phrasePaginationNext"]) && ($phrase = esc_html($options["phrasePaginationNext"])) ? $phrase : "";
        $this->phrasePaginationLast = isset($options["phrasePaginationLast"]) && ($phrase = esc_html($options["phrasePaginationLast"])) ? $phrase : "";
    }

    public function toArray() {
        $options = [
            "sections" => $this->sections,
            "showForRoles" => $this->showForRoles,
            "showForGuests" => $this->showForGuests,
            "shortInfoOnAvatarHover" => $this->shortInfoOnAvatarHover,
            "fullInfoOnUsernameClick" => $this->fullInfoOnUsernameClick,
            "profileShowDisplayName" => $this->profileShowDisplayName,
            "profileShowNickName" => $this->profileShowNickName,
            "profileShowBio" => $this->profileShowBio,
            "profileShowWebUrl" => $this->profileShowWebUrl,
            "profileShowStatistics" => $this->profileShowStatistics,
            "profileShowMycredData" => $this->profileShowMycredData,
            "perPage" => $this->perPage,
            "phraseProfileSectionTitle" => $this->phraseProfileSectionTitle,
            "phraseActivitySectionTitle" => $this->phraseActivitySectionTitle,
            "phraseVotesSectionTitle" => $this->phraseVotesSectionTitle,
            "phraseSubscriptionsSectionTitle" => $this->phraseSubscriptionsSectionTitle,
            "phraseFollowsSectionTitle" => $this->phraseFollowsSectionTitle,
            "phraseNotAvailable" => $this->phraseNotAvailable,
            "phraseNoData" => $this->phraseNoData,
            "phraseFullInfo" => $this->phraseFullInfo,
            "phraseProfileLastActivity" => $this->phraseProfileLastActivity,
            "phraseProfileComments" => $this->phraseProfileComments,
            "phraseProfilePosts" => $this->phraseProfilePosts,
            "phraseProfileReceivedLikes" => $this->phraseProfileReceivedLikes,
            "phraseProfileReceivedDisikes" => $this->phraseProfileReceivedDisikes,
            "phraseProfileAuthorBio" => $this->phraseProfileAuthorBio,
            "phraseProfileCommentsStat" => $this->phraseProfileCommentsStat,
            "phraseProfileCommentsStatAll" => $this->phraseProfileCommentsStatAll,
            "phraseProfileCommentsStatApproved" => $this->phraseProfileCommentsStatApproved,
            "phraseProfileCommentsStatPending" => $this->phraseProfileCommentsStatPending,
            "phraseProfileCommentsStatSpam" => $this->phraseProfileCommentsStatSpam,
            "phraseProfileCommentsStatTrashed" => $this->phraseProfileCommentsStatTrashed,
            "phraseActivityInResponseTo" => $this->phraseActivityInResponseTo,
            "phraseVotesInResponseTo" => $this->phraseVotesInResponseTo,
            "phraseSubscriptionsReply" => $this->phraseSubscriptionsReply,
            "phraseSubscriptionsAllComment" => $this->phraseSubscriptionsAllComment,
            "phraseSubscriptionsPost" => $this->phraseSubscriptionsPost,
            "phrasePaginationFirst" => $this->phrasePaginationFirst,
            "phrasePaginationPrevious" => $this->phrasePaginationPrevious,
            "phrasePaginationNext" => $this->phrasePaginationNext,
            "phrasePaginationLast" => $this->phrasePaginationLast,
        ];
        return $options;
    }

    public function saveOptions() {
        if ($this->tabKey === $_POST["wpd_tab"]) {
            $this->sections = isset($_POST[$this->tabKey]["sections"]) && is_array($_POST[$this->tabKey]["sections"]) ? $_POST[$this->tabKey]["sections"] : [];
            $this->showForRoles = isset($_POST[$this->tabKey]["showForRoles"]) && is_array($_POST[$this->tabKey]["showForRoles"]) ? $_POST[$this->tabKey]["showForRoles"] : [];
            $this->showForGuests = isset($_POST[$this->tabKey]["showForGuests"]) ? absint($_POST[$this->tabKey]["showForGuests"]) : 0;
            $this->shortInfoOnAvatarHover = isset($_POST[$this->tabKey]["shortInfoOnAvatarHover"]) ? absint($_POST[$this->tabKey]["shortInfoOnAvatarHover"]) : 0;
            $this->fullInfoOnUsernameClick = isset($_POST[$this->tabKey]["fullInfoOnUsernameClick"]) ? absint($_POST[$this->tabKey]["fullInfoOnUsernameClick"]) : 0;
            $this->profileShowDisplayName = isset($_POST[$this->tabKey]["profileShowDisplayName"]) ? absint($_POST[$this->tabKey]["profileShowDisplayName"]) : 0;
            $this->profileShowNickName = isset($_POST[$this->tabKey]["profileShowNickName"]) ? absint($_POST[$this->tabKey]["profileShowNickName"]) : 0;
            $this->profileShowBio = isset($_POST[$this->tabKey]["profileShowBio"]) ? absint($_POST[$this->tabKey]["profileShowBio"]) : 0;
            $this->profileShowWebUrl = isset($_POST[$this->tabKey]["profileShowWebUrl"]) ? absint($_POST[$this->tabKey]["profileShowWebUrl"]) : 0;
            $this->profileShowStatistics = isset($_POST[$this->tabKey]["profileShowStatistics"]) ? absint($_POST[$this->tabKey]["profileShowStatistics"]) : 0;
            $this->profileShowMycredData = isset($_POST[$this->tabKey]["profileShowMycredData"]) ? absint($_POST[$this->tabKey]["profileShowMycredData"]) : 0;
            $this->perPage = isset($_POST[$this->tabKey]["perPage"]) ? absint($_POST[$this->tabKey]["perPage"]) : 5;
            $this->phraseProfileSectionTitle = isset($_POST[$this->tabKey]["phraseProfileSectionTitle"]) ? stripslashes($_POST[$this->tabKey]["phraseProfileSectionTitle"]) : "";
            $this->phraseActivitySectionTitle = isset($_POST[$this->tabKey]["phraseActivitySectionTitle"]) ? stripslashes($_POST[$this->tabKey]["phraseActivitySectionTitle"]) : "";
            $this->phraseVotesSectionTitle = isset($_POST[$this->tabKey]["phraseVotesSectionTitle"]) ? stripslashes($_POST[$this->tabKey]["phraseVotesSectionTitle"]) : "";
            $this->phraseSubscriptionsSectionTitle = isset($_POST[$this->tabKey]["phraseSubscriptionsSectionTitle"]) ? stripslashes($_POST[$this->tabKey]["phraseSubscriptionsSectionTitle"]) : "";
            $this->phraseFollowsSectionTitle = isset($_POST[$this->tabKey]["phraseFollowsSectionTitle"]) ? stripslashes($_POST[$this->tabKey]["phraseFollowsSectionTitle"]) : "";
            $this->phraseNotAvailable = isset($_POST[$this->tabKey]["phraseNotAvailable"]) ? stripslashes($_POST[$this->tabKey]["phraseNotAvailable"]) : "";
            $this->phraseNoData = isset($_POST[$this->tabKey]["phraseNoData"]) ? stripslashes($_POST[$this->tabKey]["phraseNoData"]) : "";
            $this->phraseFullInfo = isset($_POST[$this->tabKey]["phraseFullInfo"]) ? stripslashes($_POST[$this->tabKey]["phraseFullInfo"]) : "";
            $this->phraseProfileLastActivity = isset($_POST[$this->tabKey]["phraseProfileLastActivity"]) ? stripslashes($_POST[$this->tabKey]["phraseProfileLastActivity"]) : "";
            $this->phraseProfileComments = isset($_POST[$this->tabKey]["phraseProfileComments"]) ? stripslashes($_POST[$this->tabKey]["phraseProfileComments"]) : "";
            $this->phraseProfilePosts = isset($_POST[$this->tabKey]["phraseProfilePosts"]) ? stripslashes($_POST[$this->tabKey]["phraseProfilePosts"]) : "";
            $this->phraseProfileReceivedLikes = isset($_POST[$this->tabKey]["phraseProfileReceivedLikes"]) ? stripslashes($_POST[$this->tabKey]["phraseProfileReceivedLikes"]) : "";
            $this->phraseProfileReceivedDisikes = isset($_POST[$this->tabKey]["phraseProfileReceivedDisikes"]) ? stripslashes($_POST[$this->tabKey]["phraseProfileReceivedDisikes"]) : "";
            $this->phraseProfileAuthorBio = isset($_POST[$this->tabKey]["phraseProfileAuthorBio"]) ? stripslashes($_POST[$this->tabKey]["phraseProfileAuthorBio"]) : "";
            $this->phraseProfileCommentsStat = isset($_POST[$this->tabKey]["phraseProfileCommentsStat"]) ? stripslashes($_POST[$this->tabKey]["phraseProfileCommentsStat"]) : "";
            $this->phraseProfileCommentsStatAll = isset($_POST[$this->tabKey]["phraseProfileCommentsStatAll"]) ? stripslashes($_POST[$this->tabKey]["phraseProfileCommentsStatAll"]) : "";
            $this->phraseProfileCommentsStatApproved = isset($_POST[$this->tabKey]["phraseProfileCommentsStatApproved"]) ? stripslashes($_POST[$this->tabKey]["phraseProfileCommentsStatApproved"]) : "";
            $this->phraseProfileCommentsStatPending = isset($_POST[$this->tabKey]["phraseProfileCommentsStatPending"]) ? stripslashes($_POST[$this->tabKey]["phraseProfileCommentsStatPending"]) : "";
            $this->phraseProfileCommentsStatSpam = isset($_POST[$this->tabKey]["phraseProfileCommentsStatSpam"]) ? stripslashes($_POST[$this->tabKey]["phraseProfileCommentsStatSpam"]) : "";
            $this->phraseProfileCommentsStatTrashed = isset($_POST[$this->tabKey]["phraseProfileCommentsStatTrashed"]) ? stripslashes($_POST[$this->tabKey]["phraseProfileCommentsStatTrashed"]) : "";
            $this->phraseActivityInResponseTo = isset($_POST[$this->tabKey]["phraseActivityInResponseTo"]) ? stripslashes($_POST[$this->tabKey]["phraseActivityInResponseTo"]) : "";
            $this->phraseVotesInResponseTo = isset($_POST[$this->tabKey]["phraseVotesInResponseTo"]) ? stripslashes($_POST[$this->tabKey]["phraseVotesInResponseTo"]) : "";
            $this->phraseSubscriptionsReply = isset($_POST[$this->tabKey]["phraseSubscriptionsReply"]) ? stripslashes($_POST[$this->tabKey]["phraseSubscriptionsReply"]) : "";
            $this->phraseSubscriptionsAllComment = isset($_POST[$this->tabKey]["phraseSubscriptionsAllComment"]) ? stripslashes($_POST[$this->tabKey]["phraseSubscriptionsAllComment"]) : "";
            $this->phraseSubscriptionsPost = isset($_POST[$this->tabKey]["phraseSubscriptionsPost"]) ? stripslashes($_POST[$this->tabKey]["phraseSubscriptionsPost"]) : "";
            $this->phrasePaginationFirst = isset($_POST[$this->tabKey]["phrasePaginationFirst"]) ? stripslashes($_POST[$this->tabKey]["phrasePaginationFirst"]) : "";
            $this->phrasePaginationPrevious = isset($_POST[$this->tabKey]["phrasePaginationPrevious"]) ? stripslashes($_POST[$this->tabKey]["phrasePaginationPrevious"]) : "";
            $this->phrasePaginationNext = isset($_POST[$this->tabKey]["phrasePaginationNext"]) ? stripslashes($_POST[$this->tabKey]["phrasePaginationNext"]) : "";
            $this->phrasePaginationLast = isset($_POST[$this->tabKey]["phrasePaginationLast"]) ? stripslashes($_POST[$this->tabKey]["phrasePaginationLast"]) : "";
            update_option(self::OPTION_MAIN_OPTIONS, $this->toArray());
        }
    }

    public function resetOptions($tab) {
        if ($tab === $this->tabKey || $tab === "all") {
            delete_option(self::OPTION_MAIN_OPTIONS);
            $this->addOptions();
            $this->initOptions(get_option(self::OPTION_MAIN_OPTIONS));
        }
    }

    public function getDefaultRoles() {
        return ["administrator", "editor", "author", "contributor", "subscriber"];
    }

    public function getDefaultSections() {
        return ["profile", "activity", "votes", "subscriptions", "follows"];
    }

    public function settingsArray($settings) {
        $settings["addons"][$this->tabKey] = [
            "title" => __("Comment Author Info", "wpdiscuz-comment-author-info"),
            "title_original" => "Comment Author Info",
            "icon" => "",
            "icon-height" => "",
            "file_path" => WCAI_DIR_PATH . "/options/wcai-html-options.php",
            "values" => $this,
            "options" => [
                "sections" => [
                    "label" => __("Comment Author Tabs", "wpdiscuz-comment-author-info"),
                    "label_original" => "Comment Author Tabs",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "showForRoles" => [
                    "label" => __("Display comment author information for user roles", "wpdiscuz-comment-author-info"),
                    "label_original" => "Display comment author information for user roles",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "showForGuests" => [
                    "label" => __("Display comment author information for guests", "wpdiscuz-comment-author-info"),
                    "label_original" => "Display comment author information for guests",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "shortInfoOnAvatarHover" => [
                    "label" => __("Display comment author short information on avatar hover", "wpdiscuz-comment-author-info"),
                    "label_original" => "Display comment author short information on avatar hover",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "fullInfoOnUsernameClick" => [
                    "label" => __("Display comment author full information on username click", "wpdiscuz-comment-author-info"),
                    "label_original" => "Display comment author full information on username click",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "profileShowDisplayName" => [
                    "label" => __("Display Name", "wpdiscuz-comment-author-info"),
                    "label_original" => "Display Name",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "profileShowNickName" => [
                    "label" => __("Display Nickname", "wpdiscuz-comment-author-info"),
                    "label_original" => "Display Nickname",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "profileShowBio" => [
                    "label" => __("Display Comment Author Biography", "wpdiscuz-comment-author-info"),
                    "label_original" => "Display Comment Author Biography",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "profileShowWebUrl" => [
                    "label" => __("Display Website", "wpdiscuz-comment-author-info"),
                    "label_original" => "Display Website",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "profileShowStatistics" => [
                    "label" => __("Display Comment Statistics", "wpdiscuz-comment-author-info"),
                    "label_original" => "Display Comment Statistics",
                    "description" => __("This information is available for admins only", "wpdiscuz-comment-author-info"),
                    "description_original" => "This information is available for admins only",
                    "docurl" => "#"
                ],
                "profileShowMycredData" => [
                    "label" => __("Display MyCRED Information", "wpdiscuz-comment-author-info"),
                    "label_original" => "Display MyCRED Information",
                    "description" => __("User Badges and Rank", "wpdiscuz-comment-author-info"),
                    "description_original" => "User Badges and Rank",
                    "docurl" => "#"
                ],
                "perPage" => [
                    "label" => __("Pagination items per page", "wpdiscuz-comment-author-info"),
                    "label_original" => "Pagination items per page",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseProfileSectionTitle" => [
                    "label" => __("Profile tab title", "wpdiscuz-comment-author-info"),
                    "label_original" => "Profile tab title",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseActivitySectionTitle" => [
                    "label" => __("Activity tab title", "wpdiscuz-comment-author-info"),
                    "label_original" => "Activity tab title",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseVotesSectionTitle" => [
                    "label" => __("Voted Comments tab title", "wpdiscuz-comment-author-info"),
                    "label_original" => "Voted Comments tab title",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseSubscriptionsSectionTitle" => [
                    "label" => __("Subscriptions tab title", "wpdiscuz-comment-author-info"),
                    "label_original" => "Subscriptions tab title",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseFollowsSectionTitle" => [
                    "label" => __("Follows tab title", "wpdiscuz-comment-author-info"),
                    "label_original" => "Follows tab title",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseNotAvailable" => [
                    "label" => __("Not Available", "wpdiscuz-comment-author-info"),
                    "label_original" => "Not Available",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseNoData" => [
                    "label" => __("No Data", "wpdiscuz-comment-author-info"),
                    "label_original" => "No Data",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseFullInfo" => [
                    "label" => __("View Full Info", "wpdiscuz-comment-author-info"),
                    "label_original" => "View Full Info",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseProfileLastActivity" => [
                    "label" => __("Last Activity", "wpdiscuz-comment-author-info"),
                    "label_original" => "Last Activity",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseProfileComments" => [
                    "label" => __("Comments", "wpdiscuz-comment-author-info"),
                    "label_original" => "Comments",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseProfilePosts" => [
                    "label" => __("Posts", "wpdiscuz-comment-author-info"),
                    "label_original" => "Posts",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseProfileReceivedLikes" => [
                    "label" => __("Received Likes", "wpdiscuz-comment-author-info"),
                    "label_original" => "Received Likes",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseProfileReceivedDisikes" => [
                    "label" => __("Received Disikes", "wpdiscuz-comment-author-info"),
                    "label_original" => "Received Disikes",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseProfileAuthorBio" => [
                    "label" => __("Comment Author Biography", "wpdiscuz-comment-author-info"),
                    "label_original" => "Comment Author Biography",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseProfileCommentsStat" => [
                    "label" => __("Comments Statistic", "wpdiscuz-comment-author-info"),
                    "label_original" => "Comments Statistic",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseProfileCommentsStatAll" => [
                    "label" => __("All", "wpdiscuz-comment-author-info"),
                    "label_original" => "All",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseProfileCommentsStatApproved" => [
                    "label" => __("Approved", "wpdiscuz-comment-author-info"),
                    "label_original" => "Approved",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseProfileCommentsStatPending" => [
                    "label" => __("Pending", "wpdiscuz-comment-author-info"),
                    "label_original" => "Pending",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseProfileCommentsStatSpam" => [
                    "label" => __("Spam", "wpdiscuz-comment-author-info"),
                    "label_original" => "Spam",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseProfileCommentsStatTrashed" => [
                    "label" => __("Trashed", "wpdiscuz-comment-author-info"),
                    "label_original" => "Trashed",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseActivityInResponseTo" => [
                    "label" => __("In Response To", "wpdiscuz-comment-author-info"),
                    "label_original" => "In Response To",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseVotesInResponseTo" => [
                    "label" => __("In Response To", "wpdiscuz-comment-author-info"),
                    "label_original" => "In Response To",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseSubscriptionsReply" => [
                    "label" => __("Subscribed to replies to this comment", "wpdiscuz-comment-author-info"),
                    "label_original" => "Subscribed to replies to this comment",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseSubscriptionsAllComment" => [
                    "label" => __("Subscribed to replies to own comments", "wpdiscuz-comment-author-info"),
                    "label_original" => "Subscribed to replies to own comments",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phraseSubscriptionsPost" => [
                    "label" => __("Subscribed to all follow-up comments of this post", "wpdiscuz-comment-author-info"),
                    "label_original" => "Subscribed to all follow-up comments of this post",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phrasePaginationFirst" => [
                    "label" => __("First", "wpdiscuz-comment-author-info"),
                    "label_original" => "First",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phrasePaginationPrevious" => [
                    "label" => __("Previous", "wpdiscuz-comment-author-info"),
                    "label_original" => "Previous",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phrasePaginationNext" => [
                    "label" => __("Next", "wpdiscuz-comment-author-info"),
                    "label_original" => "Next",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "phrasePaginationLast" => [
                    "label" => __("Last", "wpdiscuz-comment-author-info"),
                    "label_original" => "Last",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
            ],
        ];
        return $settings;
    }

}
