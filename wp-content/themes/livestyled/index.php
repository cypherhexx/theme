<?php
get_header();
get_template_part( 'templates/global', 'social-icons' );
get_template_part( 'templates/pb-banner-full-bleed' );
?>


<?php
// ================================================================
// Latest Post / Featured Posts
// ================================================================ ?>
<article class="section theme--alternative" id="section-2">

	<section class="container grid grid--2">

		<div class="block clearfix">
			<h2 class="section__header section__header--normal-case">Latest Post</h2>

			<article class="latest-post">
				<?php 
				$the_query = new WP_Query( array('posts_per_page' => 1));

				if ( $the_query->have_posts() ) :
				while ( $the_query->have_posts() ) : $the_query->the_post();

					get_template_part( 'templates/post-blog' );

				endwhile;
				endif; ?>
				<?php wp_reset_postdata(); ?>

			</article>
		</div>


		<div class="block featured-posts-wrap">
			<h2 class="section__header section__header--normal-case">Featured Posts</h2>

			<?php
			$the_query = new WP_Query( array('posts_per_page' => 4, 'category_name' => 'featured'));
			if( $the_query->have_posts() ): ?>
				<ul class="featured-posts flexbox flex-wrap">
				<?php while( $the_query->have_posts() ): $the_query->the_post(); ?>

				<li class="post--blog block p0 flexbox">

					<a href="<?php the_permalink(); ?>" class="post--blog__img-wrap">
						<?php
						if (has_post_thumbnail()):
							$imgURL = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
						else:
							$imgURL = get_stylesheet_directory_uri()."/assets/img/post-img-fallback-150x150.jpg";
						endif;
						?>
						<img class="post--blog__img lazyload" data-src="<?php echo $imgURL; ?>" alt="<?php the_title(); ?>" />
					</a>

					<div class="post--blog__copy flexbox">
						<h3 class="post--blog__title">
							<a class="post--blog__title-link" href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</h3>
						<p class="post--blog__desc">
							<?php echo excerpt('15'); ?>
						</p>
					</div>

				</li>

				<?php endwhile; ?>
				</ul>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
		</div>

	</section>

</article>


<?php
// ================================================================
// Main Posts list (with category filters)
// ================================================================ ?>
<article class="section theme--secondary" id="section-3">

	<?php
	// ----------------------
	// Category Filters
	// ---------------------- ?>
	<section class="container flexbox align--center">
		<form class="blog-filters" action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST">
			<label class="blog-filters__label cta cta--sm cta--red active" for="all">
				<input class="blog-filters__input" type="radio" name="categoryfilter[]" id="all" value="all">
				<span>All</span>
			</label>
			<?php
			$args = array('orderby' => 'name', 'exclude' => array(16,17,1));
			$categories = get_categories( $args );

			foreach ($categories as $category) {
				echo '<label class="blog-filters__label cta cta--sm cta--red" for="'.$category->term_id.'"><input class="blog-filters__input" type="radio" name="categoryfilter[]" id="'.$category->term_id.'" value="' . $category->term_id . '"><span>' . $category->name . '</span></label>';
			}
			?>
			<input type="hidden" name="action" value="customfilter">
		</form>
	</section>
		
	<?php
	// ----------------------
	// Main Post List
	// ---------------------- ?>
	<section class="container flexbox">
		<?php
		if( have_posts() ): ?>
			<ul class="post-list flexbox flex-wrap">
			<?php $n=0; while( have_posts() ): the_post(); $n++;
				get_template_part( 'templates/post-blog' );
			endwhile; ?>
			</ul>
		<?php endif; ?>
	</section>

	<?php
	// ----------------------
	// Load More Posts Btn
	// ---------------------- ?>
	<section class="container flexbox justify-content-center">
		<?php
		if ($wp_query->max_num_pages > 1):
			echo '<div class="blog-load-more">View more</div>';
		endif; ?>
		<script>
			var posts_loadmore = '<?php echo json_encode( $wp_query->query_vars ); ?>',
			current_page_loadmore = 1,
			max_page_loadmore = <?php echo $wp_query->max_num_pages; ?>
		</script>
	</section>

</article>


<?php
// ================================================================
// Thought Leadership
// ================================================================ ?>
<article class="section section--scrollable section--<?php echo $section; ?> align--center theme--alternative" id="section-4">

    <h3 class="section__header">Thought Leadership</h3>

    <section class="media-blocks-wrap media-blocks-wrap--scrollable tabs flexbox hide-scrollbar scroll-touch drag-scroll">

		<?php
		$the_query = new WP_Query( array('posts_per_page' => 3, 'category_name' => 'thought-leadership'));
		if( $the_query->have_posts() ):
		while( $the_query->have_posts() ): $the_query->the_post();
		?>

        <div class="media-block align--left">

            <h3 class="media-block__title"><?php the_title(); ?></h3>
            <p class="media-block__meta"><?php the_time('M j, Y'); ?></p>

            <div class="media-block__desc">
				<?php echo excerpt('25'); ?>
            </div>

			<a class="cta cta--red cta--md post--blog__cta" href="<?php the_permalink(); ?>">Read more</a>

        </div>

		<?php
		endwhile;
		endif;
		wp_reset_postdata();
		?>

	</section>

</article>


<?php
include( 'templates/pb-request-demo-form.php' );
?>

<?php
get_footer();