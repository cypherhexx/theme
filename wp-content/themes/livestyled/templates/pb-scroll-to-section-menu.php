<article class="section section--scroll-menu section--<?php echo $section; ?> align--center theme--secondary" id="section-<?php echo $section; ?>">
	
	<section class="container container--1100">

        <?php
        if(have_rows('stsm_menu_items')):
            echo "<ul class='scroll-menu__list hide-scrollbar scroll-touch'>";
            while(have_rows('stsm_menu_items')): the_row(); ?>

                <li class="scroll-menu__list-item">
                    <a class="scroll-menu__link" href="<?php the_sub_field('stsm_section_link'); ?>"><?php the_sub_field('stsm_menu_item_text'); ?></a>
                </li>

            <?php endwhile;
            echo "</ul>";
        endif;
        ?>

	</section>

</article>