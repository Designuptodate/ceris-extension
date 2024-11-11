<?php
    $postID = get_the_ID();
?>
<div class="entry-interaction entry-interaction--horizontal">
	<div class="entry-interaction__left">
		<div class="post-sharing post-sharing--simple">
			<ul>
				<?php echo ceris_single::bk_post_share($postID);?>
			</ul>
		</div>
	</div>

	<div class="entry-interaction__right">
		<a href="#comments" class="comments-count entry-action-btn" data-toggle="tooltip" data-placement="top" title="<?php echo get_comments_number($postID)?> comments"><i class="mdicon mdicon-chat_bubble"></i><span><?php echo get_comments_number($postID)?></span></a>
	</div>
</div>