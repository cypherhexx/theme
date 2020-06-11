<article class="section section--testimonials section--<?php echo $section; ?> align--center theme--primary" id="section-<?php echo $section; ?>">
	
	<section class="container container--1150">

		<h3 class="section__header">Testimonials</h3>

		<section class="testimonials__wrap tabs flexbox align-items-center anim-500" data-animate="fadeInUp-disabled">


			<?php // Client Logos
			// ----------------------------------------- ?>
			<ul class="testimonials__logos tabs-nav tabs-auto-rotate">
			<?php while( has_sub_field('testimonials', 'option') ):

				if(get_sub_field('t_logo')): ?>

					<li class="testimonials__logo flexbox align-items-center justify-content-center">
						<img class="rwd lazyload testimonials__logo-img" data-src="<?php echo get_sub_field('t_logo')['url']; ?>" alt="<?php the_sub_field('t_client_name', 'option') ?>" />
					</li>

				<?php else: ?>

					<li class="testimonials__logo flexbox align-items-center justify-content-center">
						<?php the_sub_field('t_client_name', 'option') ?>
					</li>

			<?php endif;
			endwhile; ?>
			</ul>


			<?php // Nav Controls (dots)
			// ----------------------------------------- ?>
			<ul class="testimonials__nav tabs-nav tabs-auto-rotate">
			<?php while( has_sub_field('testimonials', 'option') ): ?>
				<li class="testimonials__nav-item"></li>
			<?php endwhile; ?>
			</ul>


			<?php // Testimonial Quotes
			// ----------------------------------------- ?>
			<div class="testimonials__quotes tabs-auto-rotate">
			<?php while( has_sub_field('testimonials', 'option') ): ?>
				<blockquote class="testimonials__quote tabs-panel">
					<p><?php echo get_sub_field('t_quote', 'option'); ?></p>
					<cite>
						<?php echo get_sub_field('t_credit', 'option'); ?>
						<?php if(get_sub_field('t_job_title', 'option')):
							echo "<span>".get_sub_field('t_job_title', 'option')."</span>";
						endif; ?>
					</cite>
				</blockquote>
			<?php endwhile; ?>
			</div>


		</section>

	</section>

</article>