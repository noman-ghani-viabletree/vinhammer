<?php
if (!defined("ABSPATH")) {
    exit();
}
?>
<div class="wcai-item">
    <div class="wcai-item-left">
        <div class="wcai-item-link wcai-comment-meta">
            <i class="fas fa-user"></i> <?php echo $fName; ?> &nbsp; 
            <i class="fas fa-calendar-alt"></i> <?php echo $postedDate; ?>
        </div>
    </div>
    <?php if ($isAdmin || $fEmail == $currentUser->user_email) { ?>
        <div class="wcai-item-right">
            <a href="#" class="wcai-unfollow wcai-not-clicked" data-wcai-fid='<?php echo $fId; ?>'>
                <i class="fas fa-trash-alt"></i>
            </a>
        </div>
    <?php } ?>
    <div class="wcai-clear"></div>
</div>