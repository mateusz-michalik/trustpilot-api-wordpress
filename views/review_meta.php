<?php
global $post;
?>
<p><strong>Review ID: </strong><?=get_post_meta($post->ID, 'review_id', true)?></p>
<p><strong>Stars: </strong><?=get_post_meta($post->ID, 'review_stars', true)?></p>
<p><strong>Reviewer ID: </strong><?=get_post_meta($post->ID, 'review_consumer_id', true)?></p>
<p><strong>Reviewer Name: </strong><?=get_post_meta($post->ID, 'review_consumer_name', true)?></p>
<p><strong>Reviewer Location: </strong><?=get_post_meta($post->ID, 'review_consumer_location', true)?></p>