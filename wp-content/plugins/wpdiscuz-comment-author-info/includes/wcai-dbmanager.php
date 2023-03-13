<?php

if (!defined("ABSPATH")) {
    exit();
}

class WCAIDBManager implements WCAIConstants {

    private $db;
    private $users_voted;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->users_voted = $wpdb->prefix . "wc_users_voted";
    }

    public function getAuthorAllComments($userId, $email = "") {
        $result = "";
        if (intval($userId)) {
            $sql = $this->db->prepare("SELECT `comment_ID`, `comment_approved` FROM {$this->db->comments} WHERE `user_id` = %d", $userId);
        } else if (trim($email)) {
            $sql = $this->db->prepare("SELECT `comment_ID`, `comment_approved` FROM {$this->db->comments} WHERE `comment_author_email` = %s", $email);
        }
        $result = $this->db->get_results($sql);
        return $result;
    }

    public function getReceivedLikesDislikes($commentIds = []) {
        $result = ["likes" => 0, "dislikes" => 0];
        if ($commentIds) {
            $cIds = implode(",", $commentIds);
            $sqlLikes = "SELECT COUNT(*) FROM `{$this->users_voted}` WHERE `vote_type` = '1' AND `comment_id` IN ($cIds);";
            $sqlDislikes = "SELECT COUNT(*) FROM `{$this->users_voted}` WHERE `vote_type` = '-1' AND `comment_id` IN ($cIds);";
            $result["likes"] = $this->db->get_var($sqlLikes);
            $result["dislikes"] = $this->db->get_var($sqlDislikes);
        }
        return $result;
    }

    public function getGivenLikesDislikes($userId, $limit, $offset) {
        $result = "";
        if (intval($userId)) {
            $limitCondition = ($l = intval($limit)) > 0 ? "LIMIT $l OFFSET $offset" : "";
            $sql = $this->db->prepare("SELECT `v`.`comment_id`, `v`.`vote_type` FROM {$this->db->prefix}wc_users_voted AS `v`, {$this->db->comments} AS `c` WHERE `c`.`comment_ID` = `v`.`comment_id` AND `c`.`comment_approved` = '1' AND `v`.`vote_type` IN (1, -1) AND `v`.`user_id` = %d ORDER BY `date` DESC $limitCondition", $userId);
            $result = $this->db->get_results($sql);
        }
        return $result;
    }

    public function getGivenLikesDislikesCount($userId) {
        $result = "";
        if (intval($userId)) {
            $sql = $this->db->prepare("SELECT COUNT(*) FROM {$this->db->prefix}wc_users_voted AS `v`, {$this->db->comments} AS `c` WHERE `c`.`comment_ID` = `v`.`comment_id` AND `c`.`comment_approved` = '1' AND `v`.`vote_type` IN (1, -1) AND `v`.`user_id` = %d;", $userId);
            $result = $this->db->get_var($sql);
        }
        return $result;
    }

    public function getSubscriptions($userEmail, $limit, $offset) {
        $result = "";
        if (trim($userEmail)) {
            $limitCondition = ($l = intval($limit)) > 0 ? "LIMIT $l OFFSET $offset" : "";
            $sql = $this->db->prepare("SELECT `s`.* FROM {$this->db->prefix}wc_comments_subscription AS `s`, {$this->db->posts} AS `p`, {$this->db->comments} AS `c` WHERE `s`.`confirm` = 1 AND `s`.`email` = %s AND `p`.`ID` = `s`.`post_id` AND `c`.`comment_post_ID` = `p`.`ID` AND `p`.`post_status` IN ('publish', 'private') AND `c`.`comment_approved` = '1' GROUP BY `s`.`id` $limitCondition;", $userEmail);
            $result = $this->db->get_results($sql);
        }
        return $result;
    }

    public function getSubscriptionsCount($userEmail) {
        $result = "";
        if (trim($userEmail)) {
            $sql = $this->db->prepare("SELECT COUNT(DISTINCT `s`.`id`) FROM {$this->db->prefix}wc_comments_subscription AS `s`, {$this->db->posts} AS `p`, {$this->db->comments} AS `c` WHERE `s`.`confirm` = 1 AND `s`.`email` = %s AND `p`.`ID` = `s`.`post_id` AND `c`.`comment_post_ID` = `p`.`ID` AND `p`.`post_status` IN ('publish', 'private') AND `c`.`comment_approved` = '1'", $userEmail);
            $result = $this->db->get_var($sql);
        }
        return $result;
    }

    public function getFollowsCount($userEmail) {
        $sql = $this->db->prepare("SELECT COUNT(*) FROM {$this->db->prefix}wc_follow_users WHERE `follower_email` = %s;", trim($userEmail));
        $result = $this->db->get_var($sql);
        return $result;
    }

    public function getFollows($userEmail, $limit, $offset) {
        $limitCondition = ($l = intval($limit)) > 0 ? "LIMIT $l OFFSET $offset" : "";
        $sql = $this->db->prepare("SELECT * FROM {$this->db->prefix}wc_follow_users WHERE `follower_email` = %s $limitCondition;", trim($userEmail));
        $result = $this->db->get_results($sql);
        return $result;
    }

    public function wcaiUnsubscribe($sId) {
        if ($id = intval($sId)) {
            $sql = $this->db->prepare("DELETE FROM {$this->db->prefix}wc_comments_subscription WHERE `id` = %d;", $id);
            $this->db->query($sql);
        }
    }

    public function wcaiUnfollow($fId) {
        if ($id = intval($fId)) {
            $sql = $this->db->prepare("DELETE FROM {$this->db->prefix}wc_follow_users WHERE `id` = %d;", $id);
            $this->db->query($sql);
        }
    }

}
