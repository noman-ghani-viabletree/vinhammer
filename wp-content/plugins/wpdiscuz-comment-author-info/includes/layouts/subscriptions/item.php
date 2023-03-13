<?php
if (!defined("ABSPATH")) {
    exit();
}
?>
<div class="wcai-item">
    <div class="wcai-item-left">
        <div class="wcai-item-link wcai-comment-meta">
            <i class="fas fa-user"></i> <?php echo $author; ?> &nbsp; 
            <i class="fas fa-calendar-alt"></i> <?php echo $postedDate; ?>
        </div>
        <div class="wcai-item-link wcai-comment-item-link">
            <a class="wcai-comment-link" href="<?php echo $link; ?>" target="_blank">
                <?php echo $content; ?>
            </a>
        </div>
        <div class="wcai-item-link wcai-post-item-link">
            <i class="far fa-bell"></i> 
            <?php echo $sTypeInfo; ?>
        </div>
    </div>
    <?php if ($isAdmin || $sEmail == $currentUser->user_email) { ?>
        <div class="wcai-item-right">
            <a href="#" class="wcai-unsubscribe wcai-not-clicked" data-wcai-sid='<?php echo $sId; ?>'>
                <i class="far fa-bell-slash"></i>
            </a>
        </div>
    <?php } ?>
    <div class="wcai-clear"></div>
</div>