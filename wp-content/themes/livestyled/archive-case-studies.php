<?php
get_header();
get_template_part( 'templates/global', 'social-icons' );
get_template_part( 'templates/pb-banner-full-bleed' );
?>

<article class="section theme--secondary" id="section-2">

	<?php
	$args = array(
		'post_type' => 'case-study-category'
	);
	$terms = get_terms( $args );

	foreach ( $terms as $term ):
		if($term->name != "Uncategorised"): ?>

		<section class="case-studies-list-wrap">

			<h2 class="section__header section__header--normal-case"><?php echo $term->name; ?></h2>

			<?php
			$args = array(
				'posts_per_page' => -1,
				'post_type' => 'case-studies', // you can change it according to your custom post type
				'tax_query' => array(
					array(
						'taxonomy' => 'case-study-category', // you can change it according to your taxonomy
						'field' => 'term_id', // this can be 'term_id', 'slug' & 'name'
						'terms' => $term->term_id,
					)
				)
			);
			$posts = get_posts($args);
			$count = count($posts);
			?>

			<ul class="case-studies-list case-studies-list--sectioned flexbox hide-scrollbar scroll-touch carousel <?php if($count < 4): echo "count-3"; endif; ?>">
				<?php foreach($posts as $post):
					get_template_part( 'templates/post-case-study' );
				endforeach; ?>
			</ul>

			<button class="carousel__arrow carousel__arrow--left"><?php icon('angle-left'); ?></button>
			<button class="carousel__arrow carousel__arrow--right"><?php icon('angle-right'); ?></button>

			<?php /*
			<a href="<?php echo get_term_link($term->term_id); ?>">More from this category</a>
			*/ ?>

		</section>

	<?php endif;
	endforeach; ?>

</article>


<?php
include( 'templates/pb-request-demo-form.php' );

get_footer();