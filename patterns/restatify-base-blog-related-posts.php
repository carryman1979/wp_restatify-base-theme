<?php
/**
 * Title: Restatify Related Posts
 * Slug: restatify-base-blog-related-posts
 * Categories: restatify-blog
 * Block Types: core/post-content
 * Inserter: true
 */
?>
<!-- wp:group {"className":"restatify-blog-related","layout":{"type":"constrained"}} -->
<div class="wp-block-group restatify-blog-related"><!-- wp:heading {"level":3,"className":"restatify-blog-related__title"} -->
<h3 class="wp-block-heading restatify-blog-related__title">Related articles</h3>
<!-- /wp:heading -->

<!-- wp:query {"queryId":44,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":true},"className":"restatify-blog-query restatify-blog-query--related","layout":{"type":"constrained"}} -->
<div class="wp-block-query restatify-blog-query restatify-blog-query--related"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
<!-- wp:group {"className":"restatify-blog-card restatify-blog-card--compact","layout":{"type":"constrained"}} -->
<div class="wp-block-group restatify-blog-card restatify-blog-card--compact"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","className":"restatify-blog-card__image"} /-->

<!-- wp:post-title {"isLink":true,"level":4,"className":"restatify-blog-card__title"} /-->

<!-- wp:post-date {"className":"restatify-blog-card__date"} /--></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->
