<?php

if (!defined("ABSPATH")) {
    exit();
}

class WEDBManager implements WEConstants {

    private $db;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        add_action("wpdiscuz_embeds_save_options", [&$this, "deleteWPoEmbedCache"]);
    }

    public function deleteWPoEmbedCache($pId = 0) {
        if ($id = intval($pId)) {
            $sql = $this->db->prepare("DELETE FROM {$this->db->postmeta} WHERE `id` = %d AND `meta_key` LIKE '_oembed_%';", $id);
        } else {
            $sql = "DELETE FROM {$this->db->postmeta} WHERE `meta_key` LIKE '_oembed_%';";
        }
        $this->db->query($sql);
    }

}
