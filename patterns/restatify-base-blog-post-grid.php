<?php
/**
 * Title: Restatify Blog Post Grid
 * Slug: restatify-base-blog-post-grid
 * Categories: restatify-blog
 * Block Types: core/query
 * Inserter: true
 */
?>
<!-- wp:query {"queryId":21,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false},"className":"restatify-blog-query restatify-blog-query--grid","layout":{"type":"constrained"}} -->
<div class="wp-block-query restatify-blog-query restatify-blog-query--grid"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
<!-- wp:group {"className":"restatify-blog-card","layout":{"type":"constrained"}} -->
<div class="wp-block-group restatify-blog-card"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","className":"restatify-blog-card__image"} /-->

<!-- wp:group {"className":"restatify-blog-card__content","layout":{"type":"constrained"}} -->
<div class="wp-block-group restatify-blog-card__content"><!-- wp:post-terms {"term":"category","className":"restatify-blog-card__terms"} /-->

<!-- wp:post-title {"isLink":true,"level":3,"className":"restatify-blog-card__title"} /-->

<!-- wp:post-excerpt {"moreText":"Read more","excerptLength":24,"className":"restatify-blog-card__excerpt"} /-->

<!-- wp:group {"className":"restatify-blog-card__meta","layout":{"type":"flex","justifyContent":"space-between"}} -->
<div class="wp-block-group restatify-blog-card__meta"><!-- wp:post-date /-->

<!-- wp:post-author-name /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination --></div>
<!-- /wp:query -->
